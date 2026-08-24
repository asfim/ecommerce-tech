<?php

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

$products = Product::with('subCategory', 'category')->get();
$count = 0;
$total = count($products);

echo "Starting download of real images for $total products...\n";

foreach ($products as $index => $product) {
    // Determine keyword
    $keyword = '';
    if ($product->subCategory) {
        $keyword = urlencode(strtolower($product->subCategory->name));
    } else if ($product->category) {
        $keyword = urlencode(strtolower($product->category->name));
    } else {
        $keyword = 'product';
    }

    // Try a few different public image providers if one fails, but loremflickr is best for keywords
    // Using a random number to avoid aggressive caching
    $url = "https://loremflickr.com/600/600/{$keyword}?random={$product->id}";

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
            
            // Delete old image if it exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            
            $product->image = $filename;
            $product->save();
            $count++;
            
            echo "[" . ($index + 1) . "/$total] Downloaded real image for: {$product->name}\n";
        } else {
             echo "[" . ($index + 1) . "/$total] Failed to download real image for: {$product->name}\n";
        }
    } catch (\Exception $e) {
        echo "[" . ($index + 1) . "/$total] Error downloading real image for: {$product->name}\n";
    }
    
    // Sleep to avoid rate limiting
    usleep(1500000); // 1.5 seconds
}

echo "\nSuccessfully downloaded and set real images for $count products!\n";
