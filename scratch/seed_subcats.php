<?php

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Str;

$data = [
    3 => ['Smartphone', 'Android Phone', 'iPhone', 'Gaming Smartphone'],
    7 => ['LED Smart Bulb', 'Smart WiFi Plug', 'Electric Kettle', 'Air Humidifier'],
    8 => ['Electric Hair Trimmer', 'Hair Dryer', 'Hair Straightener', 'Facial Cleansing Brush'],
    9 => ['Digital Blood Pressure Monitor', 'Digital Thermometer', 'Pulse Oximeter', 'Electric Heating Pad'],
    10 => ['Yoga Mat', 'Resistance Band Set', 'Dumbbell Set', 'Skipping Rope'],
    11 => ['Baby Stroller', 'Baby Feeding Bottle', 'Baby Feeding Set', 'Baby Walker'],
    12 => ['Car Phone Holder', 'Car Charger', 'Car Vacuum Cleaner', 'Car Air Freshener'],
    13 => ['Notebook', 'Diary', 'Planner', 'Gel Pen Set']
];

foreach ($data as $categoryId => $subCats) {
    foreach ($subCats as $subCatName) {
        SubCategory::updateOrCreate(
            [
                'category_id' => $categoryId,
                'name' => $subCatName
            ],
            [
                'slug' => Str::slug($subCatName)
            ]
        );
    }
}

echo "Subcategories created successfully!\n";
