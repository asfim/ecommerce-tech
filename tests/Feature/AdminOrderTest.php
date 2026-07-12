<?php

use App\Models\Admin;
use App\Models\HomepageSetting;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use SabitAhmad\SteadFast\DTO\OrderResponse;
use SabitAhmad\SteadFast\Facades\SteadFast;

uses(RefreshDatabase::class);

test('admin can update order status on the order index page and delivery sets payment to paid', function () {
    // Seed database to create Super Admin role and admin user
    $this->seed();

    $admin = Admin::where('email', 'admin@example.com')->first();

    // Create a dummy order
    $order = Order::create([
        'invoice_no' => 'INV-20260709-0001',
        'customer_name' => 'John Doe',
        'customer_phone' => '01712345678',
        'customer_address' => 'Dhaka',
        'shipping_method' => 'inside_dhaka',
        'shipping_cost' => 60,
        'payment_method' => 'cod',
        'payment_status' => 'pending',
        'order_status' => 'pending',
        'subtotal' => 1000,
        'tax' => 50,
        'total' => 1110,
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'product_name' => 'Product Name A',
        'price' => 1000,
        'quantity' => 1,
        'line_total' => 1000,
    ]);

    // View orders index page
    $response = $this->actingAs($admin, 'admin')->get(route('admin.orders.index'));

    $response->assertSuccessful();
    // Confirm the inline status update forms are rendered, but payment status dropdown is not
    $response->assertSee('action="'.route('admin.orders.update-status', $order).'"', false);
    $response->assertSee('order_status');
    $response->assertDontSee('name="payment_status"', false);

    // Update order status to confirmed should succeed
    $patchResponse1 = $this->actingAs($admin, 'admin')->patch(route('admin.orders.update-status', $order), [
        'order_status' => 'confirmed',
    ]);

    $patchResponse1->assertRedirect();
    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'order_status' => 'confirmed',
    ]);

    // Update order status to delivered should succeed and set payment status to paid automatically
    $patchResponse2 = $this->actingAs($admin, 'admin')->patch(route('admin.orders.update-status', $order), [
        'order_status' => 'delivered',
    ]);

    $patchResponse2->assertRedirect();
    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'order_status' => 'delivered',
        'payment_status' => 'paid',
    ]);
});

test('admin cannot update status of an already delivered order', function () {
    $this->seed();
    $admin = Admin::where('email', 'admin@example.com')->first();

    $order = Order::create([
        'invoice_no' => 'INV-20260709-9999',
        'customer_name' => 'John Doe',
        'customer_phone' => '01712345678',
        'customer_address' => 'Dhaka',
        'shipping_method' => 'inside_dhaka',
        'shipping_cost' => 60,
        'payment_method' => 'cod',
        'payment_status' => 'paid',
        'order_status' => 'delivered',
        'subtotal' => 1000,
        'tax' => 50,
        'total' => 1110,
    ]);

    $response = $this->actingAs($admin, 'admin')->patch(route('admin.orders.update-status', $order), [
        'order_status' => 'pending',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('error', 'Cannot update status of a delivered order.');
    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'order_status' => 'delivered',
    ]);
});

test('admin can delete an order', function () {
    $this->seed();
    $admin = Admin::where('email', 'admin@example.com')->first();

    $order = Order::create([
        'invoice_no' => 'INV-20260709-0002',
        'customer_name' => 'John Doe',
        'customer_phone' => '01712345678',
        'customer_address' => 'Dhaka',
        'shipping_method' => 'inside_dhaka',
        'shipping_cost' => 60,
        'payment_method' => 'cod',
        'payment_status' => 'pending',
        'order_status' => 'pending',
        'subtotal' => 1000,
        'tax' => 50,
        'total' => 1110,
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'product_name' => 'Product Name A',
        'price' => 1000,
        'quantity' => 1,
        'line_total' => 1000,
    ]);

    $response = $this->actingAs($admin, 'admin')->delete(route('admin.orders.destroy', $order));

    $response->assertRedirect(route('admin.orders.index'));
    $this->assertDatabaseMissing('orders', [
        'id' => $order->id,
    ]);
    $this->assertDatabaseMissing('order_items', [
        'order_id' => $order->id,
    ]);
});

test('admin can change per page items pagination on admin order index page', function () {
    $this->seed();
    $admin = Admin::where('email', 'admin@example.com')->first();

    // Create 20 orders manually to bypass mass-assignment
    for ($i = 1; $i <= 20; $i++) {
        $order = new Order([
            'invoice_no' => 'INV-ADMIN-'.str_pad($i, 2, '0', STR_PAD_LEFT),
            'customer_name' => 'Customer '.$i,
            'customer_phone' => '01700000000',
            'customer_address' => 'Test Address',
            'shipping_method' => 'inside_dhaka',
            'shipping_cost' => 60,
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'order_status' => 'pending',
            'subtotal' => 100,
            'tax' => 5,
            'total' => 105,
        ]);
        $order->created_at = now()->subMinutes(25 - $i);
        $order->save();

        OrderItem::create([
            'order_id' => $order->id,
            'product_name' => 'Order Item Product '.str_pad($i, 2, '0', STR_PAD_LEFT),
            'price' => 100,
            'quantity' => 1,
            'line_total' => 100,
        ]);
    }

    // Default per page is 15
    $responseDefault = $this->actingAs($admin, 'admin')->get(route('admin.orders.index'));
    $responseDefault->assertStatus(200);
    // Page 1 should contain latest 15 orders (Order Item Product 20 down to Order Item Product 06)
    $responseDefault->assertSee('Order Item Product 20');
    // But Order Item Product 01 should not be seen on page 1
    $responseDefault->assertDontSee('Order Item Product 01');

    // Change per page to 30
    $response30 = $this->actingAs($admin, 'admin')->get(route('admin.orders.index', ['per_page' => 30]));
    $response30->assertStatus(200);
    $response30->assertSee('Order Item Product 20');
    $response30->assertSee('Order Item Product 01');

    // Change per page to all
    $responseAll = $this->actingAs($admin, 'admin')->get(route('admin.orders.index', ['per_page' => 'all']));
    $responseAll->assertStatus(200);
    $responseAll->assertSee('Order Item Product 20');
    $responseAll->assertSee('Order Item Product 01');
});

test('admin can view bulk print invoice page with multiple order details', function () {
    $this->seed();
    $admin = Admin::where('email', 'admin@example.com')->first();

    // Create two dummy orders
    $order1 = Order::create([
        'invoice_no' => 'INV-BULK-0001',
        'customer_name' => 'John Doe',
        'customer_phone' => '01712345678',
        'customer_address' => 'Dhaka',
        'shipping_method' => 'inside_dhaka',
        'shipping_cost' => 60,
        'payment_method' => 'cod',
        'payment_status' => 'pending',
        'order_status' => 'pending',
        'subtotal' => 1000,
        'tax' => 0,
        'total' => 1060,
    ]);

    OrderItem::create([
        'order_id' => $order1->id,
        'product_name' => 'Bulk Product A',
        'price' => 1000,
        'quantity' => 1,
        'line_total' => 1000,
    ]);

    $order2 = Order::create([
        'invoice_no' => 'INV-BULK-0002',
        'customer_name' => 'Jane Smith',
        'customer_phone' => '01787654321',
        'customer_address' => 'Chittagong',
        'shipping_method' => 'outside_dhaka',
        'shipping_cost' => 120,
        'payment_method' => 'cod',
        'payment_status' => 'pending',
        'order_status' => 'pending',
        'subtotal' => 500,
        'tax' => 0,
        'total' => 620,
    ]);

    OrderItem::create([
        'order_id' => $order2->id,
        'product_name' => 'Bulk Product B',
        'price' => 500,
        'quantity' => 1,
        'line_total' => 500,
    ]);

    // Request the bulk print page with the order IDs
    $response = $this->actingAs($admin, 'admin')->get(route('admin.orders.bulk-print', [
        'ids' => implode(',', [$order1->id, $order2->id]),
    ]));

    $response->assertSuccessful();
    $response->assertSee('INV-BULK-0001')
        ->assertSee('INV-BULK-0002')
        ->assertSee('Bulk Product A')
        ->assertSee('Bulk Product B')
        ->assertSee('John Doe')
        ->assertSee('Jane Smith');
});

test('admin can view courier settings page', function () {
    $this->seed();
    $admin = Admin::where('email', 'admin@example.com')->first();

    $response = $this->actingAs($admin, 'admin')->get(route('admin.settings.courier'));
    $response->assertSuccessful();
    $response->assertSee('Courier Settings');
    $response->assertSee('Steadfast API Key');
});

test('admin can update courier settings', function () {
    $this->seed();
    $admin = Admin::where('email', 'admin@example.com')->first();

    $response = $this->actingAs($admin, 'admin')->post(route('admin.settings.courier.update'), [
        'api_key' => 'new-api-key',
        'secret_key' => 'new-secret-key',
        'base_url' => 'https://portal.packnplay.com/api/v1',
    ]);

    $response->assertRedirect();
    $settings = HomepageSetting::get('steadfast_settings');
    expect($settings['api_key'])->toBe('new-api-key');
    expect($settings['secret_key'])->toBe('new-secret-key');
    expect($settings['base_url'])->toBe('https://portal.packnplay.com/api/v1');
});

test('admin can send orders to Steadfast Courier in bulk', function () {
    $this->seed();
    $admin = Admin::where('email', 'admin@example.com')->first();

    // Set configuration for the test
    config([
        'steadfast.api_key' => 'test-api-key',
        'steadfast.secret_key' => 'test-secret-key',
        'steadfast.base_url' => 'https://portal.packnplay.com/api/v1',
    ]);

    $order1 = Order::create([
        'invoice_no' => 'INV-BULK-0001',
        'customer_name' => 'John Doe',
        'customer_phone' => '01712345678',
        'customer_address' => 'Dhaka',
        'shipping_method' => 'inside_dhaka',
        'shipping_cost' => 60,
        'payment_method' => 'cod',
        'payment_status' => 'pending',
        'order_status' => 'pending',
        'subtotal' => 1000,
        'tax' => 0,
        'total' => 1060,
    ]);

    OrderItem::create([
        'order_id' => $order1->id,
        'product_name' => 'Bulk Product A',
        'price' => 1000,
        'quantity' => 1,
        'line_total' => 1000,
    ]);

    // Mock Steadfast Facade
    SteadFast::shouldReceive('createOrder')
        ->once()
        ->andReturn(new OrderResponse(200, 'Success', [
            'consignment_id' => 12345,
            'tracking_code' => 'TRACK-12345',
            'invoice' => 'INV-BULK-0001',
            'status' => 'pending',
            'cod_amount' => 1060.00,
        ]));

    $response = $this->actingAs($admin, 'admin')->postJson(route('admin.orders.send-steadfast'), [
        'ids' => [$order1->id],
    ]);

    $response->assertSuccessful();
    $response->assertJson([
        'success' => true,
        'message' => 'Successfully sent 1 order(s) to Steadfast Courier!',
    ]);

    $this->assertDatabaseHas('orders', [
        'id' => $order1->id,
        'order_status' => 'delivered',
    ]);
});

test('admin can view SMS settings page', function () {
    $this->seed();
    $admin = Admin::where('email', 'admin@example.com')->first();

    $response = $this->actingAs($admin, 'admin')->get(route('admin.settings.sms'));

    $response->assertSuccessful();
    $response->assertSee('SMS Gateway Integration');
    $response->assertSee('SMS Gateway Provider');
});

test('admin can update SMS settings', function () {
    $this->seed();
    $admin = Admin::where('email', 'admin@example.com')->first();

    $response = $this->actingAs($admin, 'admin')->post(route('admin.settings.sms.update'), [
        'enabled' => '1',
        'gateway' => 'bulksmsbd',
        'api_key' => 'test-api-key',
        'sender_id' => 'test-sender-id',
        'message_template' => 'Hello {customer_name}, order {invoice_no} is delivered.',
    ]);

    $response->assertRedirect();
    $settings = HomepageSetting::get('sms_settings');
    expect($settings['enabled'])->toBe(true);
    expect($settings['gateway'])->toBe('bulksmsbd');
    expect($settings['api_key'])->toBe('test-api-key');
    expect($settings['sender_id'])->toBe('test-sender-id');
    expect($settings['message_template'])->toBe('Hello {customer_name}, order {invoice_no} is delivered.');
});

test('SMS is sent automatically when order status is updated to delivered', function () {
    $this->seed();
    $admin = Admin::where('email', 'admin@example.com')->first();

    // Enable SMS settings
    HomepageSetting::set('sms_settings', [
        'enabled' => true,
        'gateway' => 'bulksmsbd',
        'api_key' => 'test-api-key',
        'sender_id' => 'test-sender-id',
        'message_template' => 'Hello {customer_name}, order {invoice_no} is delivered.',
    ]);

    $order = Order::create([
        'invoice_no' => 'INV-SMS-0001',
        'customer_name' => 'Jane Customer',
        'customer_phone' => '01712345678',
        'customer_address' => 'Dhaka',
        'shipping_method' => 'inside_dhaka',
        'shipping_cost' => 60,
        'payment_method' => 'cod',
        'payment_status' => 'pending',
        'order_status' => 'pending',
        'subtotal' => 1000,
        'tax' => 50,
        'total' => 1110,
    ]);

    Http::fake([
        'bulksmsbd.net/*' => Http::response([
            'response_code' => 1000,
            'success' => true,
        ], 200),
    ]);

    // Update order status to delivered
    $response = $this->actingAs($admin, 'admin')->patch(route('admin.orders.update-status', $order), [
        'order_status' => 'delivered',
    ]);

    $response->assertRedirect();

    // Assert that SMS was sent
    Http::assertSent(function (Request $request) {
        return str_contains($request->url(), 'bulksmsbd.net/api/smsapi') &&
            $request['number'] === '01712345678' &&
            $request['message'] === 'Hello Jane Customer, order INV-SMS-0001 is delivered.';
    });
});

test('SMS is not sent when SMS notifications are disabled', function () {
    $this->seed();
    $admin = Admin::where('email', 'admin@example.com')->first();

    // Disable SMS settings
    HomepageSetting::set('sms_settings', [
        'enabled' => false,
        'gateway' => 'bulksmsbd',
        'api_key' => 'test-api-key',
        'sender_id' => 'test-sender-id',
        'message_template' => 'Hello {customer_name}, order {invoice_no} is delivered.',
    ]);

    $order = Order::create([
        'invoice_no' => 'INV-SMS-0002',
        'customer_name' => 'Jane Customer',
        'customer_phone' => '01712345678',
        'customer_address' => 'Dhaka',
        'shipping_method' => 'inside_dhaka',
        'shipping_cost' => 60,
        'payment_method' => 'cod',
        'payment_status' => 'pending',
        'order_status' => 'pending',
        'subtotal' => 1000,
        'tax' => 50,
        'total' => 1110,
    ]);

    Http::fake();

    // Update order status to delivered
    $response = $this->actingAs($admin, 'admin')->patch(route('admin.orders.update-status', $order), [
        'order_status' => 'delivered',
    ]);

    $response->assertRedirect();

    // Assert that no SMS requests were sent
    Http::assertNothingSent();
});
