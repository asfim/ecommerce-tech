<?php

use App\Models\Admin;
use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

test('frontend page renders successfully', function () {
    $page = Page::create([
        'slug' => 'test-page',
        'title' => 'Test Page Title',
        'content' => 'Hello World Content',
    ]);

    $response = $this->get(route('page.show', 'test-page'));

    $response->assertStatus(200);
    $response->assertSee('Test Page Title');
    $response->assertSee('Hello World Content');
});

test('admin can view page edit page', function () {
    $this->seed();

    $admin = Admin::create([
        'name' => 'Test Admin',
        'email' => 'testadmin@example.com',
        'password' => bcrypt('password'),
    ]);

    $role = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'admin']);
    $admin->assignRole($role);

    $page = Page::where('slug', 'terms-conditions')->first();

    $response = $this->actingAs($admin, 'admin')->get(route('admin.pages.edit', $page));

    $response->assertStatus(200);
    $response->assertSee('Terms &amp; Conditions', false);
});

test('admin can update page content', function () {
    $this->seed();

    $admin = Admin::create([
        'name' => 'Test Admin',
        'email' => 'testadmin@example.com',
        'password' => bcrypt('password'),
    ]);

    $role = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'admin']);
    $admin->assignRole($role);

    $page = Page::where('slug', 'terms-conditions')->first();

    $response = $this->actingAs($admin, 'admin')->put(route('admin.pages.update', $page), [
        'title' => 'Terms & Conditions Updated',
        'content' => 'New Dynamic Content Text',
    ]);

    $response->assertRedirect(route('admin.pages.index'));

    $this->assertDatabaseHas('pages', [
        'slug' => 'terms-conditions',
        'title' => 'Terms & Conditions Updated',
        'content' => 'New Dynamic Content Text',
    ]);
});
