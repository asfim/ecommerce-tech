<?php

namespace App\Models;

use Database\Factories\CouponFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    /** @use HasFactory<CouponFactory> */
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_amount',
        'is_active',
        'expires_at',
    ];

    /**
     * @return array{is_active: 'boolean', expires_at: 'datetime'}
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Check if coupon is active, not expired, and meets the minimum order amount.
     */
    public function isValidForSubtotal(float $subtotal): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($subtotal < $this->min_order_amount) {
            return false;
        }

        return true;
    }

    /**
     * Calculate discount amount based on order subtotal.
     */
    public function calculateDiscount(float $subtotal): float
    {
        if ($this->type === 'percent') {
            return round(($subtotal * ($this->value / 100)), 2);
        }

        // Fixed coupon
        return min($this->value, $subtotal);
    }
}
