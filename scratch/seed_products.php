<?php

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Support\Str;

$categories = Category::all();
$brands = Brand::pluck('id')->toArray();

if (empty($brands)) {
    echo "No brands found. Please seed brands first.\n";
    exit;
}

$faker = \Faker\Factory::create();

foreach ($categories as $category) {
    $subCategories = SubCategory::where('category_id', $category->id)->pluck('id')->toArray();
    
    for ($i = 1; $i <= 10; $i++) {
        $subCatId = !empty($subCategories) ? $faker->randomElement($subCategories) : null;
        $brandId = $faker->randomElement($brands);
        
        $name = $faker->words(3, true) . ' ' . $category->name . ' ' . $i;
        $slug = Str::slug($name . '-' . uniqid());
        
        $buyPrice = $faker->numberBetween(500, 50000);
        $price = $buyPrice + $faker->numberBetween(100, 5000);
        
        // Discount
        $discountType = null;
        $discountValue = 0;
        if ($faker->boolean(30)) { // 30% chance of discount
            $discountType = $faker->randomElement(['percent', 'fixed']);
            if ($discountType === 'percent') {
                $discountValue = $faker->numberBetween(5, 50);
            } else {
                $discountValue = $faker->numberBetween(50, 1000);
            }
        }

        // Variants
        $variants = null;
        if ($faker->boolean(40)) { // 40% chance of variants
            $variants = [
                [
                    'combo' => 'Color: Black',
                    'price' => $price,
                    'stock' => $faker->numberBetween(0, 50)
                ],
                [
                    'combo' => 'Color: White',
                    'price' => $price + 500,
                    'stock' => $faker->numberBetween(0, 50)
                ]
            ];
        }

        Product::create([
            'name' => ucwords($name),
            'slug' => $slug,
            'category_id' => $category->id,
            'sub_category_id' => $subCatId,
            'brand_id' => $brandId,
            'buy_price' => $buyPrice,
            'price' => $price,
            'discount_type' => $discountType,
            'discount_value' => $discountValue,
            'stock' => $faker->numberBetween(10, 100),
            'sales_count' => $faker->numberBetween(0, 500),
            'is_active' => 1,
            'is_new_arrival' => $faker->boolean(20) ? 1 : 0,
            'is_featured' => $faker->boolean(20) ? 1 : 0,
            'variants' => $variants
        ]);
    }
}

echo "Created 10 products per category successfully!\n";
