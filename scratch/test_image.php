<?php
use App\Models\Product;

$p = Product::find(133);
if (!$p) $p = Product::first();
echo "Before: " . ($p->image ?? 'null') . "\n";
$p->update(['image' => null]);
echo "After: " . ($p->image ?? 'null') . "\n";
