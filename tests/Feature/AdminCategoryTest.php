<?php

use App\Models\Admin;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can create active categories up to 6', function () {
    $this->seed();
    $admin = Admin::where('email', 'admin@example.com')->first();

    // Create 5 active categories
    for ($i = 1; $i <= 5; $i++) {
        Category::create([
            'name' => "Category $i",
            'is_active' => true,
        ]);
    }

    // Creating the 6th category as active should succeed
    $response = $this->actingAs($admin, 'admin')->post(route('admin.categories.store'), [
        'name' => 'Category 6',
        'is_active' => '1',
    ]);

    $response->assertRedirect(route('admin.categories.index'));
    $this->assertDatabaseHas('categories', [
        'name' => 'Category 6',
        'is_active' => true,
    ]);
});

test('admin cannot create a 7th active category', function () {
    $this->seed();
    $admin = Admin::where('email', 'admin@example.com')->first();

    // Create 6 active categories
    for ($i = 1; $i <= 6; $i++) {
        Category::create([
            'name' => "Category $i",
            'is_active' => true,
        ]);
    }

    // Creating the 7th category as active should fail
    $response = $this->actingAs($admin, 'admin')->post(route('admin.categories.store'), [
        'name' => 'Category 7',
        'is_active' => '1',
    ]);

    $response->assertSessionHasErrors(['is_active']);
    $this->assertDatabaseMissing('categories', [
        'name' => 'Category 7',
    ]);
});

test('admin cannot update an inactive category to active if there are 6 active categories', function () {
    $this->seed();
    $admin = Admin::where('email', 'admin@example.com')->first();

    // Create 6 active categories
    for ($i = 1; $i <= 6; $i++) {
        Category::create([
            'name' => "Category $i",
            'is_active' => true,
        ]);
    }

    // Create 1 inactive category
    $inactive = Category::create([
        'name' => 'Inactive Category',
        'is_active' => false,
    ]);

    // Updating the inactive category to active should fail
    $response = $this->actingAs($admin, 'admin')->put(route('admin.categories.update', $inactive), [
        'name' => 'Inactive Category Updated',
        'is_active' => '1',
    ]);

    $response->assertSessionHasErrors(['is_active']);
    expect($inactive->fresh()->is_active)->toBeFalse();
});

test('admin cannot toggle status of an inactive category to active if there are 6 active categories', function () {
    $this->seed();
    $admin = Admin::where('email', 'admin@example.com')->first();

    // Create 6 active categories
    for ($i = 1; $i <= 6; $i++) {
        Category::create([
            'name' => "Category $i",
            'is_active' => true,
        ]);
    }

    // Create 1 inactive category
    $inactive = Category::create([
        'name' => 'Inactive Category',
        'is_active' => false,
    ]);

    // Toggling the inactive category to active should fail via API
    $response = $this->actingAs($admin, 'admin')->patchJson(route('admin.categories.toggle-status', $inactive));

    $response->assertStatus(422)
        ->assertJson([
            'success' => false,
            'is_active' => false,
            'message' => 'You cannot select more than 6 hot categories.',
        ]);

    expect($inactive->fresh()->is_active)->toBeFalse();
});

test('admin can toggle status of an active category to inactive even if there are 6 active categories', function () {
    $this->seed();
    $admin = Admin::where('email', 'admin@example.com')->first();

    // Create 6 active categories
    $categories = [];
    for ($i = 1; $i <= 6; $i++) {
        $categories[] = Category::create([
            'name' => "Category $i",
            'is_active' => true,
        ]);
    }

    // Toggling an active category to inactive should succeed
    $response = $this->actingAs($admin, 'admin')->patchJson(route('admin.categories.toggle-status', $categories[0]));

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'is_active' => false,
        ]);

    expect($categories[0]->fresh()->is_active)->toBeFalse();
});
