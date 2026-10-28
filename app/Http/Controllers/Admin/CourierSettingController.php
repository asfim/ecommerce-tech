<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourierSettingController extends Controller
{
    public function index(): View
    {
        $fraudSettings = HomepageSetting::get('courier_fraud_settings', [
            'cancel_threshold' => 2,
            'cancel_rate_threshold' => 40,
            'auto_block_ip' => true,
        ]);

        $steadfastSettings = HomepageSetting::get('steadfast_settings', [
            'api_key' => '',
            'secret_key' => '',
            'base_url' => 'https://portal.packzy.com/api/v1',
        ]);

        $pathaoSettings = HomepageSetting::get('pathao_settings', [
            'token' => '',
            'client_id' => '',
            'client_secret' => '',
            'base_url' => 'https://api-hermes.pathao.com',
        ]);

        $redxSettings = HomepageSetting::get('redx_settings', [
            'access_token' => '',
            'base_url' => 'https://openapi.redx.com.bd',
        ]);

        $paperflySettings = HomepageSetting::get('paperfly_settings', [
            'username' => '',
            'password' => '',
            'key' => '',
            'base_url' => 'https://api.paperfly.com.bd',
        ]);

        $ecourierSettings = HomepageSetting::get('ecourier_settings', [
            'user_id' => '',
            'api_key' => '',
            'api_secret' => '',
            'base_url' => 'https://ecourier.com.bd/api',
        ]);

        return view('backend.settings.courier', compact(
            'fraudSettings',
            'steadfastSettings',
            'pathaoSettings',
            'redxSettings',
            'paperflySettings',
            'ecourierSettings'
        ));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'cancel_threshold' => 'required|integer|min:1|max:50',
            'cancel_rate_threshold' => 'required|numeric|min:1|max:100',
            'auto_block_ip' => 'nullable|boolean',

            // Steadfast Direct
            'steadfast_api_key' => 'nullable|string',
            'steadfast_secret_key' => 'nullable|string',
            'steadfast_base_url' => 'nullable|url',

            // Pathao Direct
            'pathao_token' => 'nullable|string',
            'pathao_client_id' => 'nullable|string',
            'pathao_client_secret' => 'nullable|string',
            'pathao_base_url' => 'nullable|url',

            // RedX Direct
            'redx_access_token' => 'nullable|string',
            'redx_base_url' => 'nullable|url',

            // Paperfly Direct
            'paperfly_username' => 'nullable|string',
            'paperfly_password' => 'nullable|string',
            'paperfly_key' => 'nullable|string',
            'paperfly_base_url' => 'nullable|url',

            // eCourier Direct
            'ecourier_user_id' => 'nullable|string',
            'ecourier_api_key' => 'nullable|string',
            'ecourier_api_secret' => 'nullable|string',
            'ecourier_base_url' => 'nullable|url',
        ]);

        HomepageSetting::set('courier_fraud_settings', [
            'cancel_threshold' => (int) $validated['cancel_threshold'],
            'cancel_rate_threshold' => (float) $validated['cancel_rate_threshold'],
            'auto_block_ip' => $request->has('auto_block_ip'),
        ]);

        HomepageSetting::set('steadfast_settings', [
            'api_key' => $validated['steadfast_api_key'] ?? '',
            'secret_key' => $validated['steadfast_secret_key'] ?? '',
            'base_url' => $validated['steadfast_base_url'] ?? 'https://portal.packzy.com/api/v1',
        ]);

        HomepageSetting::set('pathao_settings', [
            'token' => $validated['pathao_token'] ?? '',
            'client_id' => $validated['pathao_client_id'] ?? '',
            'client_secret' => $validated['pathao_client_secret'] ?? '',
            'base_url' => $validated['pathao_base_url'] ?? 'https://api-hermes.pathao.com',
        ]);

        HomepageSetting::set('redx_settings', [
            'access_token' => $validated['redx_access_token'] ?? '',
            'base_url' => $validated['redx_base_url'] ?? 'https://openapi.redx.com.bd',
        ]);

        HomepageSetting::set('paperfly_settings', [
            'username' => $validated['paperfly_username'] ?? '',
            'password' => $validated['paperfly_password'] ?? '',
            'key' => $validated['paperfly_key'] ?? '',
            'base_url' => $validated['paperfly_base_url'] ?? 'https://api.paperfly.com.bd',
        ]);

        HomepageSetting::set('ecourier_settings', [
            'user_id' => $validated['ecourier_user_id'] ?? '',
            'api_key' => $validated['ecourier_api_key'] ?? '',
            'api_secret' => $validated['ecourier_api_secret'] ?? '',
            'base_url' => $validated['ecourier_base_url'] ?? 'https://ecourier.com.bd/api',
        ]);

        return back()->with('success', 'Courier Direct API & Fraud Rules settings updated successfully.');
    }
}
