<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductLandingPage extends Model
{
    protected $fillable = [
        'product_id',
        'is_active',
        'meta_title',
        'tagline',
        'heading',
        'description',
        'delivery_text',
        'return_text',
        'offer_text',
        'old_price',
        'new_price',
        'discount_text',
        'stock_text',
        'whatsapp_number',
        'whatsapp_text',
        'features',
        'testimonials',
        'image',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'features' => 'array',
            'testimonials' => 'array',
            'old_price' => 'decimal:2',
            'new_price' => 'decimal:2',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

