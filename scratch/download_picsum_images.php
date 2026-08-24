<?php

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

// Get products that still have placeholders or failed in the last script
$products = Product::where('image', 'like', '%placeholder%')
    ->orWhere('image', 'like', '%uniqid%') // Just to catch any failed ones
    ->orWhere('image', 'not like', '%real_%') // Only target products that don't have real images
    ->get();

$count = 0;
$total = count($products);

echo "Starting download of picsum real images for $total products...\n";

foreach ($products as $index => $product) {
    // Skip the ones we just successfully downloaded as AI images
    if (Str::contains($product->image, 'ai_')) {
        continue;
    }

    // Using picsum photos with a seed so it's consistent for the same product
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
}

echo "\nSuccessfully downloaded and set real images for $count products!\n";
