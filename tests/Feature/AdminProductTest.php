<?php

use App\Models\Admin;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can view product list page with buy price', function () {
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
        'price' => 999.00,
        'stock' => 50,
        'sales_count' => 10,
    ]);

    $response = $this->actingAs($admin, 'admin')->get(route('admin.products.index'));

    $response->assertSuccessful()
        ->assertSee('Galaxy S24')
        ->assertSee('$700.00')
        ->assertSee('$999.00');
});

test('admin can create a product with buy price', function () {
    $this->seed();
    $admin = Admin::where('email', 'admin@example.com')->first();

    $category = Category::create(['name' => 'Electronics']);
    $brand = Brand::create(['name' => 'Samsung']);

    $response = $this->actingAs($admin, 'admin')->post(route('admin.products.store'), [
        'name' => 'Galaxy S24 Ultra',
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'buy_price' => 850.00,
        'price' => 1299.00,
        'stock' => 30,
        'sales_count' => 5,
        'discount_type' => '',
        'discount_value' => '0',
        'is_new_arrival' => '1',
    ]);

    $response->assertRedirect(route('admin.products.index'));
    $this->assertDatabaseHas('products', [
        'name' => 'Galaxy S24 Ultra',
        'slug' => 'galaxy-s24-ultra',
        'buy_price' => 850.00,
        'price' => 1299.00,
        'is_new_arrival' => true,
    ]);
});

test('admin can edit and update a product with buy price', function () {
    $this->seed();
    $admin = Admin::where('email', 'admin@example.com')->first();

    $category = Category::create(['name' => 'Electronics']);
    $brand = Brand::create(['name' => 'Samsung']);

    $product = Product::create([
        'name' => 'Galaxy S24 Plus',
        'slug' => 'galaxy-s24-plus',
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'buy_price' => 750.00,
        'price' => 1049.00,
        'stock' => 40,
        'sales_count' => 8,
        'is_new_arrival' => true,
    ]);

    $response = $this->actingAs($admin, 'admin')->put(route('admin.products.update', $product), [
        'name' => 'Galaxy S24 Plus Updated',
        'slug' => 'galaxy-s24-plus-updated',
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'buy_price' => 780.00,
        'price' => 1099.00,
        'stock' => 35,
        'sales_count' => 12,
        'is_new_arrival' => '0',
    ]);

    $response->assertRedirect(route('admin.products.index'));
    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'name' => 'Galaxy S24 Plus Updated',
        'buy_price' => 780.00,
        'price' => 1099.00,
        'is_new_arrival' => false,
    ]);
});

test('admin cannot create a product with negative buy price', function () {
    $this->seed();
    $admin = Admin::where('email', 'admin@example.com')->first();

    $category = Category::create(['name' => 'Electronics']);
    $brand = Brand::create(['name' => 'Samsung']);

    $response = $this->actingAs($admin, 'admin')->post(route('admin.products.store'), [
        'name' => 'Galaxy S24 Ultra',
        'slug' => 'galaxy-s24-ultra',
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'buy_price' => -850.00,
        'price' => 1299.00,
        'stock' => 30,
        'sales_count' => 5,
        'discount_type' => '',
        'discount_value' => '0',
    ]);

    $response->assertSessionHasErrors(['buy_price']);
});

test('admin can bulk delete products', function () {
    $this->seed();
    $admin = Admin::where('email', 'admin@example.com')->first();

    $category = Category::create(['name' => 'Electronics']);
    $brand = Brand::create(['name' => 'Samsung']);

    $product1 = Product::create([
        'name' => 'Product 1',
        'slug' => 'product-1',
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'price' => 100.00,
        'stock' => 10,
    ]);

    $product2 = Product::create([
        'name' => 'Product 2',
        'slug' => 'product-2',
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'price' => 200.00,
        'stock' => 10,
    ]);

    $response = $this->actingAs($admin, 'admin')->post(route('admin.products.bulk-destroy'), [
        'ids' => [$product1->id, $product2->id],
    ]);

    $response->assertRedirect();
    $this->assertDatabaseMissing('products', ['id' => $product1->id]);
    $this->assertDatabaseMissing('products', ['id' => $product2->id]);
});

test('admin can toggle is_new_arrival independently of is_active', function () {
    $this->seed();
    $admin = Admin::where('email', 'admin@example.com')->first();

    $category = Category::create(['name' => 'Electronics']);
    $brand = Brand::create(['name' => 'Samsung']);

    $product = Product::create([
        'name' => 'Galaxy Note 20',
        'slug' => 'galaxy-note-20',
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'price' => 899.00,
        'stock' => 10,
        'is_active' => true,
        'is_new_arrival' => true,
    ]);

    // Toggle is_new_arrival off
    $response = $this->actingAs($admin, 'admin')->patch(route('admin.products.toggle-new-arrival', $product));
    $response->assertSuccessful();

    $product->refresh();
    expect($product->is_new_arrival)->toBeFalse();
    expect($product->is_active)->toBeTrue();

    // Toggle it back on
    $response = $this->actingAs($admin, 'admin')->patch(route('admin.products.toggle-new-arrival', $product));
    $response->assertSuccessful();

    $product->refresh();
    expect($product->is_new_arrival)->toBeTrue();
    expect($product->is_active)->toBeTrue();
});

test('frontend homepage shows only active products with is_new_arrival = true in new arrivals list', function () {
    $category = Category::create(['name' => 'Electronics']);
    $brand = Brand::create(['name' => 'Samsung']);

    // Active + New Arrival (should show)
    $product1 = Product::create([
        'name' => 'Product Active New',
        'slug' => 'product-active-new',
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'price' => 100,
        'stock' => 10,
        'is_active' => true,
        'is_new_arrival' => true,
    ]);

    // Active + NOT New Arrival (should not show)
    $product2 = Product::create([
        'name' => 'Product Active Regular',
        'slug' => 'product-active-regular',
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'price' => 100,
        'stock' => 10,
        'is_active' => true,
        'is_new_arrival' => false,
    ]);

    // Inactive + New Arrival (should not show)
    $product3 = Product::create([
        'name' => 'Product Inactive New',
        'slug' => 'product-inactive-new',
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'price' => 100,
        'stock' => 10,
        'is_active' => false,
        'is_new_arrival' => true,
    ]);

    $response = $this->get(route('home'));
    $response->assertSuccessful();

    $newArrivals = $response->viewData('newArrivalProducts');
    expect($newArrivals->pluck('id'))->toContain($product1->id);
    expect($newArrivals->pluck('id'))->not->toContain($product2->id);
    expect($newArrivals->pluck('id'))->not->toContain($product3->id);
});

test('admin can see dynamic sales count of delivered products on index page', function () {
    $this->seed();
    $admin = Admin::where('email', 'admin@example.com')->first();

    $category = Category::create(['name' => 'Electronics']);
    $brand = Brand::create(['name' => 'Samsung']);

    $product = Product::create([
        'name' => 'Galaxy S25',
        'slug' => 'galaxy-s25',
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'price' => 1000.00,
        'stock' => 50,
    ]);

    // Create a delivered order with 3 units
    $orderDelivered = Order::create([
        'invoice_no' => 'INV-TEST-0001',
        'customer_name' => 'Customer A',
        'customer_phone' => '01700000000',
        'customer_address' => 'Dhaka',
        'shipping_method' => 'inside_dhaka',
        'shipping_cost' => 60,
        'payment_method' => 'cod',
        'order_status' => 'delivered',
        'subtotal' => 3000,
        'total' => 3060,
    ]);

    OrderItem::create([
        'order_id' => $orderDelivered->id,
        'product_id' => $product->id,
        'product_name' => $product->name,
        'price' => 1000,
        'quantity' => 3,
        'line_total' => 3000,
    ]);

    // Create a pending order with 2 units (should not count)
    $orderPending = Order::create([
        'invoice_no' => 'INV-TEST-0002',
        'customer_name' => 'Customer B',
        'customer_phone' => '01700000000',
        'customer_address' => 'Dhaka',
        'shipping_method' => 'inside_dhaka',
        'shipping_cost' => 60,
        'payment_method' => 'cod',
        'order_status' => 'pending',
        'subtotal' => 2000,
        'total' => 2060,
    ]);

    OrderItem::create([
        'order_id' => $orderPending->id,
        'product_id' => $product->id,
        'product_name' => $product->name,
        'price' => 1000,
        'quantity' => 2,
        'line_total' => 2000,
    ]);

    $response = $this->actingAs($admin, 'admin')->get(route('admin.products.index'));
    $response->assertSuccessful();

    // Verify it lists the product with 3 units of sales
    $response->assertSee('Galaxy S25');
    // Ensure the response contains the 3 sales count
    $products = $response->viewData('products');
    $testedProduct = $products->where('id', $product->id)->first();
    expect($testedProduct->delivered_sales_count)->toEqual(3);
});
