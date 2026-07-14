<?php

use App\Models\HomepageSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('displays contact page with default info when settings are empty', function () {
    $response = $this->get('/contact');

    $response->assertSuccessful();
    $response->assertSee('info@ecommerce.com');
    $response->assertSee('Contact Us');
});

it('displays contact page with dynamic information from settings', function () {
    HomepageSetting::set('company_settings', [
        'name' => 'Test Company',
        'site_name' => 'Test Site',
        'phone' => '+18885550199',
        'email' => 'support@testcompany.com',
        'whatsapp' => '+18885550100',
        'address' => '456 Test Road, Tech City',
        'google_map' => 'https://www.google.com/maps/embed?pb=test-maps-url',
    ]);

    $response = $this->get('/contact');

    $response->assertSuccessful();
    $response->assertSee('+18885550199');
    $response->assertSee('support@testcompany.com');
    $response->assertSee('+18885550100');
    $response->assertSee('456 Test Road, Tech City');
});
