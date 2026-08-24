<?php
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

$products = Product::all();
$count = 0;
$total = count($products);

echo "Starting download of real images via Bing for $total products...\n";

foreach ($products as $index => $product) {
    if (Str::contains($product->image, 'ai_')) {
        continue; // Skip AI images
    }

    $query = urlencode($product->name . " product photography isolated white background");
    $url = "https://www.bing.com/images/search?q={$query}&form=HDRSC3";

    try {
        $context = stream_context_create([
            'http' => [
                'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36\r\n",
                'timeout' => 5,
            ]
        ]);
        
        $html = @file_get_contents($url, false, $context);
        
        if ($html && preg_match_all('/murl&quot;:&quot;(.*?)&quot;/', $html, $matches)) {
            $downloaded = false;
            // Try top 3 image URLs
            for ($i = 0; $i < min(3, count($matches[1])); $i++) {
                $imgUrl = $matches[1][$i];
                
                // Avoid tiny images or icons if possible, though hard to know without downloading
                $imageContent = @file_get_contents($imgUrl, false, $context);
                
                if ($imageContent) {
                    $filename = 'products/bing_' . uniqid() . '_' . Str::slug($product->name) . '.jpg';
                    Storage::disk('public')->put($filename, $imageContent);
                    
                    if ($product->image && Storage::disk('public')->exists($product->image)) {
                        Storage::disk('public')->delete($product->image);
                    }
                    
                    $product->image = $filename;
                    $product->save();
                    $count++;
                    
                    echo "[" . ($index + 1) . "/$total] Bing Image for: {$product->name}\n";
                    $downloaded = true;
                    break;
                }
            }
            if (!$downloaded) {
                echo "[" . ($index + 1) . "/$total] Failed to download Bing image for: {$product->name}\n";
            }
        } else {
             echo "[" . ($index + 1) . "/$total] No Bing results for: {$product->name}\n";
        }
    } catch (\Exception $e) {
        echo "[" . ($index + 1) . "/$total] Error for: {$product->name}\n";
    }
    
    // Slight delay
    usleep(500000); // 0.5s
}

echo "\nSuccessfully downloaded and set exact product images for $count products!\n";
