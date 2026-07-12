<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\BulkSmsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Log;
use Raziul\Sslcommerz\Facades\Sslcommerz;

class OrderController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $sms = new BulkSmsService;

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'shipping_method' => 'required|in:inside_dhaka,outside_dhaka',
            'shipping_cost' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cod,sslcommerz',
            'subtotal' => 'required|numeric|min:0',
            'tax' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',   // may be overridden
            'coupon_code' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|integer',
            'items.*.product_name' => 'required|string|max:255',
            'items.*.product_image' => 'nullable|string',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.variants' => 'nullable|array',
        ]);

        // Apply coupon if present
        $discountAmount = 0.00;
        $couponCode = null;
        if (! empty($validated['coupon_code'])) {
            $coupon = Coupon::where('code', $validated['coupon_code'])->first();
            if ($coupon && $coupon->isValidForSubtotal($validated['subtotal'])) {
                $discountAmount = $coupon->calculateDiscount($validated['subtotal']);
                $couponCode = $coupon->code;
            }
        }

        // Calculate totals (use provided tax)
        $shippingCost = (float) $validated['shipping_cost'];
        $subtotal = (float) $validated['subtotal'];
        $tax = (float) $validated['tax'];
        $total = max(0, $subtotal - $discountAmount + $shippingCost + $tax);

        // Create order in transaction
        $order = DB::transaction(function () use ($validated, $couponCode, $discountAmount, $tax, $total) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'invoice_no' => Order::generateInvoiceNo(),
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_address' => $validated['customer_address'],
                'shipping_method' => $validated['shipping_method'],
                'shipping_cost' => $validated['shipping_cost'],
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'coupon_code' => $couponCode,
                'discount_amount' => $discountAmount,
                'subtotal' => $validated['subtotal'],
                'tax' => $tax,
                'total' => $total,
            ]);

            foreach ($validated['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'] ?? null,
                    'product_name' => $item['product_name'],
                    'product_image' => $item['product_image'] ?? null,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'variants' => $item['variants'] ?? null,
                    'line_total' => $item['price'] * $item['quantity'],
                ]);
            }

            return $order;
        });

        // Post-order actions: SMS and payment gateway
        try {
            // 1. Send SMS notification
            $message = "Dear {$order->customer_name},\n\n"
                     ."Your order has been placed successfully.\n"
                     ."Order No: {$order->invoice_no}\n"
                     .'Amount: '.number_format($order->total, 2)." Tk\n"
                     .'Thank you for shopping with us.';

            $smsResponse = $sms->send($order->customer_phone, $message);
            if (($smsResponse['response_code'] ?? null) == 1000) {
                Log::info('SMS Sent Successfully', $smsResponse);
            } else {
                Log::error('SMS Sending Failed', $smsResponse);
            }

            // 2. Handle SSLCommerz if selected
            if ($order->payment_method === 'sslcommerz') {
                try {
                    $response = Sslcommerz::setOrder((float) $order->total, $order->invoice_no, 'Online Order')
                        ->setCustomer(
                            $order->customer_name,
                            auth()->user()->email ?? 'customer@example.com',
                            $order->customer_phone,
                            $order->customer_address
                        )
                        ->setShippingInfo(count($validated['items']), $order->customer_address)
                        ->makePayment();

                    if ($response->success()) {
                        return response()->json([
                            'success' => true,
                            'message' => 'Redirecting to payment gateway...',
                            'invoice_no' => $order->invoice_no,
                            'redirect' => $response->gatewayPageURL(),
                        ]);
                    }

                    return response()->json([
                        'success' => false,
                        'message' => 'SSLCommerz payment initiation failed: '.($response->failedReason() ?? 'Unknown error'),
                    ], 400);
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'SSLCommerz integration error: '.$e->getMessage(),
                    ], 500);
                }
            }

            // 3. COD or other – return success with invoice
            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'invoice_no' => $order->invoice_no,
                'redirect' => route('order.invoice', $order->invoice_no),
            ]);

        } catch (\Exception $e) {
            // Log the error and return a generic failure response
            Log::error('Order processing error: '.$e->getMessage(), ['order_id' => $order->id ?? null]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your order. Please try again later.',
            ], 500);
        }
    }

    public function invoice(string $invoiceNo): View
    {
        $order = Order::where('invoice_no', $invoiceNo)->firstOrFail();
        $order->load('items');

        return view('invoice', compact('order'));
    }
}
