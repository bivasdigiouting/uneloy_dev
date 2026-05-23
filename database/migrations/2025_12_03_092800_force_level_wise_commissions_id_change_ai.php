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
            DB::statement('ALTER TABLE `level_wise_product_commissions` CHANGE `id` `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        } catch (\Throwable $e) {
        }
    }

    public function down(): void {}
};
