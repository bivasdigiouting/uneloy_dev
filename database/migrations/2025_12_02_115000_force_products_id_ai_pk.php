<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('products')) {
            return;
        }

        try {
            DB::statement('ALTER TABLE `products` ENGINE=InnoDB');
        } catch (\Throwable $e) {
        }

        // Ensure id column exists
        $hasId = false;
        try {
            $col = DB::selectOne('SELECT 1 AS c FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = "products" AND COLUMN_NAME = "id"');
            $hasId = (bool) ($col->c ?? false);
        } catch (\Throwable $e) {
        }

        if (! $hasId) {
            try {
                DB::statement('ALTER TABLE `products` ADD COLUMN `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST');
            } catch (\Throwable $e) {
            }

            return;
        }

        // Drop existing PK if any to allow AUTO_INCREMENT
        try {
            DB::statement('ALTER TABLE `products` DROP PRIMARY KEY');
        } catch (\Throwable $e) {
        }

        // Convert id to proper type then set AUTO_INCREMENT and PK
        try {
            DB::statement('ALTER TABLE `products` MODIFY COLUMN `id` BIGINT UNSIGNED NOT NULL');
        } catch (\Throwable $e) {
        }
        try {
            DB::statement('ALTER TABLE `products` MODIFY COLUMN `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        } catch (\Throwable $e) {
        }
        try {
            DB::statement('ALTER TABLE `products` ADD PRIMARY KEY (`id`)');
        } catch (\Throwable $e) {
        }
    }

    public function down(): void
    {
        // no rollback
    }
};
