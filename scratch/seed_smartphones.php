<?php

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\Brand;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

$categoryId = 3; // Smartphones & Tablets

// Create Subcategories
$subcatsData = [
    'Smartphone',
    'Android Phone',
    'iPhone',
    'Tablet'
];

$subCategories = [];
foreach ($subcatsData as $name) {
    $subCategories[$name] = SubCategory::firstOrCreate([
        'name' => $name,
        'category_id' => $categoryId,
        'slug' => Str::slug($name)
    ]);
}

$brand = Brand::first(); // Just get any brand
$brandId = $brand ? $brand->id : null;

// Realistic Product Data
$productsData = [
    ['name' => 'Samsung Galaxy S24 Ultra', 'sub' => 'Android Phone'],
    ['name' => 'Apple iPhone 15 Pro Max', 'sub' => 'iPhone'],
    ['name' => 'Google Pixel 8 Pro', 'sub' => 'Android Phone'],
    ['name' => 'OnePlus 12 5G', 'sub' => 'Smartphone'],
    ['name' => 'Apple iPad Air 5th Gen', 'sub' => 'Tablet'],
    ['name' => 'Samsung Galaxy Tab S9', 'sub' => 'Tablet'],
    ['name' => 'Xiaomi 14 Ultra', 'sub' => 'Android Phone'],
    ['name' => 'Nothing Phone (2)', 'sub' => 'Smartphone'],
    ['name' => 'Apple iPhone 14 Plus', 'sub' => 'iPhone'],
    ['name' => 'Lenovo Tab P12 Pro', 'sub' => 'Tablet'],
];

echo "Seeding " . count($productsData) . " products in category $categoryId...\n";

foreach ($productsData as $data) {
    $sub = $subCategories[$data['sub']];
    
    // Create product
    $product = Product::create([
        'name' => $data['name'],
        'slug' => Str::slug($data['name']),
        'category_id' => $categoryId,
        'sub_category_id' => $sub->id,
        'brand_id' => $brandId,
        'price' => rand(500, 1500),
        'stock' => rand(10, 100),
        'description' => 'Premium high quality ' . strtolower($data['name']) . ' with advanced features.',
        'status' => 'active',
        'is_featured' => rand(0, 1),
    ]);
    
    // Download real image via picsum
    $url = "https://picsum.photos/seed/{$product->id}/600/600";
    try {
        $context = stream_context_create([
            'http' => [
                'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)\r\n",
                'timeout' => 10,
            ]
        ]);
        
        $imageContent = @file_get_contents($url, false, $context);
        if ($imageContent) {
            $filename = 'products/real_' . uniqid() . '_' . Str::slug($product->name) . '.jpg';
            Storage::disk('public')->put($filename, $imageContent);
            $product->image = $filename;
            $product->save();
            echo "Created product and downloaded image for: {$product->name}\n";
        }
    } catch (\Exception $e) {
        echo "Failed image for: {$product->name}\n";
    }
}

echo "Seeding completed!\n";
