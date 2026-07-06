<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the application returns a successful response and renders layout components', function () {
    $user = User::factory()->create([
        'name' => 'Betty T. Niles',
    ]);

    $response = $this->actingAs($user)->get('/');

    $response->assertStatus(200)
        ->assertSee('Betty T. Niles') // header component check
        ->assertSee('eCommerce CMS is an online shopping platform') // footer component check
        ->assertSee('Trending Categories'); // home view content check
});
