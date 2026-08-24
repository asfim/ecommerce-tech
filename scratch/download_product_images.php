<?php

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

$products = Product::with('subCategory', 'category')->get();
$count = 0;

foreach ($products as $product) {
    // Generate a keyword for the image search based on subcategory or category
    $keyword = '';
    if ($product->subCategory) {
        $keyword = urlencode(strtolower($product->subCategory->name));
    } else if ($product->category) {
        $keyword = urlencode(strtolower($product->category->name));
    } else {
        $keyword = 'product';
    }

    // LoremFlickr URL for a 600x600 image matching the keyword
    $url = "https://loremflickr.com/600/600/{$keyword}";

    try {
        // Suppress warnings in case of network issues
        $imageContent = @file_get_contents($url);
        
        if ($imageContent) {
            $filename = 'products/' . uniqid() . '_' . Str::slug($product->name) . '.jpg';
            Storage::disk('public')->put($filename, $imageContent);
            
            $product->image = $filename;
            $product->save();
            $count++;
            
            echo "Downloaded image for: {$product->name}\n";
        }
    } catch (\Exception $e) {
        echo "Failed to download image for: {$product->name}\n";
    }
}

echo "\nSuccessfully downloaded and set images for $count products!\n";
