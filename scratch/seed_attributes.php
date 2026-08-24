<?php

use App\Models\Attribute;
use App\Models\AttributeValue;

$attributesData = [
    'Color' => ['Black', 'White', 'Red', 'Blue', 'Silver', 'Gold'],
    'Size' => ['S', 'M', 'L', 'XL', 'XXL'],
    'Storage' => ['64GB', '128GB', '256GB', '512GB', '1TB'],
    'Material' => ['Cotton', 'Leather', 'Plastic', 'Metal']
];

foreach ($attributesData as $attrName => $values) {
    $attribute = Attribute::firstOrCreate(['name' => $attrName]);
    
    foreach ($values as $val) {
        AttributeValue::firstOrCreate([
            'attribute_id' => $attribute->id,
            'value' => $val
        ]);
    }
}

echo "Attributes and their values created successfully!\n";
