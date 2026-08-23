<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\View\View;

class LandingPageController extends Controller
{
    public function show(string $slug): View
    {
        $product = Product::with('landingPage')
            ->where('slug', $slug)
            ->frontendActive()
            ->firstOrFail();

        $landingPage = $product->landingPage;

        if (! $landingPage || ! $landingPage->is_active) {
            abort(404);
        }

        return view('frontend.landing-page', compact('product', 'landingPage'));
    }
}
