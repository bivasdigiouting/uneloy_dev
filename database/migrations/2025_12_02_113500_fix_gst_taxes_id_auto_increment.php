<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('gst_taxes')) {
            Schema::create('gst_taxes', function (Blueprint $table) {
                $table->id();
                $table->string('tax_name');
                $table->decimal('rate_percent', 5, 2);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });

            return;
        }

        if (! Schema::hasColumn('gst_taxes', 'id')) {
            Schema::table('gst_taxes', function (Blueprint $table) {
                $table->id();
            });
        } else {
            try {
                DB::statement('ALTER TABLE `gst_taxes` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
            } catch (\Throwable $e) {
                // ignore
            }
            try {
                // Add primary key if missing
                $hasPrimary = DB::selectOne('SELECT COUNT(*) AS c FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = "gst_taxes" AND CONSTRAINT_TYPE = "PRIMARY KEY"');
                if (($hasPrimary->c ?? 0) == 0) {
                    DB::statement('ALTER TABLE `gst_taxes` ADD PRIMARY KEY (`id`)');
                }
            } catch (\Throwable $e) {
                // ignore
            }
        }
    }

    public function down(): void
    {
        // No-op: keeping auto-increment primary key is safe
    }
};
