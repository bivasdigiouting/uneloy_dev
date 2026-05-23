<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('level_wise_product_commissions')) {
            return;
        }

        try {
            DB::statement('ALTER TABLE `level_wise_product_commissions` ENGINE=InnoDB');
        } catch (\Throwable $e) {
        }

        try {
            $col = DB::selectOne('SELECT EXTRA FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = "level_wise_product_commissions" AND COLUMN_NAME = "id"');
            $extra = $col->EXTRA ?? '';
            if (stripos($extra, 'auto_increment') === false) {
                try {
                    DB::statement('ALTER TABLE `level_wise_product_commissions` DROP PRIMARY KEY');
                } catch (\Throwable $e2) {
                }
                try {
                    DB::statement('ALTER TABLE `level_wise_product_commissions` MODIFY COLUMN `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
                } catch (\Throwable $e3) {
                }
                try {
                    DB::statement('ALTER TABLE `level_wise_product_commissions` ADD PRIMARY KEY (`id`)');
                } catch (\Throwable $e4) {
                }
            }
        } catch (\Throwable $e) {
            try {
                DB::statement('ALTER TABLE `level_wise_product_commissions` MODIFY COLUMN `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
            } catch (\Throwable $e2) {
            }
        }
    }

    public function down(): void
    {
        // no rollback
    }
};
