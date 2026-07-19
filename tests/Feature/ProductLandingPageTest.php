<?php

use App\Models\Admin;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductLandingPage;
use App\Models\Order;
use Database\Seeders\RolePermissionSeeder;

beforeEach(function () {
    $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);
    $this->seed(RolePermissionSeeder::class);

    $this->category = Category::create([
        'name' => 'Clothing',
        'is_active' => true,
    ]);

    $this->brand = Brand::create([
        'name' => 'Premium Brand',
        'is_active' => true,
    ]);

    $this->product = Product::create([
        'name' => 'Premium Tee Pack of 5',
        'category_id' => $this->category->id,
        'brand_id' => $this->brand->id,
        'price' => 2999.00,
        'stock' => 10,
        'is_active' => true,
    ]);

    $this->admin = Admin::create([
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
    ]);
    $this->admin->assignRole('Super Admin');
});

test('unauthenticated guest is redirected to login from admin routes', function () {
    $response = $this->get(route('admin.products.landing-page.create', $this->product));
    $response->assertRedirect(route('admin.login'));
});

test('admin can view create landing page form', function () {
    $response = $this->actingAs($this->admin, 'admin')
        ->get(route('admin.products.landing-page.create', $this->product));

    $response->assertStatus(200);
    $response->assertSee($this->product->name);
    $response->assertSee('General Configuration');
});

test('admin can store landing page configuration', function () {
    $payload = [
        'meta_title' => 'Custom Meta Title',
        'tagline' => 'Custom Tagline',
        'heading' => 'Custom Heading 5 shirts',
        'description' => 'Custom Description text',
        'delivery_text' => 'Custom Delivery Note',
        'return_text' => 'Custom Return Note',
        'offer_text' => 'Custom Combo Offer',
        'old_price' => 4995.00,
        'new_price' => 2999.00,
        'discount_text' => 'Save 1996',
        'stock_text' => 'Only 25 left',
        'whatsapp_number' => '8801966789123',
        'whatsapp_text' => 'Hi, I want to buy',
        'is_active' => '1',
        'features' => [
            ['icon' => 'fas fa-tshirt', 'title' => 'F1', 'description' => 'D1'],
            ['icon' => 'fas fa-tshirt', 'title' => 'F2', 'description' => 'D2'],
            ['icon' => 'fas fa-tshirt', 'title' => 'F3', 'description' => 'D3'],
            ['icon' => 'fas fa-tshirt', 'title' => 'F4', 'description' => 'D4'],
            ['icon' => 'fas fa-tshirt', 'title' => 'F5', 'description' => 'D5'],
            ['icon' => 'fas fa-tshirt', 'title' => 'F6', 'description' => 'D6'],
        ],
        'testimonials' => [
            ['rating' => '5', 'text' => 'T1 text', 'author' => 'Author 1'],
            ['rating' => '5', 'text' => 'T2 text', 'author' => 'Author 2'],
            ['rating' => '4.5', 'text' => 'T3 text', 'author' => 'Author 3'],
        ],
    ];

    $response = $this->actingAs($this->admin, 'admin')
        ->post(route('admin.products.landing-page.store', $this->product), $payload);

    $response->assertRedirect(route('admin.products.index'));
    $this->assertDatabaseHas('product_landing_pages', [
        'product_id' => $this->product->id,
        'meta_title' => 'Custom Meta Title',
        'tagline' => 'Custom Tagline',
        'delivery_text' => 'Custom Delivery Note',
        'return_text' => 'Custom Return Note',
    ]);
});

test('admin can view edit landing page form and update it', function () {
    // First create a landing page
    $landingPage = ProductLandingPage::create([
        'product_id' => $this->product->id,
        'meta_title' => 'Original Title',
        'tagline' => 'Original Tagline',
        'heading' => 'Original Heading',
        'is_active' => true,
    ]);

    $response = $this->actingAs($this->admin, 'admin')
        ->get(route('admin.products.landing-page.edit', $this->product));

    $response->assertStatus(200);
    $response->assertSee('Original Title');

    // Update
    $payload = [
        'meta_title' => 'Updated Title',
        'tagline' => 'Updated Tagline',
        'heading' => 'Updated Heading',
        'delivery_text' => 'Updated Delivery Note',
        'return_text' => 'Updated Return Note',
        'is_active' => '1',
    ];

    $updateResponse = $this->actingAs($this->admin, 'admin')
        ->put(route('admin.products.landing-page.update', $this->product), $payload);

    $updateResponse->assertRedirect(route('admin.products.index'));
    $this->assertDatabaseHas('product_landing_pages', [
        'product_id' => $this->product->id,
        'meta_title' => 'Updated Title',
        'tagline' => 'Updated Tagline',
        'delivery_text' => 'Updated Delivery Note',
        'return_text' => 'Updated Return Note',
    ]);
});

test('public guest can view active landing page', function () {
    ProductLandingPage::create([
        'product_id' => $this->product->id,
        'meta_title' => 'Public Meta Title',
        'tagline' => 'Public Tagline',
        'heading' => 'Public Heading',
        'delivery_text' => 'Public Delivery Text',
        'return_text' => 'Public Return Text',
        'is_active' => true,
        'features' => [
            ['icon' => 'fas fa-tshirt', 'title' => 'F1', 'description' => 'D1'],
        ],
        'testimonials' => [
            ['rating' => '5', 'text' => 'T1 text', 'author' => 'Author 1'],
        ],
    ]);

    $response = $this->get(route('landing.show', $this->product->slug));

    $response->assertStatus(200);
    $response->assertSee('Public Meta Title');
    $response->assertSee('Public Tagline');
    $response->assertSee('Public Heading');
    $response->assertSee('Public Delivery Text');
    $response->assertSee('Public Return Text');
});

test('public landing page returns 404 if page is inactive or does not exist', function () {
    // 1. Does not exist
    $response = $this->get(route('landing.show', $this->product->slug));
    $response->assertStatus(404);

    // 2. Inactive
    ProductLandingPage::create([
        'product_id' => $this->product->id,
        'meta_title' => 'Public Meta Title',
        'is_active' => false,
    ]);

    $responseInactive = $this->get(route('landing.show', $this->product->slug));
    $responseInactive->assertStatus(404);
});

test('guest can place order successfully from landing page', function () {
    ProductLandingPage::create([
        'product_id' => $this->product->id,
        'is_active' => true,
    ]);

    $orderPayload = [
        'customer_name' => 'Customer Name',
        'customer_phone' => '01700000000',
        'customer_address' => 'Mirpur, Dhaka',
        'shipping_method' => 'inside_dhaka',
        'shipping_cost' => 0,
        'payment_method' => 'cod',
        'subtotal' => 2999.00,
        'tax' => 0,
        'total' => 2999.00,
        'items' => [
            [
                'product_id' => $this->product->id,
                'product_name' => $this->product->name,
                'product_image' => $this->product->image,
                'price' => 2999.00,
                'quantity' => 1,
                'variants' => ['size' => 'M'],
            ]
        ],
    ];

    $response = $this->postJson(route('order.store'), $orderPayload);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
    ]);

    $this->assertDatabaseHas('orders', [
        'customer_name' => 'Customer Name',
        'customer_phone' => '01700000000',
        'total' => 2999.00,
    ]);

    // Check stock level decreased (confirmed status decreases stock. Oh wait, order starts as pending)
    // Wait, in OrderStockTest we saw pending status does not change stock, but confirmed does.
    // Let's verify pending order has correct values in DB.
    $this->assertDatabaseHas('order_items', [
        'product_id' => $this->product->id,
        'price' => 2999.00,
        'quantity' => 1,
    ]);
});

