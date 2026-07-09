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
        $permissions = [];
        $modules = [
            'products' => ['view', 'create', 'edit', 'delete'],
            'categories' => ['view', 'create', 'edit', 'delete'],
            'subcategories' => ['view', 'create', 'edit', 'delete'],
            'brands' => ['view', 'create', 'edit', 'delete'],
            'attributes' => ['view', 'create', 'edit', 'delete'],
            'orders' => ['view', 'edit', 'delete'],
            'coupons' => ['view', 'create', 'edit', 'delete'],
            'reviews' => ['view', 'delete'],
            'blogs' => ['view', 'create', 'edit', 'delete'],
            'staffs' => ['view', 'create', 'edit', 'delete'],
            'customers' => ['view', 'create', 'edit', 'delete'],
            'roles' => ['view', 'create', 'edit', 'delete'],
            'permissions' => ['view', 'create'],
            'activitylogs' => ['view'],
        ];

        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                $name = "{$action}-{$module}";
                $permissions[] = Permission::firstOrCreate(['name' => $name, 'guard_name' => 'admin']);
            }
        }

        $permissions[] = Permission::firstOrCreate(['name' => 'view-reports', 'guard_name' => 'admin']);
        $permissions[] = Permission::firstOrCreate(['name' => 'manage-homepage-settings', 'guard_name' => 'admin']);
        $permissions[] = Permission::firstOrCreate(['name' => 'manage-company-settings', 'guard_name' => 'admin']);

        // 2. Create roles for 'admin' guard and assign permissions
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'admin']);
        $superAdminRole->syncPermissions($permissions);

        $editorRole = Role::firstOrCreate(['name' => 'Editor', 'guard_name' => 'admin']);
        $editorPermissions = Permission::where('guard_name', 'admin')
            ->where(function ($query) {
                $query->where('name', 'like', '%-products')
                    ->orWhere('name', 'like', '%-categories')
                    ->orWhere('name', 'like', '%-subcategories')
                    ->orWhere('name', 'like', '%-brands')
                    ->orWhere('name', 'like', '%-attributes')
                    ->orWhere('name', 'like', '%-blogs');
            })
            ->where('name', 'not like', 'delete-%')
            ->get();
        $editorRole->syncPermissions($editorPermissions);

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
