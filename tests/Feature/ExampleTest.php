<?php

test('the application returns a successful response and renders layout components', function () {
    $response = $this->get('/');

    $response->assertStatus(200)
        ->assertSee('Betty T. Niles') // header component check
        ->assertSee('eCommerce CMS is an online shopping platform') // footer component check
        ->assertSee('Trending Categories'); // home view content check
});
