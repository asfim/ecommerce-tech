<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductLandingPage;
use Illuminate\View\View;

class LandingPageController extends Controller
{
    public function show(string $slug): View
    {
        $product = Product::with('landingPage')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $landingPage = $product->landingPage;

        if (!$landingPage) {
            $landingPage = new ProductLandingPage([
                'is_active' => true,
                'meta_title' => $product->name,
                'tagline' => 'প্রিমিয়াম কালেকশন ২০২৬',
                'heading' => '৫টি ভিন্ন ডিজাইন এক প্যাকেজে',
                'description' => 'প্রিমিয়াম কম্বড কটন • প্রতিটি টি-শার্ট আলাদা স্টাইল • সীমিত সংস্করণ',
                'delivery_text' => 'ফ্রি এক্সপ্রেস ডেলিভারি',
                'return_text' => '৩০ দিন রিটার্ন',
                'offer_text' => 'কম্বো অফার – ৫টি টি-শার্ট',
                'old_price' => $product->price * 1.5,
                'new_price' => $product->price,
                'discount_text' => 'বাঁচাচ্ছেন ৳' . number_format(($product->price * 1.5) - $product->price, 0, '.', ''),
                'stock_text' => 'মাত্র ২৫টি প্যাকেজ বাকি',
                'whatsapp_number' => '8801966789123',
                'whatsapp_text' => 'I want to order the 5 Premium Tee pack',
                'features' => [
                    ['icon' => 'fas fa-tshirt', 'title' => 'ক্লাসিক ফিট', 'description' => 'সাদা, কালো, নেভি – প্রতিদিনের জন্য পারফেক্ট'],
                    ['icon' => 'fas fa-palette', 'title' => 'আর্টিস্টিক প্রিন্ট', 'description' => 'হাতের আঁকা ডিজাইন, ইউনিক কালেকশন'],
                    ['icon' => 'fas fa-feather-alt', 'title' => 'অর্গানিক কটন', 'description' => 'নরম, হালকা, ঘাম শোষণকারী ফ্যাব্রিক'],
                    ['icon' => 'fas fa-running', 'title' => 'অ্যাক티브 ফিট', 'description' => 'স্পোর্টি ডিজাইন, জিম ও আউটডোরের জন্য'],
                    ['icon' => 'fas fa-moon', 'title' => 'লাউঞ্জ স্টাইল', 'description' => 'স্লিপওয়্যার ও ক্যাজুয়াল আউটিং'],
                    ['icon' => 'fas fa-gift', 'title' => 'ফ্রি গিফট', 'description' => 'অর্ডার করলেই পাবেন এক্সক্লুসিভ স্টিকার'],
                ],
                'testimonials' => [
                    ['rating' => '5', 'text' => 'পাঁচটি ভিন্ন ডিজাইন পেয়ে খুব খুশি। ফ্যাব্রিক অসাধারণ!', 'author' => 'রহিম, ঢাকা'],
                    ['rating' => '5', 'text' => 'গিফট পেয়ে মুগ্ধ! কোয়ালিটি অনেক ভালো। দ্রুত ডেলিভারি পেয়েছি।', 'author' => 'নাদিয়া, চট্টগ্রাম'],
                    ['rating' => '4.5', 'text' => 'এক প্যাকেজে ৫টি টি-শার্ট পাওয়ায় অনেক সাশ্রয়। সুপারিশ করব।', 'author' => 'সজীব, রাজশাহী'],
                ],
            ]);
        }

        if (!$landingPage->is_active) {
            abort(404);
        }

        return view('frontend.landing-page', compact('product', 'landingPage'));
    }
}

