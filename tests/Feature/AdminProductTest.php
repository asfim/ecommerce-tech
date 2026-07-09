<?php

use App\Models\Admin;
use App\Models\Brand;
use App\Models\Category;
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
    ]);

    $response->assertRedirect(route('admin.products.index'));
    $this->assertDatabaseHas('products', [
        'name' => 'Galaxy S24 Ultra',
        'slug' => 'galaxy-s24-ultra',
        'buy_price' => 850.00,
        'price' => 1299.00,
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
    ]);

    $response->assertRedirect(route('admin.products.index'));
    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'name' => 'Galaxy S24 Plus Updated',
        'buy_price' => 780.00,
        'price' => 1099.00,
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
