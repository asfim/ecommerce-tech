<?php

use App\Models\Admin;
use App\Models\Coupon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can view coupon list page', function () {
    $this->seed();
    $admin = Admin::where('email', 'admin@example.com')->first();

    // Default seeded coupons (SAVE10, FLAT50)
    $response = $this->actingAs($admin, 'admin')->get(route('admin.coupons.index'));

    $response->assertStatus(200)
        ->assertSee('SAVE10')
        ->assertSee('FLAT50');
});

test('admin can create a coupon', function () {
    $this->seed();
    $admin = Admin::where('email', 'admin@example.com')->first();

    $response = $this->actingAs($admin, 'admin')->post(route('admin.coupons.store'), [
        'code' => 'NEWCOUPON',
        'type' => 'percent',
        'value' => 20.00,
        'min_order_amount' => 100.00,
        'is_active' => '1',
        'expires_at' => '2026-12-31T23:59',
    ]);

    $response->assertRedirect(route('admin.coupons.index'));
    $this->assertDatabaseHas('coupons', [
        'code' => 'NEWCOUPON',
        'type' => 'percent',
        'value' => 20.00,
        'min_order_amount' => 100.00,
        'is_active' => true,
    ]);
});

test('admin can edit and update a coupon', function () {
    $this->seed();
    $admin = Admin::where('email', 'admin@example.com')->first();

    $coupon = Coupon::create([
        'code' => 'TESTCOUPON',
        'type' => 'fixed',
        'value' => 30.00,
        'min_order_amount' => 10.00,
        'is_active' => true,
    ]);

    $response = $this->actingAs($admin, 'admin')->put(route('admin.coupons.update', $coupon), [
        'code' => 'UPDATEDCOUPON',
        'type' => 'fixed',
        'value' => 45.00,
        'min_order_amount' => 20.00,
        'is_active' => '1',
    ]);

    $response->assertRedirect(route('admin.coupons.index'));
    $this->assertDatabaseHas('coupons', [
        'id' => $coupon->id,
        'code' => 'UPDATEDCOUPON',
        'value' => 45.00,
        'min_order_amount' => 20.00,
    ]);
});

test('admin can delete a coupon', function () {
    $this->seed();
    $admin = Admin::where('email', 'admin@example.com')->first();

    $coupon = Coupon::create([
        'code' => 'DELETECOUPON',
        'type' => 'percent',
        'value' => 15.00,
        'min_order_amount' => 0.00,
        'is_active' => true,
    ]);

    $response = $this->actingAs($admin, 'admin')->delete(route('admin.coupons.destroy', $coupon));

    $response->assertRedirect(route('admin.coupons.index'));
    $this->assertDatabaseMissing('coupons', [
        'id' => $coupon->id,
    ]);
});
