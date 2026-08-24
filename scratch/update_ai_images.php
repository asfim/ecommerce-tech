<?php

use Illuminate\Support\Facades\Storage;
use App\Models\Product;

$files = [
    129 => 'C:/Users/Admin/.gemini/antigravity-ide/brain/9949150d-7f2f-4231-b2de-2964381d63eb/ai_oximeter_1787562225826.jpg',
    35 => 'C:/Users/Admin/.gemini/antigravity-ide/brain/9949150d-7f2f-4231-b2de-2964381d63eb/ai_fashion_1787562238946.jpg',
    82 => 'C:/Users/Admin/.gemini/antigravity-ide/brain/9949150d-7f2f-4231-b2de-2964381d63eb/ai_electronics_1787562250607.jpg'
];

foreach ($files as $id => $sourcePath) {
    if (file_exists($sourcePath)) {
        $destPath = 'products/ai_' . uniqid() . '_' . $id . '.jpg';
        Storage::disk('public')->put($destPath, file_get_contents($sourcePath));
        $product = Product::find($id);
        if ($product) {
            $product->image = $destPath;
            $product->save();
            echo "Updated image for product ID $id\n";
        }
    } else {
        echo "Source file not found: $sourcePath\n";
    }
}
echo "Finished updating AI images.\n";
