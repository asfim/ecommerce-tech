<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create permissions for 'admin' guard
        $manageProducts = Permission::firstOrCreate(['name' => 'manage-products', 'guard_name' => 'admin']);
        $manageCategories = Permission::firstOrCreate(['name' => 'manage-categories', 'guard_name' => 'admin']);
        $manageBrands = Permission::firstOrCreate(['name' => 'manage-brands', 'guard_name' => 'admin']);
        $manageAttributes = Permission::firstOrCreate(['name' => 'manage-attributes', 'guard_name' => 'admin']);
        $manageOrders = Permission::firstOrCreate(['name' => 'manage-orders', 'guard_name' => 'admin']);
        $manageCoupons = Permission::firstOrCreate(['name' => 'manage-coupons', 'guard_name' => 'admin']);
        $manageReviews = Permission::firstOrCreate(['name' => 'manage-reviews', 'guard_name' => 'admin']);
        $viewReports = Permission::firstOrCreate(['name' => 'view-reports', 'guard_name' => 'admin']);

        // 2. Create roles for 'admin' guard and assign permissions
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'admin']);
        $superAdminRole->givePermissionTo([
            $manageProducts,
            $manageCategories,
            $manageBrands,
            $manageAttributes,
            $manageOrders,
            $manageCoupons,
            $manageReviews,
            $viewReports,
        ]);

        $editorRole = Role::firstOrCreate(['name' => 'Editor', 'guard_name' => 'admin']);
        $editorRole->givePermissionTo([
            $manageProducts,
        ]);

        // 3. Create permissions & roles for 'web' (User) guard
        Permission::firstOrCreate(['name' => 'view-orders', 'guard_name' => 'web']);
        $customerRole = Role::firstOrCreate(['name' => 'Customer', 'guard_name' => 'web']);

        // 4. Assign role to default seeded admin (admin@example.com)
        $admin = Admin::where('email', 'admin@example.com')->first();
        if ($admin) {
            $admin->assignRole($superAdminRole);
        }

        // 5. Assign role to default seeded user (test@example.com)
        $user = User::where('email', 'test@example.com')->first();
        if ($user) {
            $user->assignRole($customerRole);
        }
    }
}
