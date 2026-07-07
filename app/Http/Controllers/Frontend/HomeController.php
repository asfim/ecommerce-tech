<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\HomepageSetting;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $heroBanners = HomepageSetting::get('hero_banners', []);
        $hotCategories = Category::where('is_active', true)->take(8)->get();

        return view('home', compact('heroBanners', 'hotCategories'));
    }
}
