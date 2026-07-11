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
        $settings = HomepageSetting::get('steadfast_settings', [
            'api_key' => '',
            'secret_key' => '',
            'base_url' => 'https://portal.packzy.com/api/v1',
        ]);

        return view('backend.settings.courier', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'api_key' => 'required|string',
            'secret_key' => 'required|string',
            'base_url' => 'required|url',
        ]);

        HomepageSetting::set('steadfast_settings', $validated);

        return back()->with('success', 'Courier API configuration updated successfully.');
    }
}
