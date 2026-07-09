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
        $newArrivalProducts = Product::where('is_active', true)
            ->latest()
            ->take(12)
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
            'newArrivalProducts',
            'products'
        ));
    }

    public function productDetails(string $slug): View
    {
        $product = Product::with('reviews')->where('slug', $slug)->firstOrFail();
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('product-details', compact('product', 'relatedProducts'));
    }

    public function categoryProducts(int $id): View
    {
        $category = Category::findOrFail($id);
        $products = Product::where('category_id', $category->id)
            ->where('is_active', true)
            ->latest()
            ->paginate(12);

        return view('category-products', compact('category', 'products'));
    }

    public function checkout(): View
    {
        return view('checkout');
    }
}
