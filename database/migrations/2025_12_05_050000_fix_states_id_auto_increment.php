<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Ensure `states.id` is AUTO_INCREMENT primary key
     */
    public function up(): void
    {
        try {
            DB::statement('ALTER TABLE `states` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        } catch (\Throwable $e) {
        }

        try {
            DB::statement('ALTER TABLE `states` ADD PRIMARY KEY (`id`)');
        } catch (\Throwable $e) {
        }
    }

    /**
     * Revert `states.id` to non-auto increment (best-effort)
     */
    public function down(): void
    {
        try {
            DB::statement('ALTER TABLE `states` MODIFY `id` BIGINT UNSIGNED NOT NULL');
        } catch (\Throwable $e) {
        }
    }
};
