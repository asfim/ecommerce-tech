<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CompanySettingController extends Controller
{
    public function index(): View
    {
        $settings = HomepageSetting::get('company_settings', []);

        return view('backend.settings.company', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
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
            'logo' => $logoPath,
            'favicon' => $faviconPath,
            'address' => $validated['address'] ?? '',
            'phone' => $validated['phone'] ?? '',
        ];

        HomepageSetting::set('company_settings', $settings);

        return redirect()->route('admin.settings.company')->with('success', 'Company settings updated successfully.');
    }
}
