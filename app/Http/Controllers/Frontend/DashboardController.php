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

        $monthlyOrderCounts = [];
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');

            $count = Order::where('user_id', auth()->id())
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $monthlyOrderCounts[] = $count;
        }

        return view('frontend.dashboard.index', compact('totalOrders', 'months', 'monthlyOrderCounts'));
    }
}
