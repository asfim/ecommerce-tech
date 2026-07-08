<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalOrders = Order::where('user_id', auth()->id())->count();

        return view('frontend.dashboard.index', compact('totalOrders'));
    }
}
