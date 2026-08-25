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
                
                if ($item->product && !empty($item->variants) && !empty($item->product->variants)) {
                    foreach ($item->product->variants as $v) {
                        if (isset($v['combo'])) {
                            $isMatch = true;
                            foreach ($v['combo'] as $k => $val) {
                                if (!isset($item->variants[$k]) || $item->variants[$k] !== $val) {
                                    $isMatch = false;
                                    break;
                                }
                            }
                            if ($isMatch && count($v['combo']) === count($item->variants)) {
                                if (isset($v['buy_price']) && is_numeric($v['buy_price'])) {
                                    $buyPrice = (float) $v['buy_price'];
                                }
                                break;
                            }
                        }
                    }
                }
                
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
    public function stock(Request $request): View
    {
        $products = Product::with('category')->get();

        $totalProducts = $products->count();
        $totalStockQty = 0;
        $stockValueCost = 0.00;
        $stockValueRetail = 0.00;
        $outOfStockCount = 0;
        $lowStockCount = 0;

        $categoryStockArray = [];

        foreach ($products as $product) {
            $hasVariants = false;
            $pTotalStock = 0;
            $pTotalCost = 0.00;
            $pTotalRetail = 0.00;
            
            if (!empty($product->variants)) {
                $isNewStructure = false;
                foreach ($product->variants as $v) {
                    if (isset($v['combo'])) {
                        $isNewStructure = true;
                        break;
                    }
                }
                
                if ($isNewStructure) {
                    $hasVariants = true;
                    foreach ($product->variants as $v) {
                        if (!isset($v['active']) || $v['active']) {
                            $vBuyPrice = isset($v['buy_price']) && is_numeric($v['buy_price']) ? (float) $v['buy_price'] : (float) ($product->buy_price ?? 0);
                            $vSalePrice = isset($v['price']) && is_numeric($v['price']) ? (float) $v['price'] : (float) ($product->price ?? 0);
                            $vStock = isset($v['stock']) ? (int) $v['stock'] : 0;
                            
                            $pTotalStock += $vStock;
                            $pTotalCost += ($vBuyPrice * $vStock);
                            $pTotalRetail += ($vSalePrice * $vStock);
                        }
                    }
                }
            }
            
            if (!$hasVariants) {
                $buyPrice = $product->buy_price ?? 0.00;
                $salePrice = $product->price ?? 0.00;
                $pStock = (int) $product->stock;

                $pTotalStock = $pStock;
                $pTotalCost = ($buyPrice * $pStock);
                $pTotalRetail = ($salePrice * $pStock);
            }

            // Bind computed values to product for table display and sorting
            $product->computed_stock = $pTotalStock;
            $product->computed_cost = $pTotalCost;
            $product->computed_retail = $pTotalRetail;
            
            // Add to totals
            $totalStockQty += $pTotalStock;
            $stockValueCost += $pTotalCost;
            $stockValueRetail += $pTotalRetail;

            if ($pTotalStock === 0) {
                $outOfStockCount++;
            } elseif ($pTotalStock <= 5) {
                $lowStockCount++;
            }

            // Category aggregation
            $catId = $product->category_id;
            $catName = $product->category->name ?? 'Uncategorized';
            if (!isset($categoryStockArray[$catId])) {
                $categoryStockArray[$catId] = [
                    'category_name' => $catName,
                    'total_stock' => 0,
                    'retail_value' => 0.00
                ];
            }
            $categoryStockArray[$catId]['total_stock'] += $pTotalStock;
            $categoryStockArray[$catId]['retail_value'] += $pTotalRetail;
        }

        $potentialProfit = $stockValueRetail - $stockValueCost;

        // Sort collection by computed stock ascending
        $sortedProducts = $products->sortBy('computed_stock')->values();

        // Manual pagination
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 15;
        $currentPageItems = $sortedProducts->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $paginatedProducts = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems,
            $sortedProducts->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        // Prepare category stock for chart
        $categoryStock = collect($categoryStockArray)->sortByDesc('retail_value')->values()->all();

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
