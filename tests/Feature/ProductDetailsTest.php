<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('product details page renders successfully', function () {
    $category = Category::create([
        'name' => 'Electronics',
        'slug' => 'electronics',
        'is_active' => true,
    ]);

    $brand = Brand::create([
        'name' => 'Sony',
        'slug' => 'sony',
        'is_active' => true,
    ]);

    $product = Product::create([
        'name' => 'Premium Wireless Headphone Test',
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'price' => 4990,
        'stock' => 50,
        'is_active' => true,
    ]);

    $response = $this->get(route('product.details', $product->slug));

    $response->assertStatus(200);
    $response->assertSee('Premium Wireless Headphone Test');
    $response->assertSee('৳4,990.00');
});
