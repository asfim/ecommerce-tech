<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use SabitAhmad\SteadFast\DTO\OrderRequest;
use SabitAhmad\SteadFast\Facades\SteadFast;

class OrderController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view-orders,admin', only: ['index', 'show']),
            new Middleware('permission:edit-orders,admin', only: ['updateStatus', 'sendToSteadfast']),
            new Middleware('permission:delete-orders,admin', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $query = Order::query()->with('items')->latest();

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 15);

        if ($perPage === 'all') {
            $orders = $query->paginate($query->count('*') ?: 15);
        } else {
            $perPage = in_array((int) $perPage, [10, 15, 20, 30, 50]) ? (int) $perPage : 15;
            $orders = $query->paginate($perPage);
        }

        $statusCounts = [
            'all' => Order::count(),
            'pending' => Order::where('order_status', '=', 'pending', 'and')->count('*'),
            'confirmed' => Order::where('order_status', '=', 'confirmed', 'and')->count('*'),
            'delivered' => Order::where('order_status', '=', 'delivered', 'and')->count('*'),
            'cancelled' => Order::where('order_status', '=', 'cancelled', 'and')->count('*'),
            'returned' => Order::where('order_status', '=', 'returned', 'and')->count('*'),
        ];

        return view('backend.orders.index', compact('orders', 'statusCounts'));
    }

    public function bulkPrint(Request $request): View
    {
        $idsString = $request->input('ids', '');
        $ids = array_filter(explode(',', $idsString));

        if (empty($ids)) {
            abort(404, 'No orders selected.');
        }

        $orders = Order::query()->whereIn('id', $ids, 'and', false)->with('items')->latest()->get();

        return view('backend.orders.bulk-print', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load('items');

        return view('backend.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'order_status' => 'nullable|in:pending,confirmed,delivered,cancelled,returned',
            'payment_status' => 'nullable|in:pending,paid',
        ]);

        $updateData = array_filter($validated, fn ($value) => ! is_null($value));

        if (isset($updateData['order_status']) && $updateData['order_status'] === 'delivered') {
            $updateData['payment_status'] = 'paid';
        }

        if ($order->payment_status === 'paid') {
            if (
                (isset($updateData['order_status']) && $updateData['order_status'] === 'pending') ||
                (isset($updateData['payment_status']) && $updateData['payment_status'] === 'pending')
            ) {
                return back()->with('error', 'Cannot change status to Pending for a Paid order.');
            }
        }

        if (! empty($updateData)) {
            $oldStatus = $order->order_status;
            $order->update($updateData);

            if (isset($updateData['order_status'])) {
                $newStatus = $updateData['order_status'];
                if ($oldStatus !== $newStatus) {
                    $order->load('items.product');
                    $this->adjustStock($order, $newStatus, $oldStatus);
                }
            }
        }

        $messages = [];
        if (isset($updateData['order_status'])) {
            $messages[] = 'Order status updated to '.ucfirst($updateData['order_status']);
        }
        if (isset($updateData['payment_status'])) {
            $messages[] = 'Payment status updated to '.ucfirst($updateData['payment_status']);
        }

        $message = empty($messages) ? 'Order updated.' : implode(' and ', $messages);

        return back()->with('success', $message);
    }

    public function destroy(Order $order): RedirectResponse
    {
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }

    public function sendToSteadfast(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:orders,id',
        ]);

        $orders = Order::query()->whereIn('id', $validated['ids'], 'and', false)->whereIn('order_status', ['pending', 'confirmed'])->with('items')->get();

        $successCount = 0;
        $errors = [];

        /** @var Order $order */
        foreach ($orders as $order) {
            try {
                // Clean the phone number to 11 digits starting with 01
                $phone = preg_replace('/[^0-9]/', '', $order->customer_phone);
                if (str_starts_with($phone, '8801') && strlen($phone) === 13) {
                    $phone = substr($phone, 2);
                } elseif (str_starts_with($phone, '01') && strlen($phone) === 11) {
                    // Already correct
                } else {
                    if (strlen($phone) === 10 && str_starts_with($phone, '1')) {
                        $phone = '0'.$phone;
                    }
                }

                // Prepare item details
                $productName = $order->items->first()?->product_name ?? 'Multiple Products';
                if ($order->items->count() > 1) {
                    $productName = $order->items->count().' items (incl. '.$productName.')';
                }

                $productName = substr($productName, 0, 100);

                $orderData = [
                    'invoice' => $order->invoice_no,
                    'recipient_name' => substr($order->customer_name, 0, 99),
                    'recipient_phone' => $phone,
                    'recipient_address' => substr($order->customer_address, 0, 249),
                    'cod_amount' => $order->payment_status === 'paid' ? 0.00 : (float) $order->total,
                    'note' => 'Deliver securely. Contact client.',
                ];

                $orderRequest = OrderRequest::fromArray($orderData);
                $response = SteadFast::createOrder($orderRequest);

                $oldStatus = $order->order_status;
                $order->update([
                    'order_status' => 'delivered',
                    'payment_status' => $order->payment_status === 'paid' ? 'paid' : 'pending',
                ]);

                if ($oldStatus !== 'delivered') {
                    $order->load('items.product');
                    $this->adjustStock($order, 'delivered', $oldStatus);
                }

                $successCount++;
            } catch (\Exception $e) {
                $errors[] = "Order #{$order->invoice_no}: ".$e->getMessage();
            }
        }

        if ($successCount > 0 && count($errors) === 0) {
            $message = "Successfully sent {$successCount} order(s) to Steadfast Courier!";
            session()->flash('success', $message);
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                ]);
            }

            return redirect()->back();
        } elseif ($successCount > 0) {
            $message = "Sent {$successCount} order(s) successfully. Some orders failed:\n".implode("\n", $errors);
            session()->flash('success', $message);
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                ]);
            }

            return redirect()->back();
        } else {
            $message = "Failed to send orders to Steadfast:\n".implode("\n", $errors);
            session()->flash('error', $message);
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ]);
            }

            return redirect()->back();
        }
    }

    private function adjustStock(Order $order, string $newStatus, string $oldStatus)
    {
        $decrements = ['confirmed', 'delivered'];
        $increments = ['cancelled', 'returned'];

        $wasDecremented = in_array($oldStatus, $decrements);
        $willBeDecremented = in_array($newStatus, $decrements);

        if (! $wasDecremented && $willBeDecremented) {
            // Decrease stock
            foreach ($order->items as $item) {
                $this->updateStock($item, -1);
            }
        } elseif ($wasDecremented && ! $willBeDecremented) {
            // Increase stock
            foreach ($order->items as $item) {
                $this->updateStock($item, 1);
            }
        }
    }

    private function updateStock(OrderItem $item, int $multiplier)
    {
        $product = $item->product;
        if (! $product) {
            return;
        }

        $quantity = $item->quantity * $multiplier;

        $product->stock += $quantity;

        if (! empty($item->variants) && is_array($product->variants)) {
            $updatedVariants = $product->variants;
            $found = false;

            foreach ($updatedVariants as &$variant) {
                if (isset($variant['combo']) && is_array($variant['combo'])) {
                    $match = true;
                    foreach ($item->variants as $key => $value) {
                        $comboValue = null;
                        foreach ($variant['combo'] as $cKey => $cVal) {
                            if (strtolower(trim($cKey)) === strtolower(trim($key))) {
                                $comboValue = $cVal;
                                break;
                            }
                        }
                        if ($comboValue !== $value) {
                            $match = false;
                            break;
                        }
                    }
                    if ($match) {
                        $variant['stock'] = max(0, (int) ($variant['stock'] ?? 0) + $quantity);
                        $found = true;
                        break;
                    }
                }
            }

            if ($found) {
                $product->variants = $updatedVariants;
            }
        }

        $product->stock = max(0, $product->stock);
        $product->save();
    }
}
