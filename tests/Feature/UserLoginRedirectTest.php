<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user is redirected back to the page they came from after login', function () {
    $user = User::factory()->create([
        'email' => 'customer@example.com',
        'password' => bcrypt('password123'),
    ]);

    $previousUrl = route('home');

    $response = $this->from($previousUrl)->get(route('user.login'));
    $response->assertSuccessful();

    expect(session()->get('url.intended'))->toEqual($previousUrl);

    $loginResponse = $this->post(route('user.login.submit'), [
        'email' => 'customer@example.com',
        'password' => 'password123',
    ]);

    $loginResponse->assertRedirect($previousUrl);
});

test('user is redirected to user dashboard if there is no previous page stored', function () {
    $user = User::factory()->create([
        'email' => 'customer@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->get(route('user.login'));
    $response->assertSuccessful();

    expect(session()->has('url.intended'))->toBeFalse();

    $loginResponse = $this->post(route('user.login.submit'), [
        'email' => 'customer@example.com',
        'password' => 'password123',
    ]);

    $loginResponse->assertRedirect(route('user.dashboard'));
});
