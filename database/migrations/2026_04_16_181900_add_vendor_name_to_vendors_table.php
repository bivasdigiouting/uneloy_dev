<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('vendors')) {
            return;
        }

        if (Schema::hasColumn('vendors', 'vendor_name')) {
            return;
        }

        Schema::table('vendors', function (Blueprint $table) {
            if (Schema::hasColumn('vendors', 'vendor_number')) {
                $table->string('vendor_name')->nullable()->after('vendor_number');
            } else {
                $table->string('vendor_name')->nullable()->after('id');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('vendors')) {
            return;
        }

        if (! Schema::hasColumn('vendors', 'vendor_name')) {
            return;
        }

        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn('vendor_name');
        });
    }
};

