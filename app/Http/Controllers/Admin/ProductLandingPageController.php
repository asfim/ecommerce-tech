<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductLandingPage;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;

class ProductLandingPageController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:create-products,admin', only: ['create', 'store']),
            new Middleware('permission:edit-products,admin', only: ['edit', 'update']),
        ];
    }

    public function create(Product $product): View|RedirectResponse
    {
        if ($product->landingPage) {
            return redirect()->route('admin.products.landing-page.edit', $product);
        }

        $defaultFeatures = [
            ['icon' => 'fas fa-tshirt', 'title' => 'ক্লাসিক ফিট', 'description' => 'সাদা, কালো, নেভি – প্রতিদিনের জন্য পারফেক্ট'],
            ['icon' => 'fas fa-palette', 'title' => 'আর্টিস্টিক প্রিন্ট', 'description' => 'হাতের আঁকা ডিজাইন, ইউনিক কালেকশন'],
            ['icon' => 'fas fa-feather-alt', 'title' => 'অর্গানিক কটন', 'description' => 'নরম, হালকা, ঘাম শোষণকারী ফ্যাব্রিক'],
            ['icon' => 'fas fa-running', 'title' => 'অ্যাকティブ ফিট', 'description' => 'স্পোর্টি ডিজাইন, জিম ও আউটডোরের জন্য'],
            ['icon' => 'fas fa-moon', 'title' => 'লাউঞ্জ স্টাইল', 'description' => 'স্লিপওয়্যার ও ক্যাজুয়াল আউটিং'],
            ['icon' => 'fas fa-gift', 'title' => 'ফ্রি গিফট', 'description' => 'অর্ডার করলেই পাবেন এক্সক্লুসিভ স্টিকার'],
        ];

        $defaultTestimonials = [
            ['rating' => '5', 'text' => 'পাঁচটি ভিন্ন ডিজাইন পেয়ে খুব খুশি। ফ্যাব্রিক অসাধারণ!', 'author' => 'রহিম, ঢাকা'],
            ['rating' => '5', 'text' => 'গিফট পেয়ে মুগ্ধ! কোয়ালিটি অনেক ভালো। দ্রুত ডেলিভারি পেয়েছি।', 'author' => 'নাদিয়া, চট্টগ্রাম'],
            ['rating' => '4.5', 'text' => 'এক প্যাকেজে ৫টি টি-শার্ট পাওয়ায় অনেক সাশ্রয়। সুপারিশ করব।', 'author' => 'সজীব, রাজশাহী'],
        ];

        return view('backend.landing-pages.form', compact('product', 'defaultFeatures', 'defaultTestimonials'));
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        if ($product->landingPage) {
            return redirect()->route('admin.products.landing-page.edit', $product);
        }

        $validated = $request->validate([
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'heading' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'delivery_text' => 'nullable|string|max:255',
            'return_text' => 'nullable|string|max:255',
            'offer_text' => 'nullable|string|max:255',
            'old_price' => 'nullable|numeric|min:0',
            'new_price' => 'nullable|numeric|min:0',
            'discount_text' => 'nullable|string|max:255',
            'stock_text' => 'nullable|string|max:255',
            'whatsapp_number' => 'nullable|string|max:20',
            'whatsapp_text' => 'nullable|string|max:255',
            'features' => 'nullable|array',
            'features.*.icon' => 'required|string|max:100',
            'features.*.title' => 'required|string|max:255',
            'features.*.description' => 'required|string',
            'testimonials' => 'nullable|array',
            'testimonials.*.rating' => 'required|numeric|min:1|max:5',
            'testimonials.*.text' => 'required|string',
            'testimonials.*.author' => 'required|string|max:255',
            'image' => 'nullable|image|max:10240',
        ]);

        $validated['product_id'] = $product->id;
        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('landing_pages', 'public');
        }

        ProductLandingPage::create($validated);

        ActivityLog::log('landing_page_created', "Created landing page for product: {$product->name}");

        return redirect()->route('admin.products.index')->with('success', 'Landing page created successfully.');
    }

    public function edit(Product $product): View|RedirectResponse
    {
        $landingPage = $product->landingPage;

        if (!$landingPage) {
            return redirect()->route('admin.products.landing-page.create', $product);
        }

        return view('backend.landing-pages.form', compact('product', 'landingPage'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $landingPage = $product->landingPage;

        if (!$landingPage) {
            return redirect()->route('admin.products.landing-page.create', $product);
        }

        $validated = $request->validate([
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'heading' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'delivery_text' => 'nullable|string|max:255',
            'return_text' => 'nullable|string|max:255',
            'offer_text' => 'nullable|string|max:255',
            'old_price' => 'nullable|numeric|min:0',
            'new_price' => 'nullable|numeric|min:0',
            'discount_text' => 'nullable|string|max:255',
            'stock_text' => 'nullable|string|max:255',
            'whatsapp_number' => 'nullable|string|max:20',
            'whatsapp_text' => 'nullable|string|max:255',
            'features' => 'nullable|array',
            'features.*.icon' => 'required|string|max:100',
            'features.*.title' => 'required|string|max:255',
            'features.*.description' => 'required|string',
            'testimonials' => 'nullable|array',
            'testimonials.*.rating' => 'required|numeric|min:1|max:5',
            'testimonials.*.text' => 'required|string',
            'testimonials.*.author' => 'required|string|max:255',
            'image' => 'nullable|image|max:10240',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($landingPage->image) {
                Storage::disk('public')->delete($landingPage->image);
            }
            $validated['image'] = $request->file('image')->store('landing_pages', 'public');
        }

        $landingPage->update($validated);

        ActivityLog::log('landing_page_updated', "Updated landing page for product: {$product->name}");

        return redirect()->route('admin.products.index')->with('success', 'Landing page updated successfully.');
    }
}
