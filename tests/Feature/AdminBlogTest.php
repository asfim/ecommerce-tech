<?php

use App\Models\Admin;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest cannot view admin blog posts index', function () {
    $response = $this->get(route('admin.blog-posts.index'));
    $response->assertRedirect('/admin/login');
});

test('authorized admin can view admin blog posts index', function () {
    $this->seed();
    $admin = Admin::where('email', 'admin@example.com')->first();

    $post = BlogPost::create([
        'title' => 'Building Fast Backends',
        'summary' => 'Some summary content about fast backends.',
        'body' => 'Detailed body content.',
    ]);

    $response = $this->actingAs($admin, 'admin')->get(route('admin.blog-posts.index'));

    $response->assertSuccessful()
        ->assertSee('Building Fast Backends')
        ->assertSee('building-fast-backends');
});

test('admin can create a blog post', function () {
    $this->seed();
    $admin = Admin::where('email', 'admin@example.com')->first();

    $response = $this->actingAs($admin, 'admin')->post(route('admin.blog-posts.store'), [
        'title' => 'Scaling Laravel Apps',
        'summary' => 'Laravel scaling tips.',
        'body' => '<p>Laravel can scale very well.</p>',
        'is_active' => '1',
    ]);

    $response->assertRedirect(route('admin.blog-posts.index'));
    $this->assertDatabaseHas('blog_posts', [
        'title' => 'Scaling Laravel Apps',
        'slug' => 'scaling-laravel-apps',
        'summary' => 'Laravel scaling tips.',
    ]);
});

test('admin can update a blog post', function () {
    $this->seed();
    $admin = Admin::where('email', 'admin@example.com')->first();

    $post = BlogPost::create([
        'title' => 'Old Title',
        'summary' => 'Old summary.',
        'body' => 'Old body.',
    ]);

    $response = $this->actingAs($admin, 'admin')->put(route('admin.blog-posts.update', $post), [
        'title' => 'New Title',
        'summary' => 'New summary.',
        'body' => 'New body.',
        'is_active' => '1',
    ]);

    $response->assertRedirect(route('admin.blog-posts.index'));
    $this->assertDatabaseHas('blog_posts', [
        'id' => $post->id,
        'title' => 'New Title',
        'slug' => 'new-title',
    ]);
});

test('admin can delete a blog post', function () {
    $this->seed();
    $admin = Admin::where('email', 'admin@example.com')->first();

    $post = BlogPost::create([
        'title' => 'Post to Delete',
        'summary' => 'To delete summary.',
        'body' => 'To delete body.',
    ]);

    $response = $this->actingAs($admin, 'admin')->delete(route('admin.blog-posts.destroy', $post));

    $response->assertRedirect(route('admin.blog-posts.index'));
    $this->assertDatabaseMissing('blog_posts', [
        'id' => $post->id,
    ]);
});

test('public users can view blogs index and individual blog details', function () {
    $user = User::factory()->create();

    $post = BlogPost::create([
        'title' => 'Ultimate Design Guide',
        'slug' => 'ultimate-design-guide',
        'summary' => 'Summary about ultimate design.',
        'body' => 'Body about ultimate design.',
        'is_active' => true,
        'views' => 10,
    ]);

    // View Index
    $response = $this->actingAs($user)->get(route('blogs.index'));
    $response->assertSuccessful()
        ->assertSee('Ultimate Design Guide')
        ->assertSee('10 views');

    // View Details
    $response = $this->actingAs($user)->get(route('blogs.show', $post->slug));
    $response->assertSuccessful()
        ->assertSee('Ultimate Design Guide')
        ->assertSee('Body about ultimate design.');

    // Check views incremented
    $post->refresh();
    expect($post->views)->toBe(11);
});
