<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateSuperAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('12345678'),
            ]
        );

        // Ensure Super Admin role exists for the 'admin' guard
        $adminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'admin']);

        // Sync role with all 'admin' guard permissions
        $adminPermissions = Permission::where('guard_name', 'admin')->pluck('id', 'id')->all();
        $adminRole->syncPermissions($adminPermissions);

        // Assign the 'admin' guard Super Admin role if not already assigned
        if (! $user->hasRole('Super Admin', 'admin')) {
            $user->assignRole($adminRole);
        }
    }
}
