<?php

namespace App\Models;

use App\Services\SmsService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class Order extends Model
{
    protected static function booted(): void
    {
        static::updated(function (Order $order) {
            if ($order->isDirty('order_status') && $order->order_status === 'delivered') {
                try {
                    $settings = HomepageSetting::get('sms_settings', []);
                    if (! empty($settings['enabled'])) {
                        $message = strtr($settings['message_template'] ?? '', [
                            '{customer_name}' => $order->customer_name,
                            '{invoice_no}' => $order->invoice_no,
                            '{total_amount}' => $order->total,
                            '{order_status}' => $order->order_status,
                        ]);

                        SmsService::send($order->customer_phone, $message);
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to process order delivered SMS: '.$e->getMessage());
                }
            }
        });
    }

    protected $fillable = [
        'user_id',
        'invoice_no',
        'customer_name',
        'customer_phone',
        'customer_address',
        'shipping_method',
        'shipping_cost',
        'payment_method',
        'payment_status',
        'order_status',
        'coupon_code',
        'discount_amount',
        'subtotal',
        'tax',
        'total',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<OrderItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function generateInvoiceNo(): string
    {
        $prefix = 'INV-'.date('Ymd').'-';
        $lastOrder = self::where('invoice_no', 'like', $prefix.'%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastOrder) {
            $lastNumber = (int) str_replace($prefix, '', $lastOrder->invoice_no);

            return $prefix.str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        }

        return $prefix.'0001';
    }
}
