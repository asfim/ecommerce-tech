<?php

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

$products = Product::whereNull('image')->get();
$count = 0;

foreach ($products as $product) {
    // Generate a placeholder image with the product name as text
    $text = urlencode($product->name);
    $url = "https://placehold.co/600x600/2a2a2a/ffffff.png?text={$text}";

    try {
        $imageContent = @file_get_contents($url);
        
        if ($imageContent) {
            $filename = 'products/' . uniqid() . '_' . Str::slug($product->name) . '.png';
            Storage::disk('public')->put($filename, $imageContent);
            
            $product->image = $filename;
            $product->save();
            $count++;
            
            echo "Downloaded placeholder image for: {$product->name}\n";
        }
    } catch (\Exception $e) {
        echo "Failed to download placeholder image for: {$product->name}\n";
    }
}

echo "\nSuccessfully downloaded and set images for $count remaining products!\n";
