<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CompanySettingController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:manage-company-settings,admin'),
        ];
    }

    public function index(): View
    {
        $settings = HomepageSetting::get('company_settings', []);

        return view('backend.settings.company', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'site_name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'whatsapp' => 'nullable|string|max:50',
            'google_map' => 'nullable|string',
            'facebook' => 'nullable|string|max:1000',
            'twitter' => 'nullable|string|max:1000',
            'youtube' => 'nullable|string|max:1000',
            'instagram' => 'nullable|string|max:1000',
            'pinterest' => 'nullable|string|max:1000',
            'linkedin' => 'nullable|string|max:1000',
        ]);

        $existing = HomepageSetting::get('company_settings', []);
        $logoPath = $existing['logo'] ?? null;
        $faviconPath = $existing['favicon'] ?? null;

        // If a new logo is uploaded, delete the old logo first if it exists
        if ($request->hasFile('logo')) {
            if ($logoPath && Storage::disk('public')->exists($logoPath)) {
                Storage::disk('public')->delete($logoPath);
            }
            $logoPath = $request->file('logo')->store('company', 'public');
        }

        // If a new favicon is uploaded, delete the old favicon first if it exists
        if ($request->hasFile('favicon')) {
            if ($faviconPath && Storage::disk('public')->exists($faviconPath)) {
                Storage::disk('public')->delete($faviconPath);
            }
            $faviconPath = $request->file('favicon')->store('company', 'public');
        }

        $settings = [
            'name' => $validated['name'],
            'site_name' => $validated['site_name'],
            'logo' => $logoPath,
            'favicon' => $faviconPath,
            'address' => $validated['address'] ?? '',
            'phone' => $validated['phone'] ?? '',
            'email' => $validated['email'] ?? '',
            'whatsapp' => $validated['whatsapp'] ?? '',
            'google_map' => $validated['google_map'] ?? '',
            'facebook' => $validated['facebook'] ?? '',
            'twitter' => $validated['twitter'] ?? '',
            'youtube' => $validated['youtube'] ?? '',
            'instagram' => $validated['instagram'] ?? '',
            'pinterest' => $validated['pinterest'] ?? '',
            'linkedin' => $validated['linkedin'] ?? '',
        ];

        HomepageSetting::set('company_settings', $settings);

        return redirect()->route('admin.settings.company')->with('success', 'Company settings updated successfully.');
    }
}
