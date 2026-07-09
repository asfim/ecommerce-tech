<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the application returns a successful response and renders layout components', function () {
    $user = User::factory()->create([
        'name' => 'Betty T. Niles',
    ]);

    $response = $this->actingAs($user)->get('/');

    $response->assertStatus(200)
        ->assertSee('Betty T. Niles') // header component check
        ->assertSee('eCommerce CMS is an online shopping platform') // footer component check
        ->assertSee('Trending Categories'); // home view content check
});

test('the home page search returns matching products and hides non-matching ones', function () {
    $user = User::factory()->create();

    $category = Category::create(['name' => 'Clothing']);
    $brand = Brand::create(['name' => 'Zara']);

    $matchProduct = Product::create([
        'name' => 'Zara Winter Jacket',
        'slug' => 'zara-winter-jacket',
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'price' => 120.00,
        'stock' => 10,
        'is_active' => true,
    ]);

    $nonMatchProduct = Product::create([
        'name' => 'Nike Shoes',
        'slug' => 'nike-shoes',
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'price' => 80.00,
        'stock' => 5,
        'is_active' => true,
    ]);

    $response = $this->actingAs($user)->get('/?search=Jacket');

    $response->assertStatus(200)
        ->assertSee('Zara Winter Jacket')
        ->assertSee('Search Results for')
        ->assertSee('Jacket')
        ->assertDontSee('Nike Shoes');
});

test('the search API returns matching products in JSON format', function () {
    $user = User::factory()->create();

    $category = Category::create(['name' => 'Clothing']);
    $brand = Brand::create(['name' => 'Zara']);

    $product = Product::create([
        'name' => 'Zara Winter Jacket',
        'slug' => 'zara-winter-jacket',
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'price' => 120.00,
        'stock' => 10,
        'is_active' => true,
    ]);

    $response = $this->actingAs($user)->getJson(route('products.search-api', ['q' => 'Jacket']));

    $response->assertStatus(200)
        ->assertJsonFragment([
            'name' => 'Zara Winter Jacket',
            'price' => '120.00',
        ]);
});
