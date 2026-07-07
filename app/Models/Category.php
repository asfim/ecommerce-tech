<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'description', 'image', 'is_active', 'is_trending'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_trending' => 'boolean',
        ];
    }

    public function subCategories(): HasMany
    {
        return $this->hasMany(SubCategory::class);
    }
}
