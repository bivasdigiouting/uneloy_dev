<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Only applicable for MySQL / MariaDB
        try {
            $driver = DB::connection()->getDriverName();
        } catch (\Throwable $e) {
            $driver = null;
        }

        if ($driver !== 'mysql' && $driver !== 'mariadb') {
            return;
        }

        // 1) Restore PRIMARY KEY + AUTO_INCREMENT on numeric `id` columns across all tables
        $rows = DB::select(<<<'SQL'
            SELECT TABLE_NAME, COLUMN_TYPE, EXTRA, COLUMN_KEY, DATA_TYPE
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE() AND COLUMN_NAME = 'id'
        SQL);

        foreach ($rows as $row) {
            $table = $row->TABLE_NAME;
            $dataType = strtolower((string) $row->DATA_TYPE);
            $extra = strtolower((string) $row->EXTRA);
            $columnKey = strtoupper((string) $row->COLUMN_KEY);

            // Skip non-numeric id types (e.g., string ids)
            if (! in_array($dataType, ['bigint', 'int', 'mediumint', 'smallint', 'tinyint'], true)) {
                continue;
            }

            // Ensure AUTO_INCREMENT on id
            if (strpos($extra, 'auto_increment') === false) {
                try {
                    DB::statement("ALTER TABLE `{$table}` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT");
                } catch (\Throwable $e) {
                    // ignore
                }
            }

            // Ensure PRIMARY KEY on id if none exists
            try {
                $pkCount = DB::selectOne(
                    'SELECT COUNT(*) as cnt FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND CONSTRAINT_TYPE = \"PRIMARY KEY\"',
                    [$table]
                );
                if ((int) ($pkCount->cnt ?? 0) === 0) {
                    DB::statement("ALTER TABLE `{$table}` ADD PRIMARY KEY (`id`)");
                }
            } catch (\Throwable $e) {
                // ignore
            }
        }

        // 2) Restore composite PRIMARY KEYs for Spatie permission pivot tables, if missing
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $teamsEnabled = (bool) config('permission.teams');

        if (is_array($tableNames) && is_array($columnNames)) {
            // role_has_permissions: PRIMARY KEY (permission_id, role_id)
            if (! empty($tableNames['role_has_permissions']) && Schema::hasTable($tableNames['role_has_permissions'])) {
                try {
                    $pk = DB::selectOne('SHOW KEYS FROM `'.$tableNames['role_has_permissions'].'` WHERE Key_name = "PRIMARY"');
                    if (! $pk) {
                        DB::statement('ALTER TABLE `'.$tableNames['role_has_permissions'].'` ADD PRIMARY KEY (`'.$columnNames['permission_pivot_key'].'`, `'.$columnNames['role_pivot_key'].'`)');
                    }
                } catch (\Throwable $e) {
                    // ignore
                }
            }

            // model_has_permissions: PRIMARY KEY depends on teams setting
            if (! empty($tableNames['model_has_permissions']) && Schema::hasTable($tableNames['model_has_permissions'])) {
                try {
                    $pk = DB::selectOne('SHOW KEYS FROM `'.$tableNames['model_has_permissions'].'` WHERE Key_name = "PRIMARY"');
                    if (! $pk) {
                        $modelKey = $columnNames['model_morph_key'];
                        $permKey = $columnNames['permission_pivot_key'] ?? 'permission_id';
                        if ($teamsEnabled) {
                            $teamFk = $columnNames['team_foreign_key'];
                            DB::statement('ALTER TABLE `'.$tableNames['model_has_permissions'].'` ADD PRIMARY KEY (`'.$teamFk.'`, `'.$permKey.'`, `'.$modelKey.'`, `model_type`)');
                        } else {
                            DB::statement('ALTER TABLE `'.$tableNames['model_has_permissions'].'` ADD PRIMARY KEY (`'.$permKey.'`, `'.$modelKey.'`, `model_type`)');
                        }
                    }
                } catch (\Throwable $e) {
                    // ignore
                }
            }

            // model_has_roles: PRIMARY KEY depends on teams setting
            if (! empty($tableNames['model_has_roles']) && Schema::hasTable($tableNames['model_has_roles'])) {
                try {
                    $pk = DB::selectOne('SHOW KEYS FROM `'.$tableNames['model_has_roles'].'` WHERE Key_name = "PRIMARY"');
                    if (! $pk) {
                        $modelKey = $columnNames['model_morph_key'];
                        $roleKey = $columnNames['role_pivot_key'] ?? 'role_id';
                        if ($teamsEnabled) {
                            $teamFk = $columnNames['team_foreign_key'];
                            DB::statement('ALTER TABLE `'.$tableNames['model_has_roles'].'` ADD PRIMARY KEY (`'.$teamFk.'`, `'.$roleKey.'`, `'.$modelKey.'`, `model_type`)');
                        } else {
                            DB::statement('ALTER TABLE `'.$tableNames['model_has_roles'].'` ADD PRIMARY KEY (`'.$roleKey.'`, `'.$modelKey.'`, `model_type`)');
                        }
                    }
                } catch (\Throwable $e) {
                    // ignore
                }
            }
        }
    }

    public function down(): void
    {
        // No-op: restoring keys is a corrective migration; do not attempt to drop them automatically.
    }
};
