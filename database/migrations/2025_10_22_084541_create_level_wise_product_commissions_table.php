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
        Schema::create('level_wise_product_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_category_id')->constrained('product_categories')->onDelete('cascade');
            $table->decimal('state_member_commission', 5, 2)->default(0.00);
            $table->decimal('district_member_commission', 5, 2)->default(0.00);
            $table->decimal('block_member_commission', 5, 2)->default(0.00);
            $table->decimal('panchayat_member_commission', 5, 2)->default(0.00);
            $table->decimal('village_member_commission', 5, 2)->default(0.00);
            $table->decimal('customer_commission', 5, 2)->default(0.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('product_category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('level_wise_product_commissions');
    }
};
