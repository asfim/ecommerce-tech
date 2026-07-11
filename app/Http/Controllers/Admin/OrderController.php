<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class OrderController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view-orders,admin', only: ['index', 'show']),
            new Middleware('permission:edit-orders,admin', only: ['updateStatus']),
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
            $orders = $query->paginate($query->count() ?: 15);
        } else {
            $perPage = in_array((int) $perPage, [10, 15, 20, 30, 50]) ? (int) $perPage : 15;
            $orders = $query->paginate($perPage);
        }

        $statusCounts = [
            'all' => Order::count(),
            'pending' => Order::where('order_status', 'pending')->count(),
            'delivered' => Order::where('order_status', 'delivered')->count(),
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

        $orders = Order::whereIn('id', $ids)->with('items')->latest()->get();

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
            'order_status' => 'nullable|in:pending,delivered,cancelled',
            'payment_status' => 'nullable|in:pending,paid',
        ]);

        $updateData = array_filter($validated, fn ($value) => ! is_null($value));

        if (isset($updateData['order_status']) && $updateData['order_status'] === 'delivered') {
            $updateData['payment_status'] = 'paid';
        }

        if (! empty($updateData)) {
            $order->update($updateData);
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
}
