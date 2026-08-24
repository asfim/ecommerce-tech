<?php
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

// Get all categories except "Smartphones & Tablets"
$categories = Category::where('name', '!=', 'Smartphones & Tablets')->get();
$deletedCount = 0;

foreach ($categories as $category) {
    // Get products for this category, ordered by id asc (to keep the first ones)
    $products = Product::where('category_id', $category->id)->orderBy('id', 'asc')->get();
    
    if ($products->count() > 5) {
        // Keep the first 5, delete the rest
        $productsToDelete = $products->slice(5);
        
        foreach ($productsToDelete as $product) {
            // Delete product image if exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            
            // Delete gallery images if any
            if ($product->images && is_array($product->images)) {
                foreach ($product->images as $img) {
                    if (Storage::disk('public')->exists($img)) {
                        Storage::disk('public')->delete($img);
                    }
                }
            }
            
            $product->delete();
            $deletedCount++;
        }
        echo "Category '{$category->name}': Kept 5 products, deleted " . $productsToDelete->count() . " products.\n";
    } else {
        echo "Category '{$category->name}': Has {$products->count()} products (<= 5). Kept all.\n";
    }
}

echo "\nTotal products deleted: {$deletedCount}\n";
