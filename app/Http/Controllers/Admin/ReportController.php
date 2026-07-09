<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Display the sales report.
     */
    public function sales(Request $request): View
    {
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : Carbon::now()->subDays(30)->startOfDay();

        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : Carbon::now()->endOfDay();

        $status = $request->input('order_status', 'delivered');

        $query = Order::query()->whereBetween('created_at', [$startDate, $endDate]);

        if ($status !== 'all') {
            $query->where('order_status', $status);
        }

        $orders = $query->with('items.product')->get();

        $totalOrders = $orders->count();
        $totalRevenue = 0.00;
        $totalCost = 0.00;
        $totalDiscount = 0.00;
        $totalItemsSold = 0;

        foreach ($orders as $order) {
            $totalRevenue += (float) $order->subtotal;
            $totalDiscount += (float) $order->discount_amount;

            foreach ($order->items as $item) {
                $totalItemsSold += (int) $item->quantity;
                $buyPrice = $item->product ? (float) $item->product->buy_price : 0.00;
                $totalCost += $buyPrice * $item->quantity;
            }
        }

        $netProfit = ($totalRevenue - $totalCost) - $totalDiscount;
        $profitMargin = $totalRevenue > 0 ? ($netProfit / $totalRevenue) * 100 : 0;

        $chartDataQuery = Order::query()->whereBetween('created_at', [$startDate, $endDate]);

        if ($status !== 'all') {
            $chartDataQuery->where('order_status', $status);
        }

        $chartData = $chartDataQuery
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(subtotal) as revenue'),
                DB::raw('COUNT(id) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $topSelling = OrderItem::query()
            ->whereHas('order', function ($q) use ($startDate, $endDate, $status) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
                if ($status !== 'all') {
                    $q->where('order_status', $status);
                }
            })
            ->select(
                'product_name',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(line_total) as total_revenue')
            )
            ->groupBy('product_name')
            ->orderBy('total_qty', 'desc')
            ->limit(10)
            ->get();

        return view('backend.reports.sales', compact(
            'startDate',
            'endDate',
            'status',
            'totalOrders',
            'totalRevenue',
            'totalCost',
            'totalDiscount',
            'totalItemsSold',
            'netProfit',
            'profitMargin',
            'chartData',
            'topSelling'
        ));
    }

    /**
     * Display the stock report.
     */
    public function stock(): View
    {
        $products = Product::with('category')->get();

        $totalProducts = $products->count();
        $totalStockQty = $products->sum('stock');

        $stockValueCost = 0.00;
        $stockValueRetail = 0.00;

        foreach ($products as $product) {
            $buyPrice = $product->buy_price ?? 0.00;
            $salePrice = $product->price ?? 0.00;

            $stockValueCost += ($buyPrice * $product->stock);
            $stockValueRetail += ($salePrice * $product->stock);
        }

        $potentialProfit = $stockValueRetail - $stockValueCost;

        $outOfStockCount = $products->where('stock', 0)->count();
        $lowStockCount = $products->where('stock', '>', 0)->where('stock', '<=', 5)->count();

        $paginatedProducts = Product::with('category')
            ->orderBy('stock', 'asc')
            ->paginate(15);

        $categoryStock = Product::query()
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'categories.name as category_name',
                DB::raw('SUM(products.stock) as total_stock'),
                DB::raw('SUM(products.stock * products.price) as retail_value')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('retail_value', 'desc')
            ->get();

        return view('backend.reports.stock', compact(
            'totalProducts',
            'totalStockQty',
            'stockValueCost',
            'stockValueRetail',
            'potentialProfit',
            'outOfStockCount',
            'lowStockCount',
            'paginatedProducts',
            'categoryStock'
        ));
    }
}
