<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

beforeEach(function () {
    $this->category = Category::create([
        'name' => 'Electronics',
        'is_active' => true,
    ]);

    $this->brand = Brand::create([
        'name' => 'Samsung',
        'is_active' => true,
    ]);

    $this->product = Product::create([
        'name' => 'Galaxy S21',
        'category_id' => $this->category->id,
        'brand_id' => $this->brand->id,
        'price' => 800,
        'stock' => 10,
        'is_active' => true,
    ]);
});

test('stock does not change when order is created as pending', function () {
    $order = Order::create([
        'invoice_no' => 'INV-TEST-0001',
        'customer_name' => 'John Doe',
        'customer_phone' => '01712345678',
        'customer_address' => 'Dhaka, Bangladesh',
        'shipping_method' => 'inside_dhaka',
        'shipping_cost' => 60,
        'payment_method' => 'cod',
        'payment_status' => 'pending',
        'order_status' => 'pending',
        'subtotal' => 800,
        'tax' => 40,
        'total' => 900,
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $this->product->id,
        'product_name' => $this->product->name,
        'price' => 800,
        'quantity' => 2,
        'line_total' => 1600,
    ]);

    $this->product->refresh();
    expect($this->product->stock)->toBe(10);
});

test('stock transitions correctly through different order status updates', function () {
    $order = Order::create([
        'invoice_no' => 'INV-TEST-0003',
        'customer_name' => 'John Doe',
        'customer_phone' => '01712345678',
        'customer_address' => 'Dhaka, Bangladesh',
        'shipping_method' => 'inside_dhaka',
        'shipping_cost' => 60,
        'payment_method' => 'cod',
        'payment_status' => 'pending',
        'order_status' => 'pending',
        'subtotal' => 800,
        'tax' => 40,
        'total' => 900,
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $this->product->id,
        'product_name' => $this->product->name,
        'price' => 800,
        'quantity' => 2,
        'line_total' => 1600,
    ]);

    // 1. pending -> confirmed (decrease stock: 10 -> 8)
    $order->update(['order_status' => 'confirmed']);
    $this->product->refresh();
    expect($this->product->stock)->toBe(8);

    // 2. confirmed -> delivered (no change: remains 8)
    $order->update(['order_status' => 'delivered']);
    $this->product->refresh();
    expect($this->product->stock)->toBe(8);

    // 3. delivered -> returned (increase stock: 8 -> 10)
    $order->update(['order_status' => 'returned']);
    $this->product->refresh();
    expect($this->product->stock)->toBe(10);

    // 4. returned -> confirmed (decrease stock: 10 -> 8)
    $order->update(['order_status' => 'confirmed']);
    $this->product->refresh();
    expect($this->product->stock)->toBe(8);

    // 5. confirmed -> cancelled (increase stock: 8 -> 10)
    $order->update(['order_status' => 'cancelled']);
    $this->product->refresh();
    expect($this->product->stock)->toBe(10);
});

test('stock is restored when a confirmed or delivered order is deleted', function () {
    $order = Order::create([
        'invoice_no' => 'INV-TEST-0004',
        'customer_name' => 'John Doe',
        'customer_phone' => '01712345678',
        'customer_address' => 'Dhaka, Bangladesh',
        'shipping_method' => 'inside_dhaka',
        'shipping_cost' => 60,
        'payment_method' => 'cod',
        'payment_status' => 'pending',
        'order_status' => 'pending',
        'subtotal' => 800,
        'tax' => 40,
        'total' => 900,
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $this->product->id,
        'product_name' => $this->product->name,
        'price' => 800,
        'quantity' => 2,
        'line_total' => 1600,
    ]);

    // Change status to confirmed (stock decreases: 10 -> 8)
    $order->update(['order_status' => 'confirmed']);
    $this->product->refresh();
    expect($this->product->stock)->toBe(8);

    // Delete order (stock increases: 8 -> 10)
    $order->delete();
    $this->product->refresh();
    expect($this->product->stock)->toBe(10);
});
