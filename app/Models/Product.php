<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'category_id', 'brand_id', 'price', 'stock', 'slug', 'variants', 'image', 'is_active'];

    protected function casts(): array
    {
        return [
            'variants' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
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
}
