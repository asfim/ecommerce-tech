<?php

use App\Models\Admin;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

    // Update order status to confirmed should fail validation
    $patchResponse1 = $this->actingAs($admin, 'admin')->patch(route('admin.orders.update-status', $order), [
        'order_status' => 'confirmed',
    ]);

    $patchResponse1->assertSessionHasErrors(['order_status']);
    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'order_status' => 'pending',
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
