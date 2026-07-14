<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerOrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::where('user_id', auth()->id())->with('items')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                    ->orWhereHas('items', function ($itemQuery) use ($search) {
                        $itemQuery->where('product_name', 'like', "%{$search}%");
                    });
            });
        }

        $perPage = $request->input('per_page', 10);

        if ($perPage === 'all') {
            $orders = $query->paginate($query->count() ?: 10);
        } else {
            $perPage = in_array((int) $perPage, [10, 20, 30, 50]) ? (int) $perPage : 10;
            $orders = $query->paginate($perPage);
        }

        return view('frontend.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        abort_if($order->user_id !== auth()->id(), 404);

        $order->load('items');

        return view('frontend.orders.show', compact('order'));
    }
}
