<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('category products page renders successfully with products', function () {
    $category = Category::create([
        'name' => 'Fashion & Apparels',
        'is_active' => true,
    ]);

    $brand = Brand::create([
        'name' => 'Premium Brand',
        'slug' => 'premium-brand',
        'is_active' => true,
    ]);

    $product = Product::create([
        'name' => 'Premium Silk Saree Test',
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'price' => 3500.00,
        'stock' => 20,
        'is_active' => true,
    ]);

    // Active product in another category
    $otherCategory = Category::create([
        'name' => 'Gadgets',
        'is_active' => true,
    ]);
    $otherProduct = Product::create([
        'name' => 'Smart Watch Pro Test',
        'category_id' => $otherCategory->id,
        'brand_id' => $brand->id,
        'price' => 2500.00,
        'stock' => 10,
        'is_active' => true,
    ]);

    $response = $this->get(route('category.products', $category->id));

    $response->assertStatus(200);
    $response->assertSee('Fashion & Apparels');
    $response->assertSee('Premium Silk Saree Test');
    $response->assertSee('Tk 3,500');
    $response->assertDontSee('Smart Watch Pro Test');
});

test('category products page returns 404 for invalid category id', function () {
    $response = $this->get(route('category.products', 99999));
    $response->assertStatus(404);
});

test('homepage displays hot categories links successfully', function () {
    $category = Category::create([
        'name' => 'Hot Category Alpha',
        'is_active' => true,
    ]);

    $response = $this->get(route('home'));

    $response->assertStatus(200);
    $response->assertSee(route('category.products', $category->id));
});
