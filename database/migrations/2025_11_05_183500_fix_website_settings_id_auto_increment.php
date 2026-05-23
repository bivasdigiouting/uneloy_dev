<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ensure website_settings.id has a PRIMARY KEY
        try {
            DB::statement('ALTER TABLE `website_settings` ADD PRIMARY KEY (`id`)');
        } catch (\Exception $e) {
            // Ignore if PK already exists
        }

        // Then set AUTO_INCREMENT on id
        DB::statement('ALTER TABLE `website_settings` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
    }

    public function down(): void
    {
        // Revert AUTO_INCREMENT and primary key if needed (best-effort)
        try {
            DB::statement('ALTER TABLE `website_settings` DROP PRIMARY KEY');
        } catch (\Exception $e) {
            // ignore if not present
        }
        // Remove AUTO_INCREMENT flag (cannot set NULL default; leave as NOT NULL without auto-increment)
        DB::statement('ALTER TABLE `website_settings` MODIFY `id` BIGINT UNSIGNED NOT NULL');
    }
};
