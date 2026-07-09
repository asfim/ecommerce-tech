<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Coupon::create([
            'code' => 'SAVE10',
            'type' => 'percent',
            'value' => 10.00,
            'min_order_amount' => 0.00,
            'is_active' => true,
            'expires_at' => now()->addYear(),
        ]);

        Coupon::create([
            'code' => 'FLAT50',
            'type' => 'fixed',
            'value' => 50.00,
            'min_order_amount' => 200.00,
            'is_active' => true,
            'expires_at' => now()->addYear(),
        ]);
    }
}
