<?php

use App\Models\Admin;
use App\Models\HomepageSetting;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('authorized admin can access company settings page', function () {
    $this->seed();
    $admin = Admin::where('email', 'admin@example.com')->first();

    $response = $this->actingAs($admin, 'admin')->get(route('admin.settings.company'));

    $response->assertStatus(200)
        ->assertSee('Company Settings')
        ->assertSee('Company Name');
});

test('authorized admin can update company settings', function () {
    Storage::fake('public');
    $this->seed();
    $admin = Admin::where('email', 'admin@example.com')->first();

    $logo = UploadedFile::fake()->image('company_logo.png');
    $favicon = UploadedFile::fake()->image('favicon.png');

    $response = $this->actingAs($admin, 'admin')->post(route('admin.settings.company.update'), [
        'name' => 'Tech Corp',
        'site_name' => 'TechShop',
        'logo' => $logo,
        'favicon' => $favicon,
        'address' => 'Mirpur, Dhaka',
        'phone' => '+8801234567890',
    ]);

    $response->assertRedirect(route('admin.settings.company'));

    $stored = HomepageSetting::get('company_settings');

    expect($stored)->not->toBeNull()
        ->and($stored['name'])->toBe('Tech Corp')
        ->and($stored['site_name'])->toBe('TechShop')
        ->and($stored['address'])->toBe('Mirpur, Dhaka')
        ->and($stored['phone'])->toBe('+8801234567890');

    Storage::disk('public')->assertExists($stored['logo']);
    Storage::disk('public')->assertExists($stored['favicon']);
});

test('order invoice page displays dynamic company settings', function () {
    $this->seed();

    HomepageSetting::set('company_settings', [
        'name' => 'Dynamic Shop Ltd.',
        'logo' => 'company/dynamic_logo.png',
        'address' => '123 Tech Lane, Dhaka',
        'phone' => '+8801999999999',
    ]);

    $order = Order::create([
        'invoice_no' => 'INV-DYNAMIC-123',
        'customer_name' => 'Jane Customer',
        'customer_phone' => '01800000000',
        'customer_address' => 'Banani, Dhaka',
        'shipping_method' => 'inside_dhaka',
        'shipping_cost' => 60,
        'payment_method' => 'cod',
        'payment_status' => 'pending',
        'order_status' => 'pending',
        'subtotal' => 100,
        'tax' => 5,
        'total' => 165,
    ]);

    $response = $this->get(route('order.invoice', $order->invoice_no));

    $response->assertStatus(200)
        ->assertSee('Dynamic Shop Ltd.')
        ->assertSee('123 Tech Lane, Dhaka')
        ->assertSee('Phone: +8801999999999')
        ->assertSee('company/dynamic_logo.png');
});
