<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('checkout page renders successfully for authenticated users', function () {
    $user =
        \App\Models\User::factory()->create();

    $response = $this->actingAs($user)->get(route('checkout'));

    $response->assertStatus(200);
    $response->assertSee('Shipping address');
    $response->assertSee('SSL Commerz');
});

test('guest users cannot access checkout page', function () {
    $response = $this->get(route('checkout'));

    $response->assertRedirect(route('user.login'));
});

test('guest users cannot place orders', function () {
    $response = $this->postJson(route('order.store'), [
        'customer_name' => 'Guest Customer',
        'customer_phone' => '01700000000',
        'customer_address' => 'Guest Address',
        'shipping_method' => 'inside_dhaka',
        'shipping_cost' => 0,
        'payment_method' => 'cod',
        'subtotal' => 100,
        'tax' => 0,
        'total' => 100,
        'items' => [
            [
                'product_id' => 1,
                'product_name' => 'Sample Product',
                'product_image' => null,
                'price' => 100,
                'quantity' => 1,
                'variants' => [],
            ],
        ],
    ]);

    $response->assertRedirect(route('user.login'));
});
