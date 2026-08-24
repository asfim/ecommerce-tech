<?php

use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Support\Str;

$realNames = [
    'Smartphone' => ['Samsung Galaxy S23', 'Google Pixel 8', 'OnePlus 11', 'Xiaomi 13 Pro'],
    'Android Phone' => ['Oppo Reno 10', 'Vivo V29', 'Realme 11 Pro', 'Motorola Edge 40'],
    'iPhone' => ['Apple iPhone 15 Pro', 'Apple iPhone 14', 'Apple iPhone 13 Mini', 'Apple iPhone SE'],
    'Gaming Smartphone' => ['ASUS ROG Phone 7', 'Black Shark 5', 'RedMagic 8 Pro', 'Poco F5'],
    'LED Smart Bulb' => ['Philips Hue Color Ambiance', 'Xiaomi Mi Smart LED Bulb', 'TP-Link Tapo Smart Bulb', 'Wipro 9W Smart LED'],
    'Smart WiFi Plug' => ['Wemo Mini Smart Plug', 'TP-Link Kasa Smart Plug', 'Amazon Smart Plug', 'Gosund Smart WiFi Plug'],
    'Electric Kettle' => ['Philips 1.5L Electric Kettle', 'Miyako 2L Stainless Steel Kettle', 'Panasonic Electric Kettle', 'Walton Electric Water Heater'],
    'Air Humidifier' => ['Xiaomi Deerma Air Humidifier', 'Levoit Ultrasonic Humidifier', 'Philips Series 2000 Humidifier', 'Baseus Mini Car Humidifier'],
    'Electric Hair Trimmer' => ['Philips Multigroom Trimmer', 'Braun Beard Trimmer', 'Panasonic Cordless Trimmer', 'Wahl Professional Clipper'],
    'Hair Dryer' => ['Dyson Supersonic Hair Dryer', 'Philips Essential Care Dryer', 'Panasonic Nanoe Hair Dryer', 'Remington Pro Hair Dryer'],
    'Hair Straightener' => ['Philips Selfie Straightener', 'Remington Ceramic Straightener', 'Braun Satin Hair 7', 'Kemei Professional Hair Iron'],
    'Facial Cleansing Brush' => ['Foreo Luna Mini 3', 'Olay Regenerist Cleansing Brush', 'Philips VisaPure Mini', 'Clinique Sonic Brush'],
    'Digital Blood Pressure Monitor' => ['Omron HEM-7120 BP Monitor', 'Beurer BM 28 Blood Pressure Monitor', 'Rossmax X1 BP Machine', 'Dr. Trust Smart BP Monitor'],
    'Digital Thermometer' => ['Omron Digital Thermometer', 'Beurer Clinical Thermometer', 'Hicks Digital Thermometer', 'Braun ThermoScan Ear Thermometer'],
    'Pulse Oximeter' => ['Beurer PO 30 Pulse Oximeter', 'Dr. Trust Finger Oximeter', 'Choicemmed Pulse Oximeter', 'Accu-Chek Fingertip Oximeter'],
    'Electric Heating Pad' => ['Beurer HK 25 Heating Pad', 'Flamingo Orthopaedic Heat Belt', 'Dr. Trust Electric Heat Pad', 'Pieseta Heating Pad'],
    'Yoga Mat' => ['Adidas Essential Yoga Mat', 'Nike Mastery Yoga Mat', 'Decathlon Domyos Yoga Mat', 'Reebok Double Sided Mat'],
    'Resistance Band Set' => ['TheraBand Resistance Band', 'Decathlon Cross Training Band', 'Fitbit Resistance Tubes', 'Nike Recovery Band'],
    'Dumbbell Set' => ['Decathlon Hex Dumbbell 5kg', 'Adidas Neoprene Dumbbell 3kg', 'Bowflex SelectTech Dumbbells', 'Reebok Cast Iron Dumbbell'],
    'Skipping Rope' => ['Nike Fundamental Speed Rope', 'Adidas Essential Skipping Rope', 'Decathlon Weighted Rope', 'Puma Training Rope'],
    'Baby Stroller' => ['Chicco Bravo Trio Stroller', 'Graco Modes Click Connect', 'Evenflo Pivot Stroller', 'Mothercare Journey Pram'],
    'Baby Feeding Bottle' => ['Philips Avent Natural Bottle', 'Tommee Tippee Closer to Nature', 'Dr. Brown\'s Anti-Colic Bottle', 'Chicco Well-Being Bottle'],
    'Baby Feeding Set' => ['Munchkin 5-Piece Dining Set', 'Skip Hop Zoo Melamine Set', 'Boon Feeding Set', 'Beaba Silicone Meal Set'],
    'Baby Walker' => ['Chicco Walky Talky Baby Walker', 'Joovy Spoon Walker', 'Fisher-Price Learn with Me Walker', 'Baby Trend Trend Walker'],
    'Car Phone Holder' => ['Baseus Magnetic Car Mount', 'Spigen OneTap Car Mount', 'Ugreen Air Vent Holder', 'Xiaomi Wireless Car Charger'],
    'Car Charger' => ['Baseus 65W Car Charger', 'Anker PowerDrive 2', 'Ugreen 130W Fast Car Charger', 'Xiaomi Dual USB Car Charger'],
    'Car Vacuum Cleaner' => ['Baseus A3 Car Vacuum Cleaner', 'Xiaomi Mijia Portable Vacuum', 'Black+Decker Dustbuster', 'Bosch Handheld Car Vacuum'],
    'Car Air Freshener' => ['Godrej Aer Twist', 'Ambi Pur Car Freshener', 'Baseus Car Air Purifier', 'Glade Car Gel'],
    'Notebook' => ['Moleskine Classic Notebook', 'Matador Premium Notebook', 'Linc Executive Diary', 'Faber-Castell Sketchbook'],
    'Diary' => ['Letts Classic Diary 2026', 'Moleskine Daily Planner', 'Matador Leather Diary', 'Paperblanks Hardcover Diary'],
    'Planner' => ['Moleskine Weekly Planner', 'Erin Condren LifePlanner', 'Matador Student Planner', 'Faber-Castell Task Planner'],
    'Gel Pen Set' => ['Pilot G2 Gel Pens (Pack of 5)', 'Uniball Signo Gel Pen Set', 'Linc Pentonic Gel Set', 'Matador Gel Pen Combo']
];

$products = Product::with('subCategory', 'category')->get();
$faker = \Faker\Factory::create();
$updatedCount = 0;

foreach ($products as $product) {
    $newName = null;
    
    if ($product->subCategory && isset($realNames[$product->subCategory->name])) {
        $options = $realNames[$product->subCategory->name];
        $newName = $faker->randomElement($options) . ' ' . $faker->numberBetween(2023, 2026);
    } else if ($product->category) {
        $newName = $product->category->name . ' Premium Product ' . $faker->lexify('????');
    }

    if ($newName) {
        $product->name = $newName;
        $product->slug = Str::slug($newName . '-' . uniqid());
        $product->save();
        $updatedCount++;
    }
}

echo "Successfully updated names for $updatedCount products!\n";
