<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class PaymentSettingController extends Controller implements HasMiddleware
{
    /**
     * Define the middleware for the controller.
     *
     * @return array<int, Middleware>
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:manage-company-settings,admin'),
        ];
    }

    /**
     * Display the payment settings page.
     */
    public function index(): View
    {
        $settings = HomepageSetting::get('sslcommerz_settings', [
            'sandbox' => '1',
            'store_id' => '',
            'store_password' => '',
            'currency' => 'BDT',
        ]);

        return view('backend.settings.payment', compact('settings'));
    }

    /**
     * Update the payment settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'sandbox' => 'required|in:0,1',
            'store_id' => 'required|string|max:255',
            'store_password' => 'required|string|max:255',
            'currency' => 'required|string|max:10',
        ]);

        HomepageSetting::set('sslcommerz_settings', [
            'sandbox' => $validated['sandbox'],
            'store_id' => $validated['store_id'],
            'store_password' => $validated['store_password'],
            'currency' => $validated['currency'],
        ]);

        return back()->with('success', 'SSLCommerz payment gateway configuration updated successfully.');
    }
}
