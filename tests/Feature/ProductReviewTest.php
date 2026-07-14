<?php

use App\Models\Admin;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated user can submit a product review successfully', function () {
    $category = Category::create([
        'name' => 'Home Appliances',
        'is_active' => true,
    ]);

    $brand = Brand::create([
        'name' => 'Samsung',
        'slug' => 'samsung',
        'is_active' => true,
    ]);

    $product = Product::create([
        'name' => 'Smart Refrigerator',
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'price' => 120000.00,
        'stock' => 5,
        'is_active' => true,
    ]);

    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('product.review.store', $product), [
        'name' => 'Alice Johnson',
        'rating' => 5,
        'comment' => 'This refrigerator is absolutely amazing!',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('reviews', [
        'product_id' => $product->id,
        'name' => $user->name, // Should use the authenticated user's name
        'rating' => 5,
        'comment' => 'This refrigerator is absolutely amazing!',
    ]);
});

test('guest user cannot submit a product review', function () {
    $category = Category::create([
        'name' => 'Home Appliances',
        'is_active' => true,
    ]);

    $brand = Brand::create([
        'name' => 'Samsung',
        'slug' => 'samsung',
        'is_active' => true,
    ]);

    $product = Product::create([
        'name' => 'Smart Refrigerator',
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'price' => 120000.00,
        'stock' => 5,
        'is_active' => true,
    ]);

    $response = $this->post(route('product.review.store', $product), [
        'name' => 'Alice Johnson',
        'rating' => 5,
        'comment' => 'This refrigerator is absolutely amazing!',
    ]);

    $response->assertRedirect(route('user.login'));
    $this->assertDatabaseMissing('reviews', [
        'product_id' => $product->id,
    ]);
});

test('ajax review submission works successfully for authenticated user', function () {
    $category = Category::create([
        'name' => 'Home Appliances',
        'is_active' => true,
    ]);

    $brand = Brand::create([
        'name' => 'Samsung',
        'slug' => 'samsung',
        'is_active' => true,
    ]);

    $product = Product::create([
        'name' => 'Smart Refrigerator',
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'price' => 120000.00,
        'stock' => 5,
        'is_active' => true,
    ]);

    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson(route('product.review.store', $product), [
        'name' => 'Alice Johnson',
        'rating' => 4,
        'comment' => 'Pretty good quality!',
    ], [
        'X-Requested-With' => 'XMLHttpRequest',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Review submitted successfully!',
        ])
        ->assertJsonStructure([
            'review' => ['name', 'rating', 'comment', 'created_at'],
        ]);
});

test('guest user receives 401 on ajax review submission', function () {
    $category = Category::create([
        'name' => 'Home Appliances',
        'is_active' => true,
    ]);

    $brand = Brand::create([
        'name' => 'Samsung',
        'slug' => 'samsung',
        'is_active' => true,
    ]);

    $product = Product::create([
        'name' => 'Smart Refrigerator',
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'price' => 120000.00,
        'stock' => 5,
        'is_active' => true,
    ]);

    $response = $this->postJson(route('product.review.store', $product), [
        'name' => 'Alice Johnson',
        'rating' => 4,
        'comment' => 'Pretty good quality!',
    ], [
        'X-Requested-With' => 'XMLHttpRequest',
    ]);

    $response->assertStatus(401)
        ->assertJson([
            'success' => false,
            'message' => 'You must be logged in to write a review.',
        ]);
});

test('review submission validation checks fail for invalid values', function () {
    $category = Category::create([
        'name' => 'Home Appliances',
        'is_active' => true,
    ]);

    $brand = Brand::create([
        'name' => 'Samsung',
        'slug' => 'samsung',
        'is_active' => true,
    ]);

    $product = Product::create([
        'name' => 'Smart Refrigerator',
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'price' => 120000.00,
        'stock' => 5,
        'is_active' => true,
    ]);

    $user = User::factory()->create();

    // Invalid rating (above 5) and empty name
    $response = $this->actingAs($user)->post(route('product.review.store', $product), [
        'name' => '',
        'rating' => 6,
        'comment' => 'Too high rating',
    ]);

    $response->assertSessionHasErrors(['name', 'rating']);
});

test('submitted reviews show up on the product details page', function () {
    $category = Category::create([
        'name' => 'Electronics',
        'is_active' => true,
    ]);

    $brand = Brand::create([
        'name' => 'Apple',
        'slug' => 'apple',
        'is_active' => true,
    ]);

    $product = Product::create([
        'name' => 'iPhone 15 Pro Max',
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'price' => 150000.00,
        'stock' => 10,
        'is_active' => true,
    ]);

    $review = Review::create([
        'product_id' => $product->id,
        'name' => 'Bob Smith',
        'rating' => 5,
        'comment' => 'Worth every single penny!',
    ]);

    $response = $this->get(route('product.details', $product->slug));

    $response->assertStatus(200)
        ->assertSee('Bob Smith')
        ->assertSee('Worth every single penny!');
});

test('admin with reviews permission can list and delete reviews', function () {
    $this->seed();

    $category = Category::create([
        'name' => 'Electronics',
        'is_active' => true,
    ]);

    $brand = Brand::create([
        'name' => 'Apple',
        'slug' => 'apple',
        'is_active' => true,
    ]);

    $product = Product::create([
        'name' => 'iPhone 15 Pro Max',
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'price' => 150000.00,
        'stock' => 10,
        'is_active' => true,
    ]);

    $review = Review::create([
        'product_id' => $product->id,
        'name' => 'Charlie Brown',
        'rating' => 2,
        'comment' => 'Disappointed with battery life.',
    ]);

    $admin = Admin::where('email', 'admin@example.com')->first();

    // 1. Visit reviews index page
    $response = $this->actingAs($admin, 'admin')->get(route('admin.reviews.index'));

    $response->assertStatus(200)
        ->assertSee('Charlie Brown')
        ->assertSee('Disappointed with battery life.')
        ->assertSee('iPhone 15 Pro Max');

    // 2. Delete the review
    $deleteResponse = $this->actingAs($admin, 'admin')->delete(route('admin.reviews.destroy', $review));

    $deleteResponse->assertRedirect();
    $this->assertDatabaseMissing('reviews', [
        'id' => $review->id,
    ]);
});
