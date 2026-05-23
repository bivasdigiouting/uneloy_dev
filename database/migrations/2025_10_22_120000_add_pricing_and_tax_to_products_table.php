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
            if (! Schema::hasColumn('products', 'mrp')) {
                $table->decimal('mrp', 10, 2)->nullable()->after('price');
            }
            if (! Schema::hasColumn('products', 'distributor_price')) {
                $table->decimal('distributor_price', 10, 2)->nullable()->after('mrp');
            }
            if (! Schema::hasColumn('products', 'gst_tax_id')) {
                $table->unsignedBigInteger('gst_tax_id')->nullable()->after('brand');
                $table->index('gst_tax_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'mrp')) {
                $table->dropColumn('mrp');
            }
            if (Schema::hasColumn('products', 'distributor_price')) {
                $table->dropColumn('distributor_price');
            }
            if (Schema::hasColumn('products', 'gst_tax_id')) {
                $table->dropIndex(['gst_tax_id']);
                $table->dropColumn('gst_tax_id');
            }
        });
    }
};
