<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockedIp;
use App\Models\HomepageSetting;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use SabitAhmad\SteadFast\DTO\OrderRequest;
use SabitAhmad\SteadFast\Facades\SteadFast;

class OrderController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view-orders,admin', only: ['index', 'show', 'fraudCheck', 'checkPhoneRisk']),
            new Middleware('permission:edit-orders,admin', only: ['updateStatus', 'sendToSteadfast', 'toggleFraud', 'blockIp', 'unblockIp']),
            new Middleware('permission:delete-orders,admin', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $query = Order::query()->with('items')->latest();

        if ($request->filled('status')) {
            if ($request->status === 'fraud') {
                $query->where('is_fraud', true);
            } else {
                $query->where('order_status', $request->status);
            }
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
            'fraud' => Order::where('is_fraud', true)->count('*'),
        ];

        // Auto Phone Risk Analysis for orders on current page
        $phones = $orders->pluck('customer_phone')->filter()->unique()->values();

        $phoneStats = collect();
        if ($phones->isNotEmpty()) {
            $phoneStats = Order::whereIn('customer_phone', $phones)
                ->select(
                    'customer_phone',
                    DB::raw('count(*) as total'),
                    DB::raw("sum(case when order_status = 'delivered' then 1 else 0 end) as delivered"),
                    DB::raw("sum(case when order_status = 'cancelled' then 1 else 0 end) as cancelled"),
                    DB::raw("sum(case when order_status = 'returned' then 1 else 0 end) as returned"),
                    DB::raw('sum(case when is_fraud = 1 then 1 else 0 end) as fraud_count')
                )
                ->groupBy('customer_phone')
                ->get()
                ->keyBy('customer_phone');
        }

        $blockedIps = BlockedIp::pluck('ip_address')->flip();

        $pageFraudCount = 0;
        foreach ($orders as $order) {
            $stat = $phoneStats[$order->customer_phone] ?? null;
            $hasBlockedIp = ! empty($order->ip_address) && isset($blockedIps[$order->ip_address]);
            $hasFraudPast = $stat && $stat->fraud_count > 0;
            $hasHighCancel = $stat && ($stat->cancelled + $stat->returned) >= 1 && ($stat->delivered == 0 && $stat->total > 1);

            if ($order->is_fraud || $hasBlockedIp || $hasFraudPast || $hasHighCancel) {
                $pageFraudCount++;
            }
        }

        return view('backend.orders.index', compact('orders', 'statusCounts', 'phoneStats', 'blockedIps', 'pageFraudCount'));
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

    public function toggleFraud(Request $request, Order $order): RedirectResponse|JsonResponse
    {
        $order->is_fraud = ! $order->is_fraud;
        if ($order->is_fraud) {
            $order->fraud_reason = $request->input('reason', 'Marked as fraud by admin');
        } else {
            $order->fraud_reason = null;
        }
        $order->save();

        $statusMsg = $order->is_fraud ? 'Order #'.$order->invoice_no.' marked as FRAUD.' : 'Fraud flag removed from Order #'.$order->invoice_no.'.';

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_fraud' => (bool) $order->is_fraud,
                'message' => $statusMsg,
            ]);
        }

        return back()->with('success', $statusMsg);
    }

    public function blockIp(Request $request, Order $order): RedirectResponse|JsonResponse
    {
        $ip = $request->input('ip_address') ?: $order->ip_address;

        if (empty($ip)) {
            $errMsg = 'No IP address provided or recorded for this order.';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $errMsg], 422);
            }

            return back()->with('error', $errMsg);
        }

        BlockedIp::firstOrCreate(
            ['ip_address' => $ip],
            [
                'reason' => 'Blocked via Order #'.$order->invoice_no.' ('.$order->customer_phone.')',
                'blocked_by' => auth('admin')->id(),
            ]
        );

        $order->update([
            'ip_address' => $order->ip_address ?: $ip,
            'is_fraud' => true,
            'fraud_reason' => 'IP Address blocked ('.$ip.')',
        ]);

        $msg = 'IP '.$ip.' has been BLOCKED and order flagged as Fraud.';

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $msg]);
        }

        return back()->with('success', $msg);
    }

    public function unblockIp(Request $request, Order $order): RedirectResponse|JsonResponse
    {
        if (! empty($order->ip_address)) {
            BlockedIp::where('ip_address', $order->ip_address)->delete();
        }

        $msg = 'IP '.$order->ip_address.' has been unblocked.';

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $msg]);
        }

        return back()->with('success', $msg);
    }

    public function fraudCheck(Order $order): JsonResponse
    {
        $phone = $order->customer_phone;
        $ip = $order->ip_address;

        $phoneOrders = Order::where('customer_phone', $phone)->get();
        $totalOrders = $phoneOrders->count();
        $deliveredOrders = $phoneOrders->where('order_status', 'delivered')->count();
        $cancelledOrders = $phoneOrders->where('order_status', 'cancelled')->count();
        $returnedOrders = $phoneOrders->where('order_status', 'returned')->count();
        $fraudOrders = $phoneOrders->where('is_fraud', true)->count();

        $ipBlocked = ! empty($ip) && BlockedIp::isBlocked($ip);
        $ipOrdersCount = ! empty($ip) ? Order::where('ip_address', $ip)->count() : 0;

        $successRate = $totalOrders > 0 ? round(($deliveredOrders / $totalOrders) * 100, 1) : 0;

        $riskScore = 0;
        $reasons = [];

        if ($ipBlocked) {
            $riskScore += 90;
            $reasons[] = 'Customer IP is currently BLOCKED';
        }

        if ($fraudOrders > 0) {
            $riskScore += 60;
            $reasons[] = "Found {$fraudOrders} previous fraudulent order(s) with this phone number";
        }

        if ($cancelledOrders + $returnedOrders >= 2) {
            $riskScore += 40;
            $reasons[] = "High cancellation/return history ({$cancelledOrders} cancelled, {$returnedOrders} returned)";
        }

        if ($ipOrdersCount > 5) {
            $riskScore += 25;
            $reasons[] = "High order volume ({$ipOrdersCount} orders) placed from the same IP";
        }

        if ($order->is_fraud) {
            $riskScore = max($riskScore, 85);
            $reasons[] = $order->fraud_reason ?: 'Flagged as Fraud by administrator';
        }

        // Live Courier Check via Steadfast API if configured
        $courierData = null;
        $steadfastConfig = HomepageSetting::get('steadfast_settings');
        if (! empty($steadfastConfig['api_key']) && ! empty($steadfastConfig['secret_key'])) {
            try {
                $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
                if (str_starts_with($cleanPhone, '8801') && strlen($cleanPhone) === 13) {
                    $cleanPhone = substr($cleanPhone, 2);
                }
                $baseUrl = rtrim($steadfastConfig['base_url'] ?? 'https://portal.packzy.com/api/v1', '/');
                $response = Http::withHeaders([
                    'Api-Key' => $steadfastConfig['api_key'],
                    'Secret-Key' => $steadfastConfig['secret_key'],
                    'Content-Type' => 'application/json',
                ])->timeout(3)->get("{$baseUrl}/fraud_check/{$cleanPhone}");

                if ($response->successful()) {
                    $json = $response->json();
                    $courierData = [
                        'total' => $json['total_parcels'] ?? $json['total'] ?? 0,
                        'delivered' => $json['total_delivered'] ?? $json['delivered'] ?? 0,
                        'cancelled' => $json['total_cancelled'] ?? $json['cancelled'] ?? 0,
                    ];
                    if (! empty($courierData['cancelled']) && $courierData['cancelled'] >= 2) {
                        $riskScore = max($riskScore, 80);
                        $reasons[] = "Steadfast Courier: {$courierData['cancelled']} cancelled parcels nationwide";
                    }
                }
            } catch (\Throwable $e) {
                // Ignore gracefully if courier credentials fail
            }
        }

        $riskLevel = 'Low Risk';
        $riskColor = 'success';
        if ($riskScore >= 70) {
            $riskLevel = 'High Risk (Suspicious)';
            $riskColor = 'danger';
        } elseif ($riskScore >= 35) {
            $riskLevel = 'Medium Risk';
            $riskColor = 'warning';
        }

        return response()->json([
            'order_id' => $order->id,
            'invoice_no' => $order->invoice_no,
            'customer_name' => $order->customer_name,
            'customer_phone' => $phone,
            'ip_address' => $ip ?: 'Not recorded',
            'is_fraud' => (bool) $order->is_fraud,
            'is_ip_blocked' => $ipBlocked,
            'risk_score' => min(100, $riskScore),
            'risk_level' => $riskLevel,
            'risk_color' => $riskColor,
            'reasons' => $reasons,
            'stats' => [
                'total' => $totalOrders,
                'delivered' => $deliveredOrders,
                'cancelled' => $cancelledOrders,
                'returned' => $returnedOrders,
                'fraud' => $fraudOrders,
                'success_rate' => $successRate,
                'ip_orders' => $ipOrdersCount,
            ],
            'courier_data' => $courierData,
        ]);
    }

    public function checkPhoneRisk(Request $request): JsonResponse
    {
        $phone = $request->input('phone');
        if (empty($phone)) {
            return response()->json(['success' => false, 'message' => 'Phone number is required.'], 422);
        }

        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($cleanPhone, '8801') && strlen($cleanPhone) === 13) {
            $cleanPhone = substr($cleanPhone, 2);
        }

        $phoneOrders = Order::where('customer_phone', 'like', "%{$cleanPhone}%")->get();
        $totalOrders = $phoneOrders->count();
        $deliveredOrders = $phoneOrders->where('order_status', 'delivered')->count();
        $cancelledOrders = $phoneOrders->where('order_status', 'cancelled')->count();
        $returnedOrders = $phoneOrders->where('order_status', 'returned')->count();
        $fraudOrders = $phoneOrders->where('is_fraud', true)->count();

        $successRate = $totalOrders > 0 ? round(($deliveredOrders / $totalOrders) * 100, 1) : 0;

        $riskScore = 0;
        $reasons = [];

        if ($fraudOrders > 0) {
            $riskScore += 70;
            $reasons[] = "Found {$fraudOrders} previous order(s) marked as FRAUD";
        }
        if ($cancelledOrders + $returnedOrders >= 2) {
            $riskScore += 45;
            $reasons[] = "High cancellation rate ({$cancelledOrders} cancelled, {$returnedOrders} returned)";
        } elseif ($cancelledOrders + $returnedOrders === 1) {
            $riskScore += 25;
            $reasons[] = 'Customer has 1 previous cancelled/returned order';
        }

        $courierData = null;
        $steadfastConfig = HomepageSetting::get('steadfast_settings');
        if (! empty($steadfastConfig['api_key']) && ! empty($steadfastConfig['secret_key'])) {
            try {
                $baseUrl = rtrim($steadfastConfig['base_url'] ?? 'https://portal.packzy.com/api/v1', '/');
                $response = Http::withHeaders([
                    'Api-Key' => $steadfastConfig['api_key'],
                    'Secret-Key' => $steadfastConfig['secret_key'],
                    'Content-Type' => 'application/json',
                ])->timeout(3)->get("{$baseUrl}/fraud_check/{$cleanPhone}");

                if ($response->successful()) {
                    $json = $response->json();
                    $courierData = [
                        'total' => $json['total_parcels'] ?? $json['total'] ?? 0,
                        'delivered' => $json['total_delivered'] ?? $json['delivered'] ?? 0,
                        'cancelled' => $json['total_cancelled'] ?? $json['cancelled'] ?? 0,
                    ];
                    if (! empty($courierData['cancelled']) && $courierData['cancelled'] >= 2) {
                        $riskScore = max($riskScore, 80);
                        $reasons[] = "Steadfast Courier: {$courierData['cancelled']} cancelled parcels nationwide";
                    }
                }
            } catch (\Throwable $e) {
                // Ignore gracefully
            }
        }

        $riskLevel = 'Low Risk';
        $riskColor = 'success';
        if ($riskScore >= 60) {
            $riskLevel = 'High Risk (Fraud Likely)';
            $riskColor = 'danger';
        } elseif ($riskScore >= 30) {
            $riskLevel = 'Medium Risk';
            $riskColor = 'warning';
        }

        return response()->json([
            'success' => true,
            'phone' => $phone,
            'clean_phone' => $cleanPhone,
            'risk_score' => min(100, $riskScore),
            'risk_level' => $riskLevel,
            'risk_color' => $riskColor,
            'reasons' => $reasons,
            'stats' => [
                'total' => $totalOrders,
                'delivered' => $deliveredOrders,
                'cancelled' => $cancelledOrders,
                'returned' => $returnedOrders,
                'fraud' => $fraudOrders,
                'success_rate' => $successRate,
            ],
            'courier_data' => $courierData,
        ]);
    }
}
