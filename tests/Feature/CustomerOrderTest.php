<?php

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated customer can view order history, status, and invoice links', function () {
    // 1. Create a user
    $user = User::factory()->create();

    // 2. Create an order for this user
    $order = Order::create([
        'user_id' => $user->id,
        'invoice_no' => 'INV-20260709-1002',
        'customer_name' => $user->name,
        'customer_phone' => '01700000000',
        'customer_address' => 'Test Address',
        'shipping_method' => 'inside_dhaka',
        'shipping_cost' => 60,
        'payment_method' => 'cod',
        'payment_status' => 'pending',
        'order_status' => 'pending',
        'subtotal' => 500,
        'tax' => 25,
        'total' => 585,
    ]);

    // Create item
    OrderItem::create([
        'order_id' => $order->id,
        'product_name' => 'Blue T-Shirt Premium',
        'price' => 500,
        'quantity' => 1,
        'line_total' => 500,
    ]);

    // 3. Act: Visit customer dashboard
    $dashboardResponse = $this->actingAs($user)->get(route('user.dashboard'));
    $dashboardResponse->assertStatus(200);
    $dashboardResponse->assertSee('<h2>1</h2>', false);
    $dashboardResponse->assertSee('monthlyOrdersChart');

    // 4. Act: Visit orders history index without search
    $indexResponse = $this->actingAs($user)->get(route('user.orders.index'));
    $indexResponse->assertStatus(200);
    $indexResponse->assertSee($order->invoice_no);
    $indexResponse->assertSee('Blue T-Shirt Premium');
    $indexResponse->assertSee('Pending');

    // 5. Act: Visit order details
    $showResponse = $this->actingAs($user)->get(route('user.orders.show', $order));
    $showResponse->assertStatus(200);
    $showResponse->assertSee($order->invoice_no);

    // 6. Act: Visit public invoice page
    $invoiceResponse = $this->get(route('order.invoice', $order->invoice_no));
    $invoiceResponse->assertStatus(200);
    $invoiceResponse->assertSee($order->invoice_no);
});

test('customer can search orders by invoice number and product name', function () {
    $user = User::factory()->create();

    $order1 = Order::create([
        'user_id' => $user->id,
        'invoice_no' => 'INV-MATCH-1111',
        'customer_name' => $user->name,
        'customer_phone' => '01700000000',
        'customer_address' => 'Test Address',
        'shipping_method' => 'inside_dhaka',
        'shipping_cost' => 60,
        'payment_method' => 'cod',
        'payment_status' => 'pending',
        'order_status' => 'pending',
        'subtotal' => 500,
        'tax' => 25,
        'total' => 585,
    ]);

    OrderItem::create([
        'order_id' => $order1->id,
        'product_name' => 'Awesome Wireless Mouse',
        'price' => 500,
        'quantity' => 1,
        'line_total' => 500,
    ]);

    $order2 = Order::create([
        'user_id' => $user->id,
        'invoice_no' => 'INV-OTHER-2222',
        'customer_name' => $user->name,
        'customer_phone' => '01700000000',
        'customer_address' => 'Test Address',
        'shipping_method' => 'inside_dhaka',
        'shipping_cost' => 60,
        'payment_method' => 'cod',
        'payment_status' => 'pending',
        'order_status' => 'pending',
        'subtotal' => 500,
        'tax' => 25,
        'total' => 585,
    ]);

    OrderItem::create([
        'order_id' => $order2->id,
        'product_name' => 'USB Keyboard Wired',
        'price' => 500,
        'quantity' => 1,
        'line_total' => 500,
    ]);

    // Search by invoice
    $responseInvoice = $this->actingAs($user)->get(route('user.orders.index', ['search' => 'MATCH']));
    $responseInvoice->assertStatus(200);
    $responseInvoice->assertSee('INV-MATCH-1111');
    $responseInvoice->assertDontSee('INV-OTHER-2222');

    // Search by product name
    $responseProduct = $this->actingAs($user)->get(route('user.orders.index', ['search' => 'Mouse']));
    $responseProduct->assertStatus(200);
    $responseProduct->assertSee('INV-MATCH-1111');
    $responseProduct->assertSee('Awesome Wireless Mouse');
    $responseProduct->assertDontSee('INV-OTHER-2222');
    $responseProduct->assertDontSee('USB Keyboard Wired');

    // Search for non-matching term
    $responseEmpty = $this->actingAs($user)->get(route('user.orders.index', ['search' => 'NOTHING']));
    $responseEmpty->assertStatus(200);
    $responseEmpty->assertSee('No orders found matching');
    $responseEmpty->assertDontSee('INV-MATCH-1111');
    $responseEmpty->assertDontSee('INV-OTHER-2222');
});

test('customer can change per page items pagination on order history page', function () {
    $user = User::factory()->create();

    // Create 15 orders with distinct timestamps and padded names to prevent substring matches
    for ($i = 1; $i <= 15; $i++) {
        $invoiceNo = 'INV-PAGE-'.str_pad($i, 2, '0', STR_PAD_LEFT);
        $order = new Order([
            'user_id' => $user->id,
            'invoice_no' => $invoiceNo,
            'customer_name' => $user->name,
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
        $order->created_at = now()->subMinutes(20 - $i);
        $order->save();

        OrderItem::create([
            'order_id' => $order->id,
            'product_name' => 'Product Name '.$i,
            'price' => 100,
            'quantity' => 1,
            'line_total' => 100,
        ]);
    }

    // Default per page is 10 (page 1)
    $responseDefault = $this->actingAs($user)->get(route('user.orders.index'));
    $responseDefault->assertStatus(200);
    // Page 1 should contain items 06 to 15 (due to latest order sorting, items 15 to 06)
    $responseDefault->assertSee('INV-PAGE-15');
    // But item 01 should not be seen on page 1 (since it's on page 2)
    $responseDefault->assertDontSee('INV-PAGE-01');

    // Change per page to 20
    $response20 = $this->actingAs($user)->get(route('user.orders.index', ['per_page' => 20]));
    $response20->assertStatus(200);
    // All 15 items should be seen
    $response20->assertSee('INV-PAGE-15');
    $response20->assertSee('INV-PAGE-01');

    // Change per page to all
    $responseAll = $this->actingAs($user)->get(route('user.orders.index', ['per_page' => 'all']));
    $responseAll->assertStatus(200);
    $responseAll->assertSee('INV-PAGE-15');
    $responseAll->assertSee('INV-PAGE-01');
});
