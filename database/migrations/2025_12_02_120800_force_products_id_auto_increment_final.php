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

        // If id column exists but is not AUTO_INCREMENT, force it
        try {
            $col = DB::selectOne('SELECT EXTRA FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = "products" AND COLUMN_NAME = "id"');
            $extra = $col->EXTRA ?? '';
            if (stripos($extra, 'auto_increment') === false) {
                DB::statement('ALTER TABLE `products` MODIFY COLUMN `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
            }
        } catch (\Throwable $e) {
            // Fallback: attempt direct modify regardless
            try {
                DB::statement('ALTER TABLE `products` MODIFY COLUMN `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
            } catch (\Throwable $e2) {
            }
        }
    }

    public function down(): void
    {
        // no rollback
    }
};
