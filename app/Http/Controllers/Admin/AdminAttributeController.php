<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AdminAttributeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view-attributes,admin', only: ['index', 'show']),
            new Middleware('permission:create-attributes,admin', only: ['create', 'store']),
            new Middleware('permission:edit-attributes,admin', only: ['edit', 'update']),
            new Middleware('permission:delete-attributes,admin', only: ['destroy']),
        ];
    }

    public function index()
    {
        $attributes = Attribute::latest()->paginate(10);

        return view('backend.attributes.index', compact('attributes'));
    }

    public function create()
    {
        return view('backend.attributes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:attributes',
        ]);

        Attribute::create($validated);

        return redirect()->route('admin.attributes.index')->with('success', 'Attribute created successfully.');
    }

    public function edit(Attribute $attribute)
    {
        return view('backend.attributes.edit', compact('attribute'));
    }

    public function update(Request $request, Attribute $attribute)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:attributes,name,'.$attribute->id,
        ]);

        $attribute->update($validated);

        return redirect()->route('admin.attributes.index')->with('success', 'Attribute updated successfully.');
    }

    public function destroy(Attribute $attribute)
    {
        $attribute->delete();

        return redirect()->route('admin.attributes.index')->with('success', 'Attribute deleted successfully.');
    }
}
