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
        if (Schema::hasTable('product_categories') && Schema::hasColumn('product_categories', 'commission')) {
            Schema::table('product_categories', function (Blueprint $table) {
                $table->dropColumn('commission');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_categories', function (Blueprint $table) {
            $table->decimal('commission', 5, 2)->default(0.00)->after('sequence');
        });
    }
};
