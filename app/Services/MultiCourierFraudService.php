<?php

namespace App\Services;

use App\Models\BlockedIp;
use App\Models\HomepageSetting;
use App\Models\Order;

class MultiCourierFraudService
{
    /**
     * Check fraud risk purely from store order cancellation history and blocked IP data.
     * All external APIs removed. Fraud is evaluated based on customer cancellation history.
     *
     * @return array{
     *     phone: string,
     *     clean_phone: string,
     *     is_fraud: bool,
     *     risk_score: int,
     *     risk_level: string,
     *     risk_color: string,
     *     total_orders: int,
     *     total_delivered: int,
     *     total_cancelled: int,
     *     total_returned: int,
     *     success_rate: float,
     *     cancel_rate: float,
     *     reasons: array<string>,
     *     store_stats: array<string, int|float>,
     *     stats: array<string, int|float>
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

        // Significant 10 digits for robust matching (+88017... / 017...)
        $searchPhone = strlen($cleanPhone) >= 10 ? substr($cleanPhone, -10) : $cleanPhone;

        // 2. Fetch Customer Order History from Database
        $orderQuery = Order::query()->where(function ($q) use ($cleanPhone, $searchPhone) {
            $q->where('customer_phone', 'like', "%{$searchPhone}%")
                ->orWhere('customer_phone', 'like', "%{$cleanPhone}%");
        });

        // Exclude the current order being evaluated so past history is measured
        if ($order && $order->id) {
            $orderQuery->where('id', '!=', $order->id);
        }

        $storeOrders = $orderQuery->get();

        $storeTotal = $storeOrders->count();
        $storeDelivered = $storeOrders->where('order_status', 'delivered')->count();
        $storeCancelled = $storeOrders->where('order_status', 'cancelled')->count();
        $storeReturned = $storeOrders->where('order_status', 'returned')->count();
        $storeFraud = $storeOrders->where('is_fraud', true)->count();
        $totalCancelled = $storeCancelled + $storeReturned;

        $cancelRate = $storeTotal > 0 ? round(($totalCancelled / $storeTotal) * 100, 1) : 0;
        $successRate = $storeTotal > 0 ? round(($storeDelivered / $storeTotal) * 100, 1) : 0;

        // 3. Load Fraud Cancellation Rules
        $fraudSettings = HomepageSetting::get('courier_fraud_settings', [
            'cancel_threshold' => 2, // 2 or more cancellations = FRAUD
            'cancel_rate_threshold' => 40, // 40% cancel rate = FRAUD
            'auto_block_ip' => true,
        ]);

        $cancelThreshold = (int) ($fraudSettings['cancel_threshold'] ?? 2);
        $cancelRateThreshold = (float) ($fraudSettings['cancel_rate_threshold'] ?? 40.0);

        // 4. Fraud Evaluation Engine Based on Order Cancellations
        $isFraud = false;
        $riskScore = 0;
        $reasons = [];

        // Rule A: Order Cancellation Threshold (e.g. 2 or more cancellations)
        if ($totalCancelled >= $cancelThreshold) {
            $isFraud = true;
            $riskScore += min(85, 50 + ($totalCancelled * 10));
            $reasons[] = "High Order Cancellation: Customer has {$totalCancelled} cancelled/returned order(s) in history ({$cancelRate}% cancel rate).";
        } elseif ($totalCancelled >= 1 && $storeTotal >= 2 && $cancelRate >= $cancelRateThreshold) {
            $isFraud = true;
            $riskScore += 65;
            $reasons[] = "High Cancellation Rate: {$cancelRate}% of customer orders were cancelled ({$totalCancelled} of {$storeTotal} orders).";
        } elseif ($totalCancelled === 1) {
            $riskScore += 25;
            $reasons[] = 'Customer has 1 cancelled order in history.';
        }

        // Rule B: Previous store order flagged as fraud
        if ($storeFraud > 0) {
            $isFraud = true;
            $riskScore += 40;
            $reasons[] = "Customer has {$storeFraud} past order(s) flagged as FRAUD in our store.";
        }

        // Rule C: Customer IP is blocked
        if ($order && ! empty($order->ip_address) && BlockedIp::isBlocked($order->ip_address)) {
            $isFraud = true;
            $riskScore += 50;
            $reasons[] = "Customer IP ({$order->ip_address}) is on the BLOCKED IP list.";
        }

        // Rule D: Order was manually flagged as fraud by admin
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

        // 5. Auto-Update Order if $order is passed and fraud is detected
        if ($order && $isFraud) {
            $mainReason = ! empty($reasons) ? $reasons[0] : 'Auto-detected fraud: High order cancellations';
            $order->update([
                'is_fraud' => true,
                'fraud_reason' => $mainReason,
            ]);

            // Auto-block IP if enabled in settings
            if (($fraudSettings['auto_block_ip'] ?? true) && ! empty($order->ip_address)) {
                BlockedIp::firstOrCreate(
                    ['ip_address' => $order->ip_address],
                    [
                        'reason' => 'Auto-blocked: High order cancellations ('.$cleanPhone.')',
                        'blocked_by' => auth('admin')->id(),
                    ]
                );
            }
        }

        $stats = [
            'total' => $storeTotal,
            'delivered' => $storeDelivered,
            'cancelled' => $storeCancelled,
            'returned' => $storeReturned,
            'fraud' => $storeFraud,
            'success_rate' => $successRate,
            'cancel_rate' => $cancelRate,
        ];

        return [
            'phone' => $phone,
            'clean_phone' => $cleanPhone,
            'is_fraud' => $isFraud,
            'risk_score' => $riskScore,
            'risk_level' => $riskLevel,
            'risk_color' => $riskColor,
            'total_orders' => $storeTotal,
            'total_delivered' => $storeDelivered,
            'total_cancelled' => $totalCancelled,
            'total_returned' => $storeReturned,
            'success_rate' => $successRate,
            'cancel_rate' => $cancelRate,
            'reasons' => $reasons,
            'store_stats' => $stats,
            'stats' => $stats,
        ];
    }
}
