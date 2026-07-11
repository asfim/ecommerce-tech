<?php

use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Raziul\Sslcommerz\Data\PaymentResponse;
use Raziul\Sslcommerz\Facades\Sslcommerz;

uses(RefreshDatabase::class);

beforeEach(function () {
    config([
        'sslcommerz.store.id' => 'teststore',
        'sslcommerz.store.password' => 'testpassword',
        'sslcommerz.store.currency' => 'BDT',
        'sslcommerz.sandbox' => true,
    ]);
});

test('placing order with sslcommerz redirects to payment gateway', function () {
    // Mock Sslcommerz Facade
    Sslcommerz::shouldReceive('setOrder')
        ->once()
        ->andReturnSelf();

    Sslcommerz::shouldReceive('setCustomer')
        ->once()
        ->andReturnSelf();

    Sslcommerz::shouldReceive('setShippingInfo')
        ->once()
        ->andReturnSelf();

    $mockResponse = Mockery::mock(PaymentResponse::class);
    $mockResponse->shouldReceive('success')->andReturn(true);
    $mockResponse->shouldReceive('gatewayPageURL')->andReturn('https://mocked-sslcommerz-url.com');

    Sslcommerz::shouldReceive('makePayment')
        ->once()
        ->andReturn($mockResponse);

    $response = $this->postJson(route('order.store'), [
        'customer_name' => 'John Doe',
        'customer_phone' => '01712345678',
        'customer_address' => 'Dhaka, Bangladesh',
        'shipping_method' => 'inside_dhaka',
        'shipping_cost' => 60,
        'payment_method' => 'sslcommerz',
        'subtotal' => 500,
        'tax' => 0,
        'total' => 560,
        'items' => [
            [
                'product_name' => 'Test Product',
                'price' => 500,
                'quantity' => 1,
            ],
        ],
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'redirect' => 'https://mocked-sslcommerz-url.com',
        ]);

    $this->assertDatabaseHas('orders', [
        'customer_name' => 'John Doe',
        'payment_method' => 'sslcommerz',
        'payment_status' => 'pending',
    ]);
});

test('success callback validates payment and marks order as paid', function () {
    $order = Order::create([
        'invoice_no' => 'INV-TEST-001',
        'customer_name' => 'John Doe',
        'customer_phone' => '01712345678',
        'customer_address' => 'Dhaka',
        'shipping_method' => 'inside_dhaka',
        'shipping_cost' => 60,
        'payment_method' => 'sslcommerz',
        'payment_status' => 'pending',
        'order_status' => 'pending',
        'subtotal' => 500,
        'tax' => 0,
        'total' => 560,
    ]);

    Sslcommerz::shouldReceive('validatePayment')
        ->once()
        ->with(Mockery::type('array'), 'INV-TEST-001', 560.0)
        ->andReturn(true);

    $response = $this->post(route('sslc.success'), [
        'tran_id' => 'INV-TEST-001',
        'val_id' => 'validation_id_123',
    ]);

    $response->assertRedirect(route('order.invoice', $order->invoice_no));

    $this->assertDatabaseHas('orders', [
        'invoice_no' => 'INV-TEST-001',
        'payment_status' => 'paid',
    ]);
});

test('failure callback marks order as failed', function () {
    $order = Order::create([
        'invoice_no' => 'INV-TEST-002',
        'customer_name' => 'John Doe',
        'customer_phone' => '01712345678',
        'customer_address' => 'Dhaka',
        'shipping_method' => 'inside_dhaka',
        'shipping_cost' => 60,
        'payment_method' => 'sslcommerz',
        'payment_status' => 'pending',
        'order_status' => 'pending',
        'subtotal' => 500,
        'tax' => 0,
        'total' => 560,
    ]);

    $response = $this->post(route('sslc.failure'), [
        'tran_id' => 'INV-TEST-002',
    ]);

    $response->assertRedirect(route('checkout'));

    $this->assertDatabaseHas('orders', [
        'invoice_no' => 'INV-TEST-002',
        'payment_status' => 'failed',
    ]);
});

test('cancel callback marks order as cancelled', function () {
    $order = Order::create([
        'invoice_no' => 'INV-TEST-003',
        'customer_name' => 'John Doe',
        'customer_phone' => '01712345678',
        'customer_address' => 'Dhaka',
        'shipping_method' => 'inside_dhaka',
        'shipping_cost' => 60,
        'payment_method' => 'sslcommerz',
        'payment_status' => 'pending',
        'order_status' => 'pending',
        'subtotal' => 500,
        'tax' => 0,
        'total' => 560,
    ]);

    $response = $this->post(route('sslc.cancel'), [
        'tran_id' => 'INV-TEST-003',
    ]);

    $response->assertRedirect(route('checkout'));

    $this->assertDatabaseHas('orders', [
        'invoice_no' => 'INV-TEST-003',
        'payment_status' => 'cancelled',
    ]);
});
