<?php

use App\Models\Coupon;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('coupon application endpoint returns success for valid coupon', function () {
    $coupon = Coupon::create([
        'code' => 'SAVE10',
        'type' => 'percent',
        'value' => 10.00,
        'min_order_amount' => 0.00,
        'is_active' => true,
        'expires_at' => now()->addYear(),
    ]);

    $response = $this->postJson(route('coupon.apply'), [
        'code' => 'SAVE10',
        'subtotal' => 100.00,
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'code' => 'SAVE10',
            'discount' => 10.00,
            'type' => 'percent',
            'value' => 10.00,
        ]);
});

test('coupon application fails if subtotal is below minimum order amount', function () {
    $coupon = Coupon::create([
        'code' => 'FLAT50',
        'type' => 'fixed',
        'value' => 50.00,
        'min_order_amount' => 200.00,
        'is_active' => true,
        'expires_at' => now()->addYear(),
    ]);

    $response = $this->postJson(route('coupon.apply'), [
        'code' => 'FLAT50',
        'subtotal' => 150.00,
    ]);

    $response->assertStatus(422)
        ->assertJson([
            'success' => false,
            'message' => 'Minimum order amount to apply this code is ৳200.00',
        ]);
});

test('coupon application fails for expired coupon', function () {
    $coupon = Coupon::create([
        'code' => 'EXPIRED',
        'type' => 'percent',
        'value' => 10.00,
        'min_order_amount' => 0.00,
        'is_active' => true,
        'expires_at' => now()->subDay(),
    ]);

    $response = $this->postJson(route('coupon.apply'), [
        'code' => 'EXPIRED',
        'subtotal' => 100.00,
    ]);

    $response->assertStatus(422)
        ->assertJson([
            'success' => false,
            'message' => 'This discount code has expired.',
        ]);
});

test('authenticated user can place an order applying valid coupon discount', function () {
    $user = User::factory()->create();

    $coupon = Coupon::create([
        'code' => 'SAVE10',
        'type' => 'percent',
        'value' => 10.00,
        'min_order_amount' => 0.00,
        'is_active' => true,
        'expires_at' => now()->addYear(),
    ]);

    $orderPayload = [
        'customer_name' => 'John Doe',
        'customer_phone' => '01700000000',
        'customer_address' => 'Dhaka, Bangladesh',
        'shipping_method' => 'inside_dhaka',
        'shipping_cost' => 60.00,
        'payment_method' => 'cod',
        'subtotal' => 500.00,
        'tax' => 25.00,
        'total' => 585.00,
        'coupon_code' => 'SAVE10',
        'items' => [
            [
                'product_name' => 'Premium Headphone',
                'price' => 500.00,
                'quantity' => 1,
                'variants' => [],
            ],
        ],
    ];

    $response = $this->actingAs($user)->postJson(route('order.store'), $orderPayload);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ]);

    $order = Order::latest()->first();

    // Check database to ensure coupon is applied and totals recalculated securely
    // Subtotal 500 - Discount (10% of 500 = 50) + Shipping 60 + Tax (5% of 500 = 25) = 535
    expect($order->coupon_code)->toBe('SAVE10')
        ->and($order->discount_amount)->toEqual(50.00)
        ->and($order->total)->toEqual(535.00);
});
