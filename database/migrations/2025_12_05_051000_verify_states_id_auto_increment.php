<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        try {
            $col = DB::select("SHOW COLUMNS FROM `states` LIKE 'id'");
            $needsFix = true;
            if (! empty($col)) {
                $info = (array) $col[0];
                $extra = strtolower((string) ($info['Extra'] ?? $info['extra'] ?? ''));
                $type = strtolower((string) ($info['Type'] ?? $info['type'] ?? ''));
                $key = strtolower((string) ($info['Key'] ?? $info['key'] ?? ''));
                if (Str::contains($extra, 'auto_increment') && $key === 'pri' && (Str::contains($type, 'int'))) {
                    $needsFix = false;
                }
            }

            if (! $needsFix) {
                return;
            }

            try {
                DB::statement('ALTER TABLE `states` MODIFY `id` BIGINT UNSIGNED NOT NULL');
            } catch (\Throwable $e) {
            }
            try {
                DB::statement('ALTER TABLE `states` DROP PRIMARY KEY');
            } catch (\Throwable $e) {
            }
            try {
                DB::statement('ALTER TABLE `states` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
            } catch (\Throwable $e) {
            }
            try {
                DB::statement('ALTER TABLE `states` ADD PRIMARY KEY (`id`)');
            } catch (\Throwable $e) {
            }

            // Verify again; if still not fixed, recreate table (non-destructive attempt)
            $col2 = DB::select("SHOW COLUMNS FROM `states` LIKE 'id'");
            $fixed = false;
            if (! empty($col2)) {
                $info2 = (array) $col2[0];
                $extra2 = strtolower((string) ($info2['Extra'] ?? $info2['extra'] ?? ''));
                $type2 = strtolower((string) ($info2['Type'] ?? $info2['type'] ?? ''));
                $key2 = strtolower((string) ($info2['Key'] ?? $info2['key'] ?? ''));
                $fixed = Str::contains($extra2, 'auto_increment') && $key2 === 'pri' && (Str::contains($type2, 'int'));
            }

            if (! $fixed) {
                $suffix = date('YmdHis');
                try {
                    DB::statement("RENAME TABLE `states` TO `states_backup_${suffix}`");
                } catch (\Throwable $e) {
                }
                try {
                    DB::statement('CREATE TABLE `states` (
                        `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                        `state_name` VARCHAR(255) NOT NULL,
                        `status` ENUM("active","inactive") NOT NULL DEFAULT "active",
                        `created_at` TIMESTAMP NULL DEFAULT NULL,
                        `updated_at` TIMESTAMP NULL DEFAULT NULL,
                        PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
                } catch (\Throwable $e) {
                }
            }
        } catch (\Throwable $e) {
        }
    }

    public function down(): void
    {
        // no-op
    }
};
