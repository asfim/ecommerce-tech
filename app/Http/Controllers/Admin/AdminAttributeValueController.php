<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;

class AdminAttributeValueController extends Controller
{
    public function globalIndex(Request $request)
    {
        $attributes = Attribute::all();
        $selectedAttributeId = $request->query('attribute_id');

        $attribute = null;
        $values = collect();

        if ($selectedAttributeId) {
            $attribute = Attribute::find($selectedAttributeId);
            if ($attribute) {
                $values = $attribute->values()->latest()->paginate(10)->withQueryString();
            }
        } elseif ($attributes->isNotEmpty()) {
            $attribute = $attributes->first();
            $values = $attribute->values()->latest()->paginate(10)->withQueryString();
        }

        return view('backend.attribute-values.global_index', compact('attributes', 'attribute', 'values'));
    }

    public function index(Attribute $attribute)
    {
        return redirect()->route('admin.attribute-values.index', ['attribute_id' => $attribute->id]);
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

        return redirect()->route('admin.attribute-values.index', ['attribute_id' => $attribute->id])->with('success', 'Attribute value created successfully.');
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

        return redirect()->route('admin.attribute-values.index', ['attribute_id' => $attribute->id])->with('success', 'Attribute value updated successfully.');
    }

    public function destroy(Attribute $attribute, AttributeValue $value)
    {
        $value->delete();

        return redirect()->route('admin.attribute-values.index', ['attribute_id' => $attribute->id])->with('success', 'Attribute value deleted successfully.');
    }
}
