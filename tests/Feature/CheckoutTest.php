<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('checkout page renders successfully', function () {
    $response = $this->get(route('checkout'));

    $response->assertStatus(200);
    $response->assertSee('Shipping address');
    $response->assertSee('SSL Commerz');
});
