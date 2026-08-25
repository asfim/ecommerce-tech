<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $fillable = [
        'name', 'category_id', 'sub_category_id', 'brand_id', 'buy_price', 'price',
        'discount_type', 'discount_value', 'discount_start_date', 'discount_expiry_date',
        'stock', 'sales_count', 'slug', 'variants', 'image', 'images',
        'is_active', 'is_featured', 'is_new_arrival',
        'description', 'specifications',
    ];

    protected function casts(): array
    {
        return [
            'variants' => 'array',
            'images' => 'array',
            'specifications' => 'array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_new_arrival' => 'boolean',
            'discount_start_date' => 'datetime',
            'discount_expiry_date' => 'datetime',
        ];
    }

    public function getHasActiveDiscountAttribute(): bool
    {
        if (! $this->discount_type || $this->discount_value <= 0) {
            return false;
        }

        $now = now();

        if ($this->discount_start_date && $this->discount_start_date->gt($now)) {
            return false;
        }

        if ($this->discount_expiry_date && $this->discount_expiry_date->lt($now)) {
            return false;
        }

        return true;
    }

    public function scopeFrontendActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('discount_expiry_date')
                    ->orWhere('discount_expiry_date', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('discount_start_date')
                    ->orWhere('discount_start_date', '<=', now());
            });
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function landingPage(): HasOne
    {
        return $this->hasOne(ProductLandingPage::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    protected static function booted()
    {
        static::creating(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $product->name)));
            }
        });

        static::updating(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $product->name)));
            }
        });
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function getAverageRatingAttribute(): float
    {
        return round($this->reviews_avg_rating ?? 0, 1);
    }

    public function getReviewsCountAttribute(): int
    {
        return (int) ($this->reviews_count ?? 0);
    }
}
