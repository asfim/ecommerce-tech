<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'shipping_method' => 'required|in:inside_dhaka,outside_dhaka',
            'shipping_cost' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cod,sslcommerz',
            'subtotal' => 'required|numeric|min:0',
            'tax' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|integer',
            'items.*.product_name' => 'required|string|max:255',
            'items.*.product_image' => 'nullable|string',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.variants' => 'nullable|array',
        ]);

        $order = DB::transaction(function () use ($validated) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'invoice_no' => Order::generateInvoiceNo(),
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_address' => $validated['customer_address'],
                'shipping_method' => $validated['shipping_method'],
                'shipping_cost' => $validated['shipping_cost'],
                'payment_method' => $validated['payment_method'],
                'payment_status' => $validated['payment_method'] === 'cod' ? 'pending' : 'pending',
                'order_status' => 'pending',
                'subtotal' => $validated['subtotal'],
                'tax' => $validated['tax'],
                'total' => $validated['total'],
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

        return response()->json([
            'success' => true,
            'message' => 'Order placed successfully!',
            'invoice_no' => $order->invoice_no,
            'redirect' => route('order.invoice', $order->invoice_no),
        ]);
    }

    public function invoice(string $invoiceNo): View
    {
        $order = Order::where('invoice_no', $invoiceNo)->firstOrFail();
        $order->load('items');

        return view('invoice', compact('order'));
    }
}
