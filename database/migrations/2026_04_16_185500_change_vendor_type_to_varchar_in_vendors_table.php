<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('vendors')) {
            return;
        }

        if (! Schema::hasColumn('vendors', 'vendor_type')) {
            return;
        }

        try {
            DB::statement('ALTER TABLE `vendors` MODIFY `vendor_type` VARCHAR(255) NULL');
        } catch (\Throwable $e) {
        }
    }

    public function down(): void
    {
    }
};

