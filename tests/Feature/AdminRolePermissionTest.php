<?php

use App\Models\Admin;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

test('unauthorized admin user without view-products permission cannot list products', function () {
    $this->seed();

    $admin = Admin::create([
        'name' => 'Test Admin',
        'email' => 'testadmin@example.com',
        'password' => bcrypt('password'),
    ]);

    $response = $this->actingAs($admin, 'admin')->get(route('admin.products.index'));

    $response->assertStatus(403);
});

test('authorized admin user with view-products permission can list products but cannot create products without create-products permission', function () {
    $this->seed();

    $role = Role::create(['name' => 'Product Viewer', 'guard_name' => 'admin']);
    $role->givePermissionTo('view-products');

    $admin = Admin::create([
        'name' => 'Test Admin',
        'email' => 'testadmin@example.com',
        'password' => bcrypt('password'),
    ]);
    $admin->assignRole($role);

    $response = $this->actingAs($admin, 'admin')->get(route('admin.products.index'));
    $response->assertStatus(200);

    $response = $this->actingAs($admin, 'admin')->get(route('admin.products.create'));
    $response->assertStatus(403);
});

test('authorized admin user with create-products permission can create products', function () {
    $this->seed();

    $role = Role::create(['name' => 'Product Manager', 'guard_name' => 'admin']);
    $role->givePermissionTo(['view-products', 'create-products']);

    $admin = Admin::create([
        'name' => 'Test Admin',
        'email' => 'testadmin@example.com',
        'password' => bcrypt('password'),
    ]);
    $admin->assignRole($role);

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
});

test('unauthorized admin user without view-staffs permission cannot list staff accounts', function () {
    $this->seed();

    $admin = Admin::create([
        'name' => 'Test Admin',
        'email' => 'testadmin@example.com',
        'password' => bcrypt('password'),
    ]);

    $response = $this->actingAs($admin, 'admin')->get(route('admin.users.admins'));

    $response->assertStatus(403);
});

test('authorized admin user with view-staffs permission can list staff accounts but cannot create them without create-staffs permission', function () {
    $this->seed();

    $role = Role::create(['name' => 'Staff Viewer', 'guard_name' => 'admin']);
    $role->givePermissionTo('view-staffs');

    $admin = Admin::create([
        'name' => 'Test Admin',
        'email' => 'testadmin@example.com',
        'password' => bcrypt('password'),
    ]);
    $admin->assignRole($role);

    $response = $this->actingAs($admin, 'admin')->get(route('admin.users.admins'));
    $response->assertStatus(200);

    $response = $this->actingAs($admin, 'admin')->get(route('admin.users.admins.create'));
    $response->assertStatus(403);
});
