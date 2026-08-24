<?php

use App\Models\Brand;

$brands = [
    'Apple', 'Samsung', 'Xiaomi', 'OnePlus', 
    'Philips', 'Panasonic', 'Walton', 'Vision', 
    'L\'Oreal', 'Nivea', 'Gillette', 'Braun', 
    'Omron', 'Beurer', 'Accu-Chek', 'Rossmax', 
    'Adidas', 'Nike', 'Puma', 'Reebok', 
    'Lego', 'Fisher-Price', 'Chicco', 'Pampers', 
    'Baseus', 'Michelin', 'Pioneer', 'Bosch', 
    'Matador', 'Faber-Castell', 'Linc', 'Pilot'
];

foreach ($brands as $brandName) {
    Brand::firstOrCreate(
        ['name' => $brandName],
        ['is_active' => 1]
    );
}

echo "Brands created successfully!\n";
