<?php

use App\Models\Admin;
use App\Models\HomepageSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authorized admin can access payment settings page', function () {
    $this->seed();
    $admin = Admin::where('email', 'admin@example.com')->first();

    $response = $this->actingAs($admin, 'admin')->get(route('admin.settings.payment'));

    $response->assertStatus(200)
        ->assertSee('Payment Settings')
        ->assertSee('SSLCommerz Store ID');
});

test('authorized admin can update payment settings', function () {
    $this->seed();
    $admin = Admin::where('email', 'admin@example.com')->first();

    $response = $this->actingAs($admin, 'admin')->post(route('admin.settings.payment.update'), [
        'sandbox' => '0',
        'store_id' => 'my-store-id',
        'store_password' => 'my-store-pass',
        'currency' => 'USD',
    ]);

    $response->assertRedirect();

    $stored = HomepageSetting::get('sslcommerz_settings');

    expect($stored)->not->toBeNull()
        ->and($stored['sandbox'])->toBe('0')
        ->and($stored['store_id'])->toBe('my-store-id')
        ->and($stored['store_password'])->toBe('my-store-pass')
        ->and($stored['currency'])->toBe('USD');
});
