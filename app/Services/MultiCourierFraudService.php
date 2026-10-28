<?php

namespace App\Services;

use App\Models\BlockedIp;
use App\Models\HomepageSetting;
use App\Models\Order;
use Illuminate\Support\Facades\Http;

class MultiCourierFraudService
{
    /**
     * Check fraud risk across all major Bangladeshi couriers and store history.
     *
     * @return array{
     *     clean_phone: string,
     *     is_fraud: bool,
     *     risk_score: int,
     *     risk_level: string,
     *     risk_color: string,
     *     total_parcels: int,
     *     total_delivered: int,
     *     total_cancelled: int,
     *     success_rate: float,
     *     cancel_rate: float,
     *     couriers: array<string, array{name: string, icon: string, delivered: int, cancelled: int, total: int}>,
     *     reasons: array<string>,
     *     store_stats: array<string, int|float>
     * }
     */
    public static function check(string $phone, ?Order $order = null): array
    {
        // 1. Clean phone number to 11-digit Bangladeshi mobile format
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($cleanPhone, '8801') && strlen($cleanPhone) === 13) {
            $cleanPhone = substr($cleanPhone, 2);
        } elseif (strlen($cleanPhone) === 10 && str_starts_with($cleanPhone, '1')) {
            $cleanPhone = '0'.$cleanPhone;
        }

        // 2. Fetch Store Order History (Always Active)
        $storeOrders = Order::where('customer_phone', 'like', "%{$cleanPhone}%")->get();
        $storeTotal = $storeOrders->count();
        $storeDelivered = $storeOrders->where('order_status', 'delivered')->count();
        $storeCancelled = $storeOrders->where('order_status', 'cancelled')->count();
        $storeReturned = $storeOrders->where('order_status', 'returned')->count();
        $storeFraud = $storeOrders->where('is_fraud', true)->count();
        $storeTotalCancelled = $storeCancelled + $storeReturned;

        // 3. Courier Data Aggregation with Graceful Bypass
        $couriers = [
            'steadfast' => ['name' => 'Steadfast Courier', 'icon' => 'truck', 'delivered' => 0, 'cancelled' => 0, 'total' => 0, 'status' => 'bypassed'],
            'pathao' => ['name' => 'Pathao Courier', 'icon' => 'bicycle', 'delivered' => 0, 'cancelled' => 0, 'total' => 0, 'status' => 'bypassed'],
            'redx' => ['name' => 'RedX Delivery', 'icon' => 'box-seam', 'delivered' => 0, 'cancelled' => 0, 'total' => 0, 'status' => 'bypassed'],
            'paperfly' => ['name' => 'Paperfly Courier', 'icon' => 'send', 'delivered' => 0, 'cancelled' => 0, 'total' => 0, 'status' => 'bypassed'],
            'ecourier' => ['name' => 'eCourier', 'icon' => 'lightning', 'delivered' => 0, 'cancelled' => 0, 'total' => 0, 'status' => 'bypassed'],
            'store' => ['name' => 'Store History', 'icon' => 'shop', 'delivered' => $storeDelivered, 'cancelled' => $storeTotalCancelled, 'total' => $storeTotal, 'status' => 'active'],
        ];

        $courierSettings = HomepageSetting::get('courier_fraud_settings', [
            'cancel_threshold' => 2, // 2 or more cancellations = FRAUD
            'cancel_rate_threshold' => 40, // 40% cancel rate = FRAUD
            'auto_block_ip' => true,
        ]);

        $cancelThreshold = (int) ($courierSettings['cancel_threshold'] ?? 2);
        $cancelRateThreshold = (float) ($courierSettings['cancel_rate_threshold'] ?? 40.0);

        // A. Direct Steadfast Courier API
        $steadfastConfig = HomepageSetting::get('steadfast_settings');
        if (! empty($steadfastConfig['api_key']) && ! empty($steadfastConfig['secret_key'])) {
            try {
                $baseUrl = rtrim($steadfastConfig['base_url'] ?? 'https://portal.packzy.com/api/v1', '/');
                $res = Http::withHeaders([
                    'Api-Key' => $steadfastConfig['api_key'],
                    'Secret-Key' => $steadfastConfig['secret_key'],
                    'Content-Type' => 'application/json',
                ])->timeout(3)->get("{$baseUrl}/fraud_check/{$cleanPhone}");

                if ($res->successful()) {
                    $sfData = $res->json();
                    $sfDelivered = (int) ($sfData['total_delivered'] ?? $sfData['delivered'] ?? 0);
                    $sfCancelled = (int) ($sfData['total_cancelled'] ?? $sfData['cancelled'] ?? 0);
                    $couriers['steadfast']['delivered'] = $sfDelivered;
                    $couriers['steadfast']['cancelled'] = $sfCancelled;
                    $couriers['steadfast']['total'] = $sfDelivered + $sfCancelled;
                    $couriers['steadfast']['status'] = 'checked';
                }
            } catch (\Throwable $e) {
                // Gracefully bypass if endpoint fails
                $couriers['steadfast']['status'] = 'bypassed';
            }
        }

        // B. Direct Pathao Courier API
        $pathaoConfig = HomepageSetting::get('pathao_settings');
        $pathaoToken = $pathaoConfig['token'] ?? $pathaoConfig['api_key'] ?? '';
        if (! empty($pathaoToken)) {
            try {
                $baseUrl = rtrim($pathaoConfig['base_url'] ?? 'https://api-hermes.pathao.com', '/');
                $res = Http::withHeaders([
                    'Authorization' => 'Bearer '.$pathaoToken,
                    'Accept' => 'application/json',
                ])->timeout(3)->get("{$baseUrl}/aladdin/api/v1/orders", [
                    'phone' => $cleanPhone,
                ]);

                if ($res->successful()) {
                    $pData = $res->json();
                    $del = (int) ($pData['delivered'] ?? $pData['data']['delivered'] ?? 0);
                    $canc = (int) ($pData['cancelled'] ?? $pData['data']['cancelled'] ?? 0);
                    $couriers['pathao']['delivered'] = $del;
                    $couriers['pathao']['cancelled'] = $canc;
                    $couriers['pathao']['total'] = $del + $canc;
                    $couriers['pathao']['status'] = 'checked';
                }
            } catch (\Throwable $e) {
                // Gracefully bypass
                $couriers['pathao']['status'] = 'bypassed';
            }
        }

        // C. Direct RedX Delivery API
        $redxConfig = HomepageSetting::get('redx_settings');
        $redxToken = $redxConfig['access_token'] ?? $redxConfig['token'] ?? '';
        if (! empty($redxToken)) {
            try {
                $baseUrl = rtrim($redxConfig['base_url'] ?? 'https://openapi.redx.com.bd', '/');
                $res = Http::withHeaders([
                    'API-ACCESS-TOKEN' => 'Bearer '.$redxToken,
                    'Accept' => 'application/json',
                ])->timeout(3)->get("{$baseUrl}/v1.0.0-beta/parcels", [
                    'phone' => $cleanPhone,
                ]);

                if ($res->successful()) {
                    $rData = $res->json();
                    $del = (int) ($rData['delivered'] ?? $rData['data']['delivered'] ?? 0);
                    $canc = (int) ($rData['cancelled'] ?? $rData['data']['cancelled'] ?? 0);
                    $couriers['redx']['delivered'] = $del;
                    $couriers['redx']['cancelled'] = $canc;
                    $couriers['redx']['total'] = $del + $canc;
                    $couriers['redx']['status'] = 'checked';
                }
            } catch (\Throwable $e) {
                // Gracefully bypass
                $couriers['redx']['status'] = 'bypassed';
            }
        }

        // D. Direct Paperfly Courier API
        $pfConfig = HomepageSetting::get('paperfly_settings');
        if (! empty($pfConfig['username']) && ! empty($pfConfig['password'])) {
            try {
                $baseUrl = rtrim($pfConfig['base_url'] ?? 'https://api.paperfly.com.bd', '/');
                $res = Http::withBasicAuth($pfConfig['username'], $pfConfig['password'])
                    ->timeout(3)
                    ->get("{$baseUrl}/api/v1/tracking", [
                        'phone' => $cleanPhone,
                    ]);

                if ($res->successful()) {
                    $pfData = $res->json();
                    $del = (int) ($pfData['delivered'] ?? 0);
                    $canc = (int) ($pfData['cancelled'] ?? 0);
                    $couriers['paperfly']['delivered'] = $del;
                    $couriers['paperfly']['cancelled'] = $canc;
                    $couriers['paperfly']['total'] = $del + $canc;
                    $couriers['paperfly']['status'] = 'checked';
                }
            } catch (\Throwable $e) {
                // Gracefully bypass
                $couriers['paperfly']['status'] = 'bypassed';
            }
        }

        // E. Direct eCourier API
        $ecConfig = HomepageSetting::get('ecourier_settings');
        if (! empty($ecConfig['api_key'])) {
            try {
                $baseUrl = rtrim($ecConfig['base_url'] ?? 'https://ecourier.com.bd/api', '/');
                $res = Http::withHeaders([
                    'USER-ID' => $ecConfig['user_id'] ?? '',
                    'API-KEY' => $ecConfig['api_key'],
                    'API-SECRET' => $ecConfig['api_secret'] ?? '',
                ])->timeout(3)->post("{$baseUrl}/track", [
                    'phone' => $cleanPhone,
                ]);

                if ($res->successful()) {
                    $ecData = $res->json();
                    $del = (int) ($ecData['delivered'] ?? 0);
                    $canc = (int) ($ecData['cancelled'] ?? 0);
                    $couriers['ecourier']['delivered'] = $del;
                    $couriers['ecourier']['cancelled'] = $canc;
                    $couriers['ecourier']['total'] = $del + $canc;
                    $couriers['ecourier']['status'] = 'checked';
                }
            } catch (\Throwable $e) {
                // Gracefully bypass
                $couriers['ecourier']['status'] = 'bypassed';
            }
        }

        // 4. Calculate Aggregate Metrics Across All Couriers (Bypassed couriers contribute 0)
        $totalDelivered = 0;
        $totalCancelled = 0;
        foreach ($couriers as $c) {
            $totalDelivered += $c['delivered'];
            $totalCancelled += $c['cancelled'];
        }
        $totalParcels = $totalDelivered + $totalCancelled;

        $cancelRate = $totalParcels > 0 ? round(($totalCancelled / $totalParcels) * 100, 1) : 0;
        $successRate = $totalParcels > 0 ? round(($totalDelivered / $totalParcels) * 100, 1) : 0;

        // 5. Fraud Evaluation Engine: "beshi cancle hole ei number frud hobe"
        $isFraud = false;
        $riskScore = 0;
        $reasons = [];

        // Rule A: High cancellations across couriers
        if ($totalCancelled >= $cancelThreshold) {
            $isFraud = true;
            $riskScore += min(75, 45 + ($totalCancelled * 10));
            $reasons[] = "High Courier Cancellation: Customer has {$totalCancelled} cancelled/returned parcels across couriers ({$cancelRate}% cancel rate).";
        } elseif ($totalCancelled >= 1 && $cancelRate >= $cancelRateThreshold) {
            $isFraud = true;
            $riskScore += 55;
            $reasons[] = "High Cancellation Ratio: {$cancelRate}% of parcels cancelled ({$totalCancelled} cancelled).";
        } elseif ($totalCancelled === 1) {
            $riskScore += 25;
            $reasons[] = 'Customer has 1 cancelled parcel record across couriers.';
        }

        // Rule B: Previous store fraud record
        if ($storeFraud > 0) {
            $isFraud = true;
            $riskScore += 40;
            $reasons[] = "Customer has {$storeFraud} order(s) flagged as FRAUD in our store.";
        }

        // Rule C: Customer IP is blocked
        if ($order && ! empty($order->ip_address) && BlockedIp::isBlocked($order->ip_address)) {
            $isFraud = true;
            $riskScore += 50;
            $reasons[] = "Customer IP ({$order->ip_address}) is on the BLOCKED IP list.";
        }

        // Rule D: If order was already marked fraud by admin
        if ($order && $order->is_fraud) {
            $isFraud = true;
            $riskScore = max($riskScore, 85);
            $reasons[] = $order->fraud_reason ?: 'Flagged as Fraud by administrator';
        }

        $riskScore = min(100, $riskScore);

        // Determine Risk Color and Label
        if ($isFraud || $riskScore >= 60) {
            $riskLevel = 'High Risk (Fraud Likely)';
            $riskColor = 'danger';
        } elseif ($riskScore >= 25) {
            $riskLevel = 'Medium Risk';
            $riskColor = 'warning';
        } else {
            $riskLevel = 'Low Risk (Safe Customer)';
            $riskColor = 'success';
        }

        // 6. Auto-Update Order if $order is passed
        if ($order && $isFraud) {
            $mainReason = ! empty($reasons) ? $reasons[0] : 'Auto-detected fraud due to high courier cancellations';
            $order->update([
                'is_fraud' => true,
                'fraud_reason' => $mainReason,
            ]);

            // Auto-block IP if enabled in settings
            if (($courierSettings['auto_block_ip'] ?? true) && ! empty($order->ip_address)) {
                BlockedIp::firstOrCreate(
                    ['ip_address' => $order->ip_address],
                    [
                        'reason' => 'Auto-blocked: High courier cancellation ('.$cleanPhone.')',
                        'blocked_by' => auth('admin')->id(),
                    ]
                );
            }
        }

        return [
            'phone' => $phone,
            'clean_phone' => $cleanPhone,
            'is_fraud' => $isFraud,
            'risk_score' => $riskScore,
            'risk_level' => $riskLevel,
            'risk_color' => $riskColor,
            'total_parcels' => $totalParcels,
            'total_delivered' => $totalDelivered,
            'total_cancelled' => $totalCancelled,
            'success_rate' => $successRate,
            'cancel_rate' => $cancelRate,
            'couriers' => $couriers,
            'reasons' => $reasons,
            'store_stats' => [
                'total' => $storeTotal,
                'delivered' => $storeDelivered,
                'cancelled' => $storeCancelled,
                'returned' => $storeReturned,
                'fraud' => $storeFraud,
                'success_rate' => $storeTotal > 0 ? round(($storeDelivered / $storeTotal) * 100, 1) : 0,
            ],
            'stats' => [
                'total' => $storeTotal,
                'delivered' => $storeDelivered,
                'cancelled' => $storeCancelled,
                'returned' => $storeReturned,
                'fraud' => $storeFraud,
                'success_rate' => $storeTotal > 0 ? round(($storeDelivered / $storeTotal) * 100, 1) : 0,
            ],
        ];
    }
}
