<?php

use App\Models\Admin;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('unauthorized users cannot access reports', function () {
    $response = $this->get(route('admin.reports.sales'));
    $response->assertRedirect(route('admin.login'));

    $response = $this->get(route('admin.reports.stock'));
    $response->assertRedirect(route('admin.login'));
});

test('admin can access sales report and view calculations', function () {
    $this->seed();
    $admin = Admin::where('email', 'admin@example.com')->first();

    $category = Category::create(['name' => 'Electronics']);
    $brand = Brand::create(['name' => 'Samsung']);

    $product = Product::create([
        'name' => 'Galaxy S24',
        'slug' => 'galaxy-s24',
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'buy_price' => 700.00,
        'price' => 1000.00,
        'stock' => 50,
    ]);

    $order = Order::create([
        'invoice_no' => 'INV-2026-0001',
        'customer_name' => 'John Doe',
        'customer_phone' => '1234567890',
        'customer_address' => 'Test Address',
        'shipping_method' => 'inside_dhaka',
        'shipping_cost' => 60.00,
        'payment_method' => 'cod',
        'payment_status' => 'paid',
        'order_status' => 'delivered',
        'subtotal' => 1000.00,
        'tax' => 50.00,
        'total' => 1110.00,
        'discount_amount' => 100.00,
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'product_name' => $product->name,
        'price' => 1000.00,
        'quantity' => 1,
        'line_total' => 1000.00,
    ]);

    $response = $this->actingAs($admin, 'admin')->get(route('admin.reports.sales'));

    $response->assertSuccessful();

    // Total Revenue = 1,000.00
    $response->assertSee('$1,000.00');
    // Total Cost = 700.00
    $response->assertSee('$700.00');
    // Net Profit = (Revenue 1000 - Cost 700) - Discount 100 = 200
    $response->assertSee('$200.00');
});

test('admin can access stock report and view statistics', function () {
    $this->seed();
    $admin = Admin::where('email', 'admin@example.com')->first();

    $category = Category::create(['name' => 'Electronics']);
    $brand = Brand::create(['name' => 'Samsung']);

    $product = Product::create([
        'name' => 'Galaxy S24',
        'slug' => 'galaxy-s24',
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'buy_price' => 700.00,
        'price' => 1000.00,
        'stock' => 10,
    ]);

    $response = $this->actingAs($admin, 'admin')->get(route('admin.reports.stock'));

    $response->assertSuccessful();
    // Unique products count
    $response->assertSee('1');
    // Total stock qty
    $response->assertSee('10');
    // Stock value (cost) = 700 * 10 = 7000
    $response->assertSee('$7,000.00');
    // Stock value (retail) = 1000 * 10 = 10000
    $response->assertSee('$10,000.00');
    // Potential profit = 10000 - 7000 = 3000
    $response->assertSee('$3,000.00');
});
