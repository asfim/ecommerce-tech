<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view-products,admin', only: ['index', 'show']),
            new Middleware('permission:create-products,admin', only: ['create', 'store']),
            new Middleware('permission:edit-products,admin', only: ['edit', 'update', 'toggleFeatured', 'toggleActive', 'toggleNewArrival']),
            new Middleware('permission:delete-products,admin', only: ['destroy', 'bulkDestroy']),
        ];
    }

    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $query = Product::with(['category', 'brand', 'landingPage'])
            ->withSum(['orderItems as delivered_sales_count' => function ($query) {
                $query->whereHas('order', function ($q) {
                    $q->where('order_status', 'delivered');
                });
            }], 'quantity')
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('price', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($catQuery) use ($search) {
                        $catQuery->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('brand', function ($brandQuery) use ($search) {
                        $brandQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($perPage === 'all') {
            $products = $query->get();
        } else {
            $products = $query->paginate((int) $perPage)->appends(['per_page' => $perPage]);
        }

        return view('backend.products.index', compact('products', 'perPage'));
    }

    public function create()
    {
        $categories = Category::all();
        $subCategories = SubCategory::query()->where('is_active', true)->get();
        $brands = Brand::all();
        $attributes = Attribute::with('values')->get();

        return view('backend.products.create', compact('categories', 'subCategories', 'brands', 'attributes'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'slug' => $request->slug ?: Str::slug($request->name),
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'brand_id' => 'required|exists:brands,id',
            'buy_price' => 'nullable|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'discount_type' => 'nullable|string|in:percent,fixed',
            'discount_value' => 'nullable|numeric|min:0',
            'discount_start_date' => 'nullable|date',
            'discount_expiry_date' => 'nullable|date|after_or_equal:discount_start_date',
            'stock' => 'required|integer|min:0',
            'sales_count' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:10240',
            'images' => 'nullable|array',
            'images.*' => 'image|max:10240',
            'is_active' => 'boolean',
            'is_new_arrival' => 'boolean',
            'is_new_arrival' => 'boolean',
            'variants' => 'nullable|array',
            'description' => 'nullable|string',
            'specifications' => 'nullable|array',
            'specifications.*.label' => 'nullable|string|max:255',
            'specifications.*.value' => 'nullable|string|max:500',
        ]);

        $variants = [];
        if ($request->has('variants')) {
            foreach ($request->variants as $index => $vData) {
                $imagePath = null;
                if ($request->hasFile("variants.$index.image")) {
                    $imagePath = $request->file("variants.$index.image")->store('products/variants', 'public');
                }
                $vData['image'] = $imagePath;
                $vData['active'] = isset($vData['active']) ? (bool) $vData['active'] : true;
                $variants[] = $vData;
            }
        }
        $validated['variants'] = $variants ?: null;

        $validated['sales_count'] = $validated['sales_count'] ?? 0;
        $validated['discount_value'] = $validated['discount_value'] ?? 0;
        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : true;
        $validated['is_new_arrival'] = $request->has('is_new_arrival') ? $request->boolean('is_new_arrival') : true;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $images[] = $file->store('products/gallery', 'public');
            }
        }
        $validated['images'] = $images ?: null;
        $validated['description'] = $request->input('description');
        $validated['specifications'] = $request->input('specifications') ? array_values(array_filter($request->input('specifications'), fn ($s) => ! empty($s['label']))) : null;

        Product::create($validated);

        ActivityLog::log('product_created', "Created product: {$validated['name']}");

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'subCategory', 'brand']);
        return view('backend.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $subCategories = SubCategory::query()->where('is_active', true)->get();
        $brands = Brand::all();
        $attributes = Attribute::with('values')->get();

        return view('backend.products.edit', compact('product', 'categories', 'subCategories', 'brands', 'attributes'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,'.$product->id,
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'brand_id' => 'required|exists:brands,id',
            'buy_price' => 'nullable|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'discount_type' => 'nullable|string|in:percent,fixed',
            'discount_value' => 'nullable|numeric|min:0',
            'discount_start_date' => 'nullable|date',
            'discount_expiry_date' => 'nullable|date|after_or_equal:discount_start_date',
            'stock' => 'required|integer|min:0',
            'sales_count' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:10240',
            'images' => 'nullable|array',
            'images.*' => 'image|max:10240',
            'is_active' => 'boolean',
            'is_new_arrival' => 'boolean',
            'is_new_arrival' => 'boolean',
            'variants' => 'nullable|array',
            'description' => 'nullable|string',
            'specifications' => 'nullable|array',
            'specifications.*.label' => 'nullable|string|max:255',
            'specifications.*.value' => 'nullable|string|max:500',
        ]);

        $variants = [];
        if ($request->has('variants')) {
            foreach ($request->variants as $index => $vData) {
                $existingImage = null;
                // Preserve existing image if available
                if (isset($product->variants)) {
                    foreach ($product->variants as $v) {
                        if (isset($v['combo']) && isset($vData['combo']) && $v['combo'] === $vData['combo'] && isset($v['image'])) {
                            $existingImage = $v['image'];
                        }
                    }
                }

                if (isset($vData['removedDbImage']) && $vData['removedDbImage']) {
                    $existingImage = null;
                }

                $imagePath = $existingImage;
                if ($request->hasFile("variants.$index.image")) {
                    $imagePath = $request->file("variants.$index.image")->store('products/variants', 'public');
                }

                $vData['image'] = $imagePath;
                $vData['active'] = isset($vData['active']) ? (bool) $vData['active'] : true;
                $variants[] = $vData;
            }
        }
        $validated['variants'] = $variants ?: null;

        $validated['sales_count'] = $validated['sales_count'] ?? 0;
        $validated['discount_value'] = $validated['discount_value'] ?? 0;
        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : false;
        $validated['is_new_arrival'] = $request->has('is_new_arrival') ? $request->boolean('is_new_arrival') : $product->is_new_arrival;

        if ($request->has('remove_main_image') && $request->remove_main_image == '1') {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = null;
        } elseif ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        } else {
            unset($validated['image']);
        }

        $images = $product->images ?? [];
        $galleryChanged = false;

        if ($request->hasFile('images')) {
            $newImages = [];
            foreach ($request->file('images') as $file) {
                $newImages[] = $file->store('products/gallery', 'public');
            }
            $images = array_merge($images, $newImages);
            $galleryChanged = true;
        }

        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $delImg) {
                if (($key = array_search($delImg, $images)) !== false) {
                    unset($images[$key]);
                }
            }
            $images = array_values($images);
            $galleryChanged = true;
        }

        if ($galleryChanged) {
            $validated['images'] = $images ?: null;
        } else {
            unset($validated['images']);
        }

        $validated['description'] = $request->input('description');
        $validated['specifications'] = $request->input('specifications') ? array_values(array_filter($request->input('specifications'), fn ($s) => ! empty($s['label']))) : null;

        $product->update($validated);

        ActivityLog::log('product_updated', "Updated product: {$product->name}");

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $name = $product->name;
        $product->delete();

        ActivityLog::log('product_deleted', "Deleted product: {$name}");

        return redirect()->back()->with('success', 'Product deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:products,id',
        ]);

        $ids = $request->input('ids');
        $count = Product::whereIn('id', $ids)->delete();

        ActivityLog::log('product_deleted', "Bulk deleted {$count} products");

        return redirect()->back()->with('success', "{$count} products deleted successfully.");
    }

    public function toggleFeatured(Product $product): JsonResponse
    {
        $product->is_featured = ! $product->is_featured;
        $product->save();

        return response()->json([
            'is_featured' => $product->is_featured,
            'message' => $product->is_featured ? 'Product marked as featured.' : 'Product removed from featured.',
        ]);
    }

    public function toggleActive(Product $product): JsonResponse
    {
        $product->is_active = ! $product->is_active;
        $product->save();

        return response()->json([
            'is_active' => $product->is_active,
            'message' => $product->is_active ? 'Product marked as active.' : 'Product marked as inactive.',
        ]);
    }

    public function toggleNewArrival(Product $product): JsonResponse
    {
        $product->is_new_arrival = ! $product->is_new_arrival;
        $product->save();

        return response()->json([
            'is_new_arrival' => $product->is_new_arrival,
            'message' => $product->is_new_arrival ? 'Product marked as new arrival.' : 'Product removed from new arrivals.',
        ]);
    }
}
