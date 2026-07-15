<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('checkout page renders successfully for authenticated users', function () {
    $user =
        User::factory()->create();

    $response = $this->actingAs($user)->get(route('checkout'));

    $response->assertStatus(200);
    $response->assertSee('Shipping address');
    $response->assertSee('SSL Commerz');
});

test('guest users can access checkout page', function () {
    $response = $this->get(route('checkout'));

    $response->assertStatus(200);
    $response->assertSee('Shipping address');
});

test('guest users can place orders', function () {
    $this->seed();

    $category = \App\Models\Category::create(['name' => 'Electronics']);
    $brand = \App\Models\Brand::create(['name' => 'Samsung']);
    $product = \App\Models\Product::create([
        'name' => 'Sample Product',
        'slug' => 'sample-product',
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'price' => 100.00,
        'stock' => 10,
    ]);

    $response = $this->postJson(route('order.store'), [
        'customer_name' => 'Guest Customer',
        'customer_phone' => '01700000000',
        'customer_address' => 'Guest Address',
        'shipping_method' => 'inside_dhaka',
        'shipping_cost' => 0,
        'payment_method' => 'cod',
        'subtotal' => $product->price,
        'tax' => 0,
        'total' => $product->price,
        'items' => [
            [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_image' => $product->image,
                'price' => $product->price,
                'quantity' => 1,
                'variants' => [],
            ],
        ],
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ]);

    $this->assertDatabaseHas('orders', [
        'customer_name' => 'Guest Customer',
        'customer_phone' => '01700000000',
        'user_id' => null,
    ]);
});
