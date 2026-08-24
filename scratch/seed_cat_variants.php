<?php

use App\Models\Category;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Product;
use Illuminate\Support\Str;

$categoryAttributes = [
    1 => ['RAM' => ['4GB', '8GB', '16GB'], 'Storage' => ['64GB', '128GB', '256GB'], 'Color' => ['Black', 'Silver']],
    4 => ['Size' => ['S', 'M', 'L', 'XL'], 'Color' => ['Red', 'Blue', 'Black', 'White'], 'Material' => ['Cotton', 'Polyester']],
    7 => ['Color' => ['White', 'Brown', 'Black'], 'Material' => ['Wood', 'Plastic', 'Metal']],
    8 => ['Weight' => ['50g', '100g', '200g'], 'Scent' => ['Rose', 'Lavender', 'Citrus']],
    9 => ['Size' => ['Small', 'Medium', 'Large'], 'Color' => ['White', 'Blue']],
    10 => ['Size' => ['S', 'M', 'L'], 'Color' => ['Black', 'Red', 'Blue']],
    11 => ['Color' => ['Pink', 'Blue', 'Yellow'], 'Age Group' => ['0-6m', '6-12m', '1-3y', '3+y']],
    12 => ['Color' => ['Black', 'Silver'], 'Material' => ['Leather', 'Plastic']],
    13 => ['Pack Size' => ['Pack of 1', 'Pack of 3', 'Pack of 5'], 'Color' => ['Blue', 'Black', 'Red']],
];

// Seed the attributes globally
foreach ($categoryAttributes as $catId => $attrs) {
    foreach ($attrs as $attrName => $values) {
        $attribute = Attribute::firstOrCreate(['name' => $attrName]);
        foreach ($values as $val) {
            AttributeValue::firstOrCreate([
                'attribute_id' => $attribute->id,
                'value' => $val
            ]);
        }
    }
}

$faker = \Faker\Factory::create();
$products = Product::all();

foreach ($products as $product) {
    $catId = $product->category_id;
    
    // If we have defined attributes for this category
    if (isset($categoryAttributes[$catId])) {
        $attrs = $categoryAttributes[$catId];
        
        if ($faker->boolean(60)) { // 60% chance to have variants
            $attrNames = array_keys($attrs);
            // Pick a random attribute to create variants for (or two)
            $selectedAttr = $faker->randomElement($attrNames);
            $values = $attrs[$selectedAttr];
            
            $variants = [];
            // Pick 2 random values from that attribute
            $selectedValues = $faker->randomElements($values, min(2, count($values)));
            
            foreach ($selectedValues as $val) {
                $combo = [$selectedAttr => $val];
                // Maybe add a second attribute
                if (count($attrNames) > 1 && $faker->boolean(50)) {
                    $secondAttr = null;
                    foreach ($attrNames as $a) {
                        if ($a !== $selectedAttr) { $secondAttr = $a; break; }
                    }
                    if ($secondAttr) {
                        $secondVal = $faker->randomElement($attrs[$secondAttr]);
                        $combo[$secondAttr] = $secondVal;
                    }
                }
                
                $skuSuffix = collect($combo)->values()->map(function($v) { return strtoupper(Str::slug($v)); })->implode('-');
                $sku = strtoupper(Str::slug($product->name)) . '-' . $skuSuffix;
                
                $variants[] = [
                    'combo' => $combo,
                    'sku' => $sku,
                    'price' => $product->price + $faker->numberBetween(0, 1000),
                    'stock' => $faker->numberBetween(10, 100),
                    'active' => true
                ];
            }
            $product->variants = $variants;
        } else {
            $product->variants = null;
        }
        $product->save();
    }
}

echo "Category-specific variants generated successfully!\n";
