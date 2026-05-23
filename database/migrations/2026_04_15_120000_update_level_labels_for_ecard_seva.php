<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $departmentRenames = [
            'State Level' => 'State e-Card Seva',
            'District Level' => 'District e-Card Seva',
            'Block Level' => 'Block - e-Card Seva',
            'Panchayat Level' => 'G P M e-Card Seva',
            'Village Level' => 'e-Card Seva',
        ];

        if (Schema::hasTable('departments') && Schema::hasColumn('departments', 'department_name')) {
            foreach ($departmentRenames as $old => $new) {
                DB::table('departments')->where('department_name', $old)->update(['department_name' => $new]);
            }
        }

        $permissionLevelRenames = [
            'State Member' => 'State e-Card Seva',
            'District Level Member' => 'District e-Card Seva',
            'Block Level Member' => 'Block - e-Card Seva',
            'Panchayat Level Member' => 'G P M e-Card Seva',
            'Village Level Member' => 'e-Card Seva',
            'Customer' => 'Member',
        ];

        foreach (['e_card_department_permissions', 'ecard_department_permissions'] as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'department_level')) {
                foreach ($permissionLevelRenames as $old => $new) {
                    DB::table($table)->where('department_level', $old)->update(['department_level' => $new]);
                }
            }
        }

        if (Schema::hasTable('roles') && Schema::hasColumn('roles', 'name')) {
            $roleUpdate = [];
            if (Schema::hasColumn('roles', 'display_name')) {
                $roleUpdate['display_name'] = 'Member';
            }
            if (Schema::hasColumn('roles', 'description')) {
                $roleUpdate['description'] = 'Member level user';
            }
            if (count($roleUpdate) > 0) {
                DB::table('roles')->where('name', 'customer')->update($roleUpdate);
            }
        }
    }

    public function down(): void
    {
        $departmentRenames = [
            'State e-Card Seva' => 'State Level',
            'District e-Card Seva' => 'District Level',
            'Block - e-Card Seva' => 'Block Level',
            'G P M e-Card Seva' => 'Panchayat Level',
            'e-Card Seva' => 'Village Level',
        ];

        if (Schema::hasTable('departments') && Schema::hasColumn('departments', 'department_name')) {
            foreach ($departmentRenames as $new => $old) {
                DB::table('departments')->where('department_name', $new)->update(['department_name' => $old]);
            }
        }

        $permissionLevelRenames = [
            'State e-Card Seva' => 'State Member',
            'District e-Card Seva' => 'District Level Member',
            'Block - e-Card Seva' => 'Block Level Member',
            'G P M e-Card Seva' => 'Panchayat Level Member',
            'e-Card Seva' => 'Village Level Member',
            'Member' => 'Customer',
        ];

        foreach (['e_card_department_permissions', 'ecard_department_permissions'] as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'department_level')) {
                foreach ($permissionLevelRenames as $new => $old) {
                    DB::table($table)->where('department_level', $new)->update(['department_level' => $old]);
                }
            }
        }

        if (Schema::hasTable('roles') && Schema::hasColumn('roles', 'name')) {
            $roleUpdate = [];
            if (Schema::hasColumn('roles', 'display_name')) {
                $roleUpdate['display_name'] = 'Customer';
            }
            if (Schema::hasColumn('roles', 'description')) {
                $roleUpdate['description'] = 'Customer level user';
            }
            if (count($roleUpdate) > 0) {
                DB::table('roles')->where('name', 'customer')->update($roleUpdate);
            }
        }
    }
};

