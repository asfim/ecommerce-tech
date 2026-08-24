<?php

use App\Models\SubCategory;
use App\Models\Product;

$subIds = [4, 5, 6, 20, 21];
SubCategory::whereIn('id', $subIds)->update(['category_id' => 3]);
Product::whereIn('sub_category_id', $subIds)->update(['category_id' => 3]);
echo "Shifted back successfully! " . Product::where('category_id', 3)->count() . " products now in category 3.\n";
