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
        $manageProducts = Permission::create(['name' => 'manage-products', 'guard_name' => 'admin']);
        $manageCategories = Permission::create(['name' => 'manage-categories', 'guard_name' => 'admin']);
        $manageBrands = Permission::create(['name' => 'manage-brands', 'guard_name' => 'admin']);
        $manageAttributes = Permission::create(['name' => 'manage-attributes', 'guard_name' => 'admin']);

        // 2. Create roles for 'admin' guard and assign permissions
        $superAdminRole = Role::create(['name' => 'Super Admin', 'guard_name' => 'admin']);
        $superAdminRole->givePermissionTo([
            $manageProducts,
            $manageCategories,
            $manageBrands,
            $manageAttributes,
        ]);

        $editorRole = Role::create(['name' => 'Editor', 'guard_name' => 'admin']);
        $editorRole->givePermissionTo([
            $manageProducts,
        ]);

        // 3. Create permissions & roles for 'web' (User) guard
        Permission::create(['name' => 'view-orders', 'guard_name' => 'web']);
        $customerRole = Role::create(['name' => 'Customer', 'guard_name' => 'web']);

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
