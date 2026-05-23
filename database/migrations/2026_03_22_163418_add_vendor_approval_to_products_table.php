<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'vendor_id')) {
                $table->unsignedBigInteger('vendor_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('products', 'admin_status')) {
                $table->enum('admin_status', ['pending', 'approved', 'rejected'])->default('approved')->after('vendor_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'vendor_id')) {
                $table->dropColumn('vendor_id');
            }
            if (Schema::hasColumn('products', 'admin_status')) {
                $table->dropColumn('admin_status');
            }
        });
    }
};
