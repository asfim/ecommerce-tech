<?php

use App\Models\Product;

$products = Product::whereNotNull('variants')->get();

foreach ($products as $product) {
    $fixedVariants = [];
    $changed = false;

    foreach ($product->variants as $variant) {
        if (is_string($variant['combo'])) {
            // Convert 'Color: Black' to ['Color' => 'Black']
            $parts = explode(':', $variant['combo']);
            if (count($parts) == 2) {
                $variant['combo'] = [trim($parts[0]) => trim($parts[1])];
                $variant['sku'] = strtoupper(Str::slug(trim($parts[1])) . '-' . uniqid());
                $variant['active'] = true;
                $changed = true;
            }
        }
        $fixedVariants[] = $variant;
    }

    if ($changed) {
        $product->variants = $fixedVariants;
        // Make sure it's saved as an array, Eloquent will cast it if $casts = ['variants' => 'array']
        $product->save();
    }
}

echo "Variants fixed successfully!\n";
