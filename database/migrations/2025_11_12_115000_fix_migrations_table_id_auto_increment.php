<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        try {
            $driver = DB::connection()->getDriverName();
        } catch (\Throwable $e) {
            $driver = null;
        }

        if ($driver !== 'mysql' && $driver !== 'mariadb') {
            return;
        }

        try {
            DB::statement('ALTER TABLE `migrations` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        } catch (\Throwable $e) {
            // ignore
        }

        try {
            DB::statement('ALTER TABLE `migrations` ADD PRIMARY KEY (`id`)');
        } catch (\Throwable $e) {
            // ignore (already has primary key)
        }
    }

    public function down(): void
    {
        // No-op
    }
};
