<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\HomepageSetting;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Search products for live autocomplete.
     */
    public function searchApi(Request $request): JsonResponse
    {
        $query = $request->query('q');
        if (empty($query)) {
            return response()->json([]);
        }

        $products = Product::frontendActive()
            ->where('name', 'like', '%'.$query.'%')
            ->take(5)
            ->get(['id', 'name', 'price', 'slug', 'image']);

        $formatted = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => number_format($product->price, 2),
                'url' => route('product.details', $product->slug),
                'image' => $product->image ? asset('storage/'.$product->image) : 'https://placehold.co/50x50/eee/aaa?text=No+Img',
            ];
        });

        return response()->json($formatted);
    }

    public function index(): View|JsonResponse
    {
        $heroBanners = HomepageSetting::get('hero_banners', []);
        $bestSellingBanners = HomepageSetting::get('best_selling_banners', []);
        $discountedProductsBanner = HomepageSetting::get('discounted_products_banner', []);
        $maxDiscountPercent = 0;
        $activeProducts = Product::frontendActive()->get(['id', 'price', 'discount_type', 'discount_value', 'discount_start_date', 'discount_expiry_date', 'variants']);
        foreach ($activeProducts as $p) {
            $pMax = 0;
            if ($p->has_active_discount) {
                if ($p->discount_type === 'percent') {
                    $pMax = (float) $p->discount_value;
                } elseif ($p->discount_type === 'fixed' && $p->price > 0) {
                    $pMax = ((float) $p->discount_value / (float) $p->price) * 100;
                }
            }
            if (is_array($p->variants)) {
                $now = now();
                foreach ($p->variants as $v) {
                    if (isset($v['combo']) && !empty($v['discount_type']) && (float)($v['discount'] ?? 0) > 0) {
                        $start = !empty($v['discount_start']) ? \Carbon\Carbon::parse($v['discount_start']) : null;
                        $end = !empty($v['discount_end']) ? \Carbon\Carbon::parse($v['discount_end']) : null;
                        $isActive = true;
                        if ($start && $start->gt($now)) $isActive = false;
                        if ($end && $end->lt($now)) $isActive = false;
                        
                        if ($isActive) {
                            if ($v['discount_type'] === 'percent') {
                                $pMax = max($pMax, (float) $v['discount']);
                            } elseif ($v['discount_type'] === 'fixed' && !empty($v['price']) && (float) $v['price'] > 0) {
                                $pMax = max($pMax, ((float) $v['discount'] / (float) $v['price']) * 100);
                            }
                        }
                    }
                }
            }
            $maxDiscountPercent = max($maxDiscountPercent, $pMax);
        }
        $maxDiscountPercent = round($maxDiscountPercent);

        $hotCategories = Category::where('is_active', true)->take(8)->get();
        $trendingCategories = Category::where('is_trending', true)->get();
        $featuredProducts = Product::where('is_featured', true)
            ->frontendActive()
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->latest()
            ->get();

        $bestSellingProducts = Product::frontendActive()
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->latest()
            ->take(10)
            ->get();
        $discountedProducts = Product::frontendActive()
            ->where(function ($q) {
                $q->where(function($sq) {
                    $sq->whereNotNull('discount_type')
                       ->where('discount_value', '>', 0);
                })
                ->orWhere('variants', 'LIKE', '%"discount":"%')
                ->orWhere('variants', 'LIKE', '%"discount": %')
                ->orWhere('variants', 'LIKE', '%"discount":1%')
                ->orWhere('variants', 'LIKE', '%"discount":2%')
                ->orWhere('variants', 'LIKE', '%"discount":3%')
                ->orWhere('variants', 'LIKE', '%"discount":4%')
                ->orWhere('variants', 'LIKE', '%"discount":5%')
                ->orWhere('variants', 'LIKE', '%"discount":6%')
                ->orWhere('variants', 'LIKE', '%"discount":7%')
                ->orWhere('variants', 'LIKE', '%"discount":8%')
                ->orWhere('variants', 'LIKE', '%"discount":9%');
            })
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->latest()
            ->get()
            ->filter(function($p) { return $p->has_any_discount; })
            ->take(5);
        $newArrivalProducts = Product::frontendActive()
            ->where('is_new_arrival', true)
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->latest()
            ->take(12)
            ->get();
        $search = request()->query('search');

        if (request()->ajax() && request()->has('category_id')) {
            $categoryId = request()->query('category_id');
            $page = (int) request()->query('page', 1);
            $limit = 4;
            $offset = 8 + ($page - 2) * 4;

            $productsQuery = Product::where('category_id', $categoryId)
                ->frontendActive()
                ->withAvg('reviews', 'rating')
                ->withCount('reviews')
                ->latest();

            if (! empty($search)) {
                $productsQuery->where('name', 'like', '%'.$search.'%');
            }

            $ajaxProducts = $productsQuery->skip($offset)->take($limit)->get();
            $hasMore = $productsQuery->skip($offset + $limit)->exists();

            $html = '';
            foreach ($ajaxProducts as $product) {
                $html .= view('frontend.partials.product_card', compact('product'))->render();
            }

            return response()->json([
                'html' => $html,
                'has_more' => $hasMore,
            ]);
        }

        $categoriesQuery = Category::where('is_active', true)
            ->whereHas('products', function ($q) use ($search) {
                $q->frontendActive();
                if (! empty($search)) {
                    $q->where('name', 'like', '%'.$search.'%');
                }
            })
            ->withCount(['products' => function ($q) use ($search) {
                $q->frontendActive();
                if (! empty($search)) {
                    $q->where('name', 'like', '%'.$search.'%');
                }
            }])
            ->with(['products' => function ($q) use ($search) {
                $q->frontendActive()
                    ->withAvg('reviews', 'rating')
                    ->withCount('reviews')
                    ->latest()
                    ->take(8);

                if (! empty($search)) {
                    $q->where('name', 'like', '%'.$search.'%');
                }
            }]);

        $homeCategories = $categoriesQuery->get();
        $hasMore = false;

        return view('home', compact(
            'heroBanners',
            'hotCategories',
            'trendingCategories',
            'featuredProducts',
            'bestSellingBanners',
            'discountedProductsBanner',
            'bestSellingProducts',
            'discountedProducts',
            'newArrivalProducts',
            'homeCategories',
            'hasMore',
            'maxDiscountPercent'
        ));
    }

    public function productDetails(string $slug): View
    {
        $product = Product::frontendActive()->with('reviews')->where('slug', $slug)->firstOrFail();
        $relatedProducts = Product::frontendActive()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->take(4)
            ->get();

        return view('product-details', compact('product', 'relatedProducts'));
    }

    public function categoryProducts(Request $request, int $id): View|JsonResponse
    {
        $category = Category::findOrFail($id);

        $query = Product::frontendActive()
            ->where('category_id', $category->id)
            ->with('brand');

        $selectedSubCategory = null;
        if ($request->has('subcategory')) {
            $subCatId = $request->query('subcategory');
            $query->where('sub_category_id', $subCatId);
            $selectedSubCategory = SubCategory::find($subCatId);
        }

        // Price filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->max_price);
        }

        // Brand filter
        if ($request->filled('brands')) {
            $brandIds = explode(',', $request->brands);
            $query->whereIn('brand_id', $brandIds);
        }

        // Sort
        $sort = $request->query('sort', '');
        
        $priceExpression = "
            CASE 
                WHEN discount_type = 'percent' AND discount_value > 0 THEN price - (price * (discount_value / 100))
                WHEN discount_type = 'flat' AND discount_value > 0 THEN price - discount_value
                ELSE price 
            END
        ";

        if ($sort === 'price_asc') {
            $query->orderByRaw("($priceExpression) ASC");
        } elseif ($sort === 'price_desc') {
            $query->orderByRaw("($priceExpression) DESC");
        } else {
            $query->latest();
        }

        $isFiltered = $request->filled('min_price') || $request->filled('max_price') || $request->filled('brands') || $request->filled('sort');
        $perPage = $isFiltered ? 10000 : 12;

        $products = $query
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->paginate($perPage)
            ->withQueryString();

        // Price range for this category
        $priceRange = Product::frontendActive()
            ->where('category_id', $category->id)
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();

        if ($request->ajax()) {
            $html = '';
            foreach ($products as $product) {
                $html .= '<div class="col-6 col-md-4 col-lg-3">'
                    . view('frontend.partials.category_product_card', compact('product'))->render()
                    . '</div>';
            }
            return response()->json([
                'html'       => $html,
                'pagination' => (string) $products->links(),
                'total'      => $products->total(),
                'has_more'   => $products->hasMorePages(),
            ]);
        }

        return view('category-products', compact('category', 'products', 'selectedSubCategory', 'priceRange'));
    }

    public function checkout(): View|RedirectResponse
    {
        // if (! auth()->check()) {
        //     return redirect()->route('user.login');
        // }

        return view('checkout');
    }

    /**
     * Display the contact page.
     */
    public function contact(): View
    {
        $companySettings = HomepageSetting::get('company_settings', []);

        return view('frontend.contact', compact('companySettings'));
    }

    public function shop(): View
    {
        $products = Product::frontendActive()
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->latest()
            ->paginate(12);

        return view('shop', compact('products'));
    }

    public function flashSale(): View
    {
        $products = Product::frontendActive()
            ->where(function ($q) {
                $q->where(function($sq) {
                    $sq->whereNotNull('discount_type')
                       ->where('discount_value', '>', 0);
                })
                ->orWhere('variants', 'LIKE', '%"discount":"1%')
                ->orWhere('variants', 'LIKE', '%"discount":"2%')
                ->orWhere('variants', 'LIKE', '%"discount":"3%')
                ->orWhere('variants', 'LIKE', '%"discount":"4%')
                ->orWhere('variants', 'LIKE', '%"discount":"5%')
                ->orWhere('variants', 'LIKE', '%"discount":"6%')
                ->orWhere('variants', 'LIKE', '%"discount":"7%')
                ->orWhere('variants', 'LIKE', '%"discount":"8%')
                ->orWhere('variants', 'LIKE', '%"discount":"9%')
                ->orWhere('variants', 'LIKE', '%"discount":1%')
                ->orWhere('variants', 'LIKE', '%"discount":2%')
                ->orWhere('variants', 'LIKE', '%"discount":3%')
                ->orWhere('variants', 'LIKE', '%"discount":4%')
                ->orWhere('variants', 'LIKE', '%"discount":5%')
                ->orWhere('variants', 'LIKE', '%"discount":6%')
                ->orWhere('variants', 'LIKE', '%"discount":7%')
                ->orWhere('variants', 'LIKE', '%"discount":8%')
                ->orWhere('variants', 'LIKE', '%"discount":9%');
            })
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->latest()
            ->paginate(12);

        return view('flash-sale', compact('products'));
    }
}
