<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\HomepageSetting;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $heroBanners = HomepageSetting::get('hero_banners', []);
        $hotCategories = Category::where('is_active', true)->take(8)->get();
        $trendingCategories = Category::where('is_trending', true)->get();
        $featuredProducts = Product::where('is_featured', true)
            ->where('is_active', true)
            ->latest()
            ->get();

        return view('home', compact('heroBanners', 'hotCategories', 'trendingCategories', 'featuredProducts'));
    }
}
