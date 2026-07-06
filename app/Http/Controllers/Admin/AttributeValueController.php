<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;

class AttributeValueController extends Controller
{
    public function index(Attribute $attribute)
    {
        $values = $attribute->values()->latest()->paginate(10);

        return view('backend.attribute-values.index', compact('attribute', 'values'));
    }

    public function create(Attribute $attribute)
    {
        return view('backend.attribute-values.create', compact('attribute'));
    }

    public function store(Request $request, Attribute $attribute)
    {
        $validated = $request->validate([
            'value' => 'required|string|max:255',
        ]);

        $attribute->values()->create($validated);

        return redirect()->route('admin.attributes.values.index', $attribute)->with('success', 'Attribute value created successfully.');
    }

    public function edit(Attribute $attribute, AttributeValue $value)
    {
        return view('backend.attribute-values.edit', compact('attribute', 'value'));
    }

    public function update(Request $request, Attribute $attribute, AttributeValue $value)
    {
        $validated = $request->validate([
            'value' => 'required|string|max:255',
        ]);

        $value->update($validated);

        return redirect()->route('admin.attributes.values.index', $attribute)->with('success', 'Attribute value updated successfully.');
    }

    public function destroy(Attribute $attribute, AttributeValue $value)
    {
        $value->delete();

        return redirect()->route('admin.attributes.values.index', $attribute)->with('success', 'Attribute value deleted successfully.');
    }
}