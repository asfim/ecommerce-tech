<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $totalSales = Order::sum('total');
        $totalUsers = User::count();
        $totalProducts = Product::count();

        return view('backend.dashboard.index', compact(
            'totalOrders',
            'totalSales',
            'totalUsers',
            'totalProducts'
        ));
    }
}
