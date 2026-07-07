<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $query = Product::with(['category', 'brand'])->latest();

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
        $brands = Brand::all();
        $attributes = Attribute::with('values')->get();

        return view('backend.products.create', compact('categories', 'brands', 'attributes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
            'is_active' => 'boolean',
            'variant_labels' => 'array',
            'variant_values' => 'array',
        ]);

        $variants = [];
        if (! empty($request->variant_labels)) {
            foreach ($request->variant_labels as $index => $label) {
                if (! empty($label) && isset($request->variant_values[$index])) {
                    $variant = [
                        'label' => $label,
                        'value' => $request->variant_values[$index],
                    ];
                    if (strtolower($label) === 'color' && ! empty($request->variant_colors[$index])) {
                        $variant['color'] = $request->variant_colors[$index];
                    }
                    $variants[] = $variant;
                }
            }
        }
        $validated['variants'] = $variants ?: null;

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

        Product::create($validated);

        ActivityLog::log('product_created', "Created product: {$validated['name']}");

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();
        $attributes = Attribute::with('values')->get();

        return view('backend.products.edit', compact('product', 'categories', 'brands', 'attributes'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,'.$product->id,
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
            'is_active' => 'boolean',
            'variant_labels' => 'array',
            'variant_values' => 'array',
        ]);

        $variants = [];
        if (! empty($request->variant_labels)) {
            foreach ($request->variant_labels as $index => $label) {
                if (! empty($label) && isset($request->variant_values[$index])) {
                    $variant = [
                        'label' => $label,
                        'value' => $request->variant_values[$index],
                    ];
                    if (strtolower($label) === 'color' && ! empty($request->variant_colors[$index])) {
                        $variant['color'] = $request->variant_colors[$index];
                    }
                    $variants[] = $variant;
                }
            }
        }
        $validated['variants'] = $variants ?: null;

        if ($request->hasFile('image')) {
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

        $product->update($validated);

        ActivityLog::log('product_updated', "Updated product: {$product->name}");

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $name = $product->name;
        $product->delete();

        ActivityLog::log('product_deleted', "Deleted product: {$name}");

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
