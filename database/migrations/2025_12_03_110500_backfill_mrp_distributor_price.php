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
            DB::statement('UPDATE `products` SET `mrp` = COALESCE(`mrp`, `price`, 0)');
        } catch (\Throwable $e) {
        }
        try {
            DB::statement('UPDATE `products` SET `distributor_price` = COALESCE(`distributor_price`, `price`, 0)');
        } catch (\Throwable $e) {
        }
    }

    public function down(): void
    {
        // No rollback for backfill
    }
};
