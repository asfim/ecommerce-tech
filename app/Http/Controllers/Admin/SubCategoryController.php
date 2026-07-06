<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    public function index()
    {
        $subCategories = SubCategory::with('category')->latest()->get();

        return view('backend.sub-categories.index', compact('subCategories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('backend.sub-categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active', true);

        // Ensure slug uniqueness
        $slug = $validated['slug'];
        $count = SubCategory::where('slug', 'like', $slug.'%')->count();
        if ($count > 0) {
            $validated['slug'] = $slug.'-'.$count;
        }

        SubCategory::create($validated);

        return redirect()->route('admin.sub-categories.index')
            ->with('success', 'Sub-category created successfully.');
    }

    public function edit(SubCategory $subCategory)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('backend.sub-categories.edit', compact('subCategory', 'categories'));
    }

    public function update(Request $request, SubCategory $subCategory)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active', true);

        // Ensure slug uniqueness excluding self
        $slug = $validated['slug'];
        $count = SubCategory::where('slug', 'like', $slug.'%')
            ->where('id', '!=', $subCategory->id)
            ->count();
        if ($count > 0) {
            $validated['slug'] = $slug.'-'.$count;
        }

        $subCategory->update($validated);

        return redirect()->route('admin.sub-categories.index')
            ->with('success', 'Sub-category updated successfully.');
    }

    public function destroy(SubCategory $subCategory)
    {
        $subCategory->delete();

        return redirect()->route('admin.sub-categories.index')
            ->with('success', 'Sub-category deleted successfully.');
    }
}
