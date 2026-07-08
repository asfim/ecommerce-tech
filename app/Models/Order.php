<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'invoice_no',
        'customer_name',
        'customer_phone',
        'customer_address',
        'shipping_method',
        'shipping_cost',
        'payment_method',
        'payment_status',
        'order_status',
        'subtotal',
        'tax',
        'total',
    ];

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
