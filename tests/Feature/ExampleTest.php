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
        ->assertSee('Jacket');

    $response->assertViewHas('products', function ($products) use ($matchProduct, $nonMatchProduct) {
        return $products->contains($matchProduct) && ! $products->contains($nonMatchProduct);
    });
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

test('home page ajax pagination returns next batch of products', function () {
    $user = User::factory()->create();

    $category = Category::create(['name' => 'Clothing']);
    $brand = Brand::create(['name' => 'Zara']);

    for ($i = 1; $i <= 20; $i++) {
        Product::forceCreate([
            'name' => "Product {$i}",
            'slug' => "product-{$i}",
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'price' => 10.00,
            'stock' => 10,
            'is_active' => true,
            'created_at' => now()->addMinutes($i),
        ]);
    }

    $response = $this->actingAs($user)->get('/');
    $response->assertStatus(200)
        ->assertSee('Load more');

    $response->assertViewHas('products', function ($products) {
        return $products->count() === 12
            && $products->pluck('name')->contains('Product 20')
            && ! $products->pluck('name')->contains('Product 8');
    });

    $response = $this->actingAs($user)->get('/?page=2', [
        'HTTP_X-Requested-With' => 'XMLHttpRequest',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure(['html', 'has_more'])
        ->assertJsonFragment(['has_more' => false]);

    $responseContent = $response->json('html');
    expect($responseContent)->toContain('Product 8')
        ->toContain('Product 1')
        ->not->toContain('Product 20');
});
