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
        $bestSellingBanners = HomepageSetting::get('best_selling_banners', []);
        $newArrivalsBanner = HomepageSetting::get('new_arrivals_banner', []);
        $discountedProductsBanner = HomepageSetting::get('discounted_products_banner', []);
        $hotCategories = Category::where('is_active', true)->take(8)->get();
        $trendingCategories = Category::where('is_trending', true)->get();
        $featuredProducts = Product::where('is_featured', true)
            ->where('is_active', true)
            ->latest()
            ->get();
        $bestSellingProducts = Product::where('is_active', true)
            ->orderBy('sales_count', 'desc')
            ->take(5)
            ->get();
        $discountedProducts = Product::where('is_active', true)
            ->whereNotNull('discount_type')
            ->where('discount_value', '>', 0)
            ->latest()
            ->take(5)
            ->get();
        $products = Product::where('is_active', true)->latest()->get();

        return view('home', compact(
            'heroBanners',
            'hotCategories',
            'trendingCategories',
            'featuredProducts',
            'bestSellingBanners',
            'newArrivalsBanner',
            'discountedProductsBanner',
            'bestSellingProducts',
            'discountedProducts',
            'products'
        ));
    }
}
