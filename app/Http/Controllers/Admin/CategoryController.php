<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view-categories,admin', only: ['index', 'show']),
            new Middleware('permission:create-categories,admin', only: ['create', 'store']),
            new Middleware('permission:edit-categories,admin', only: ['edit', 'update']),
            new Middleware('permission:delete-categories,admin', only: ['destroy']),
        ];
    }

    public function index()
    {
        $categories = Category::latest()->paginate(10);

        return view('backend.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('backend.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'image' => 'nullable|image|max:3048',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        return view('backend.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$category->id,
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $validated['image'] = $request->file('image')->store('categories', 'public');
        } else {
            unset($validated['image']);
        }

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    public function toggleStatus(Category $category): JsonResponse
    {
        $category->update(['is_active' => ! $category->is_active]);

        return response()->json([
            'is_active' => $category->is_active,
            'message' => $category->is_active ? 'Category activated.' : 'Category deactivated.',
        ]);
    }

    public function toggleTrending(Category $category): JsonResponse
    {
        $category->update(['is_trending' => ! $category->is_trending]);

        return response()->json([
            'is_trending' => $category->is_trending,
            'message' => $category->is_trending ? 'Category marked as trending.' : 'Category removed from trending.',
        ]);
    }
}
