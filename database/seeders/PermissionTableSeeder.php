<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'product-list',
            'product-create',
            'product-edit',
            'product-delete',
            // Payroll permissions
            'payroll-structure-list',
            'payroll-structure-create',
            'payroll-structure-edit',
            'salary-credit-list',
            'salary-credit-create',
        ];

        // Ensure permissions exist for both 'web' and 'admin' guards
        foreach ($permissions as $permission) {
            foreach (['web', 'admin'] as $guard) {
                if (! Permission::where('name', $permission)->where('guard_name', $guard)->exists()) {
                    Permission::create(['name' => $permission, 'guard_name' => $guard]);
                }
            }
        }
    }
}
