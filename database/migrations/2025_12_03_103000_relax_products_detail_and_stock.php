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

        try {
            DB::statement('ALTER TABLE `products` MODIFY COLUMN `detail` TEXT NULL');
        } catch (\Throwable $e) {
        }
        try {
            DB::statement('ALTER TABLE `products` MODIFY COLUMN `stock` INT NULL DEFAULT 0');
        } catch (\Throwable $e) {
        }
    }

    public function down(): void
    {
        // no rollback
    }
};
