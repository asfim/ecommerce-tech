<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSetting;
use Illuminate\Http\Request;

class HomepageSettingController extends Controller
{
    // Section definitions: key => max images
    private array $sections = [
        'hero_banners' => 2,
        'best_selling_banners' => 3,
        'new_arrivals_banner' => 1,
        'discounted_products_banner' => 1,
    ];

    public function index()
    {
        $settings = [];
        foreach (array_keys($this->sections) as $key) {
            $settings[$key] = HomepageSetting::get($key, []);
        }

        return view('backend.settings.homepage', compact('settings'));
    }

    public function update(Request $request, string $section)
    {
        if (! array_key_exists($section, $this->sections)) {
            abort(404);
        }

        $maxImages = $this->sections[$section];
        $existing = HomepageSetting::get($section, []);
        $images = is_array($existing) ? $existing : [];

        // Handle deletions first
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $path) {
                $fullPath = storage_path('app/public/'.$path);
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
                $images = array_values(array_filter($images, fn ($i) => $i !== $path));
            }
        }

        // Handle new uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if (count($images) >= $maxImages) {
                    break; // enforce max
                }
                $path = $file->store('homepage', 'public');
                $images[] = $path;
            }
        }

        HomepageSetting::set($section, $images);

        return redirect()
            ->route('admin.settings.homepage', ['tab' => $section])
            ->with('success', 'Section updated successfully.');
    }
}
