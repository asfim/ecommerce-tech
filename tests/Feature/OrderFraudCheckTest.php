<?php

use App\Models\Admin;
use App\Models\BlockedIp;
use App\Models\HomepageSetting;
use App\Models\Order;
use App\Services\MultiCourierFraudService;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;

beforeEach(function () {
    $this->withoutMiddleware(ValidateCsrfToken::class);
    $this->seed(RolePermissionSeeder::class);

    $this->admin = Admin::firstOrCreate(
        ['email' => 'admin_test@example.com'],
        ['password' => bcrypt('password')]
    );
    $this->admin->assignRole('Super Admin');

    $this->order = Order::create([
        'invoice_no' => 'INV-FRAUD-001',
        'customer_name' => 'Fraud Test Customer',
        'customer_phone' => '01899999999',
        'customer_address' => 'Dhaka',
        'shipping_method' => 'inside_dhaka',
        'shipping_cost' => 60,
        'payment_method' => 'cod',
        'payment_status' => 'pending',
        'order_status' => 'pending',
        'subtotal' => 1000,
        'tax' => 0,
        'total' => 1060,
        'ip_address' => '192.168.10.50',
        'is_fraud' => false,
    ]);
});

test('admin can toggle order fraud status', function () {
    $response = $this->actingAs($this->admin, 'admin')
        ->patch(route('admin.orders.toggle-fraud', $this->order));

    $response->assertRedirect();
    $this->order->refresh();
    expect($this->order->is_fraud)->toBeTrue();
    expect($this->order->isFlaggedAsFraud())->toBeTrue();

    // Toggle off
    $this->actingAs($this->admin, 'admin')
        ->patch(route('admin.orders.toggle-fraud', $this->order));

    $this->order->refresh();
    expect($this->order->is_fraud)->toBeFalse();
});

test('admin can block customer ip from order and flags order as fraud', function () {
    $response = $this->actingAs($this->admin, 'admin')
        ->post(route('admin.orders.block-ip', $this->order));

    $response->assertRedirect();
    $this->order->refresh();
    expect(BlockedIp::isBlocked('192.168.10.50'))->toBeTrue();
    expect($this->order->is_fraud)->toBeTrue();
    expect($this->order->isIpBlocked())->toBeTrue();
});

test('blocked ip cannot place order', function () {
    BlockedIp::create([
        'ip_address' => '10.20.30.40',
        'reason' => 'Fraud Test',
    ]);

    $response = $this->withServerVariables(['REMOTE_ADDR' => '10.20.30.40'])
        ->post(route('order.store'), [
            'customer_name' => 'Blocked Guy',
            'customer_phone' => '01700000000',
            'customer_address' => 'Test Address',
            'shipping_method' => 'inside_dhaka',
            'shipping_cost' => 60,
            'payment_method' => 'cod',
            'subtotal' => 500,
            'tax' => 0,
            'total' => 560,
            'items' => [
                [
                    'product_name' => 'Sample Product',
                    'price' => 500,
                    'quantity' => 1,
                ],
            ],
        ]);

    $response->assertSessionHas('error');
    expect(Order::where('customer_phone', '01700000000')->exists())->toBeFalse();
});

test('admin can unblock customer ip', function () {
    BlockedIp::create([
        'ip_address' => '192.168.10.50',
        'reason' => 'Blocked test',
    ]);

    expect(BlockedIp::isBlocked('192.168.10.50'))->toBeTrue();

    $response = $this->actingAs($this->admin, 'admin')
        ->post(route('admin.orders.unblock-ip', $this->order));

    $response->assertRedirect();
    expect(BlockedIp::isBlocked('192.168.10.50'))->toBeFalse();
});

test('fraud check endpoint returns valid risk statistics', function () {
    $response = $this->actingAs($this->admin, 'admin')
        ->getJson(route('admin.orders.fraud-check', $this->order));

    $response->assertOk()
        ->assertJsonStructure([
            'order_id',
            'invoice_no',
            'customer_name',
            'customer_phone',
            'ip_address',
            'is_fraud',
            'is_ip_blocked',
            'risk_score',
            'risk_level',
            'risk_color',
            'reasons',
            'stats' => [
                'total',
                'delivered',
                'cancelled',
                'returned',
                'fraud',
                'success_rate',
                'ip_orders',
            ],
        ]);
});

test('check-phone endpoint returns phone risk evaluation', function () {
    $response = $this->actingAs($this->admin, 'admin')
        ->getJson(route('admin.orders.check-phone', ['phone' => '01712345678']));

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'phone',
            'clean_phone',
            'risk_score',
            'risk_level',
            'risk_color',
            'reasons',
            'stats' => [
                'total',
                'delivered',
                'cancelled',
                'returned',
                'fraud',
                'success_rate',
            ],
        ]);
});

test('high cancellation phone is automatically detected as fraud across couriers', function () {
    $badPhone = '01799887766';

    // Create 2 cancelled orders in store history
    Order::create([
        'invoice_no' => 'INV-TEST-C1',
        'customer_name' => 'Bad Customer',
        'customer_phone' => $badPhone,
        'customer_address' => 'Dhaka',
        'shipping_method' => 'inside_dhaka',
        'shipping_cost' => 60,
        'payment_method' => 'cod',
        'payment_status' => 'pending',
        'order_status' => 'cancelled',
        'subtotal' => 500,
        'tax' => 0,
        'total' => 560,
    ]);

    Order::create([
        'invoice_no' => 'INV-TEST-C2',
        'customer_name' => 'Bad Customer',
        'customer_phone' => $badPhone,
        'customer_address' => 'Dhaka',
        'shipping_method' => 'inside_dhaka',
        'shipping_cost' => 60,
        'payment_method' => 'cod',
        'payment_status' => 'pending',
        'order_status' => 'cancelled',
        'subtotal' => 500,
        'tax' => 0,
        'total' => 560,
    ]);

    $newOrder = Order::create([
        'invoice_no' => 'INV-TEST-NEW',
        'customer_name' => 'Bad Customer',
        'customer_phone' => $badPhone,
        'customer_address' => 'Dhaka',
        'shipping_method' => 'inside_dhaka',
        'shipping_cost' => 60,
        'payment_method' => 'cod',
        'payment_status' => 'pending',
        'order_status' => 'pending',
        'subtotal' => 800,
        'tax' => 0,
        'total' => 860,
        'ip_address' => '103.20.10.5',
    ]);

    $response = $this->actingAs($this->admin, 'admin')
        ->getJson(route('admin.orders.fraud-check', $newOrder));

    $response->assertOk()
        ->assertJson([
            'is_fraud' => true,
        ]);

    $newOrder->refresh();
    expect($newOrder->is_fraud)->toBeTrue();
    expect($newOrder->fraud_reason)->toContain('Cancellation');
});

test('admin can update order fraud cancellation settings without external apis', function () {
    $response = $this->actingAs($this->admin, 'admin')
        ->post(route('admin.settings.courier.update'), [
            'cancel_threshold' => 3,
            'cancel_rate_threshold' => 50,
            'auto_block_ip' => 1,
        ]);

    $response->assertRedirect();
    $fraudSettings = HomepageSetting::get('courier_fraud_settings');

    expect($fraudSettings['cancel_threshold'])->toBe(3);
    expect((float) $fraudSettings['cancel_rate_threshold'])->toBe(50.0);
    expect($fraudSettings['auto_block_ip'])->toBeTrue();

    // Verify phone check uses store cancellation history
    $checkRes = MultiCourierFraudService::check('01700000000');
    expect($checkRes['is_fraud'])->toBeFalse();
    expect($checkRes['total_cancelled'])->toBe(0);
});

test('customer with past cancellations is auto flagged as fraud when placing order', function () {
    $fraudCustomerPhone = '01811223344';

    // Seed 2 cancelled orders
    Order::create([
        'invoice_no' => 'INV-PREV-C1',
        'customer_name' => 'Repeat Canceller',
        'customer_phone' => $fraudCustomerPhone,
        'customer_address' => 'Chittagong',
        'shipping_method' => 'outside_dhaka',
        'shipping_cost' => 120,
        'payment_method' => 'cod',
        'payment_status' => 'pending',
        'order_status' => 'cancelled',
        'subtotal' => 600,
        'tax' => 0,
        'total' => 720,
    ]);

    Order::create([
        'invoice_no' => 'INV-PREV-C2',
        'customer_name' => 'Repeat Canceller',
        'customer_phone' => $fraudCustomerPhone,
        'customer_address' => 'Chittagong',
        'shipping_method' => 'outside_dhaka',
        'shipping_cost' => 120,
        'payment_method' => 'cod',
        'payment_status' => 'pending',
        'order_status' => 'returned',
        'subtotal' => 600,
        'tax' => 0,
        'total' => 720,
    ]);

    $postData = [
        'customer_name' => 'Repeat Canceller',
        'customer_phone' => $fraudCustomerPhone,
        'customer_address' => 'Test Location',
        'shipping_method' => 'inside_dhaka',
        'shipping_cost' => 60,
        'payment_method' => 'cod',
        'subtotal' => 1000,
        'tax' => 0,
        'total' => 1060,
        'items' => [
            [
                'product_name' => 'Test Item',
                'price' => 1000,
                'quantity' => 1,
            ],
        ],
    ];

    $response = $this->post(route('order.store'), $postData);

    $placedOrder = Order::where('customer_phone', $fraudCustomerPhone)
        ->where('order_status', 'pending')
        ->first();

    expect($placedOrder)->not->toBeNull();
    expect($placedOrder->is_fraud)->toBeTrue();
    expect($placedOrder->fraud_reason)->toContain('Cancellation');
});
