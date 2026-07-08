<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'category_id', 'sub_category_id', 'brand_id', 'price', 'discount_type', 'discount_value', 'stock', 'sales_count', 'slug', 'variants', 'image', 'images', 'is_active', 'is_featured'];

    protected function casts(): array
    {
        return [
            'variants' => 'array',
            'images' => 'array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
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
}
