<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'brand'])->latest()->paginate(10);

        return view('backend.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();

        return view('backend.products.create', compact('categories', 'brands'));
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
            'is_active' => 'boolean',
            'has_variants' => 'boolean',
            'variant_labels' => 'array',
            'variant_values' => 'array',
        ]);

        $variants = [];
        if ($request->has('has_variants') && ! empty($request->variant_labels)) {
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

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();

        return view('backend.products.edit', compact('product', 'categories', 'brands'));
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
            'is_active' => 'boolean',
            'has_variants' => 'boolean',
            'variant_labels' => 'array',
            'variant_values' => 'array',
        ]);

        $variants = [];
        if ($request->has('has_variants') && ! empty($request->variant_labels)) {
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

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
