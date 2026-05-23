<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('gst_taxes')) {
            return;
        }

        try {
            DB::statement('ALTER TABLE `gst_taxes` ENGINE=InnoDB');
        } catch (\Throwable $e) {
        }

        try {
            DB::statement('ALTER TABLE `gst_taxes` MODIFY COLUMN `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        } catch (\Throwable $e) {
        }

        try {
            DB::statement('ALTER TABLE `gst_taxes` ADD PRIMARY KEY (`id`)');
        } catch (\Throwable $e) {
        }
    }

    public function down(): void
    {
        // no rollback for PK repair
    }
};
