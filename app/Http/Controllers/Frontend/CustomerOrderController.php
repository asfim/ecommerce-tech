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
        $orders = Order::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('frontend.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        abort_if($order->user_id !== auth()->id(), 404);

        $order->load('items');

        return view('frontend.orders.show', compact('order'));
    }
}
