<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminTableSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}
