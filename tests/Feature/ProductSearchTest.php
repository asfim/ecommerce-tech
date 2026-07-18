<?php

use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->category1 = Category::create(['name' => 'Electronics', 'is_active' => true]);
    $this->category2 = Category::create(['name' => 'Home Appliances', 'is_active' => true]);

    $this->brand1 = Brand::create(['name' => 'Samsung', 'is_active' => true]);
    $this->brand2 = Brand::create(['name' => 'Apple', 'is_active' => true]);

    $this->product1 = Product::create([
        'name' => 'Galaxy S21',
        'category_id' => $this->category1->id,
        'brand_id' => $this->brand1->id,
        'price' => 800,
        'stock' => 10,
        'is_active' => true,
    ]);

    $this->product2 = Product::create([
        'name' => 'iPhone 13',
        'category_id' => $this->category1->id,
        'brand_id' => $this->brand2->id,
        'price' => 1000,
        'stock' => 5,
        'is_active' => true,
    ]);
});

test('admin can search products by name, category, or brand', function () {
    // Create admin user and authenticate
    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
    ]);

    // Assign view-products permission or authenticate as admin guard
    // In web.php, the route has permission:view-products|create-products|...
    // Let's bypass or actAs admin
    $this->actingAs($admin, 'admin');

    // Search by product name "Galaxy"
    $response = $this->get(route('admin.products.index', ['search' => 'Galaxy']));
    $response->assertSuccessful();
    $response->assertSee('Galaxy S21');
    $response->assertDontSee('iPhone 13');

    // Search by brand name "Apple"
    $response = $this->get(route('admin.products.index', ['search' => 'Apple']));
    $response->assertSuccessful();
    $response->assertSee('iPhone 13');
    $response->assertDontSee('Galaxy S21');
});
