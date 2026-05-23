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
        Schema::create('ecard_seva_product_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inhouse_product_id')->constrained('inhouse_products')->onDelete('cascade');
            $table->decimal('state_member_commission', 8, 2)->default(0);
            $table->decimal('district_member_commission', 8, 2)->default(0);
            $table->decimal('block_member_commission', 8, 2)->default(0);
            $table->decimal('panchayat_member_commission', 8, 2)->default(0);
            $table->decimal('village_member_commission', 8, 2)->default(0);
            $table->decimal('customer_commission', 8, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecard_seva_product_commissions');
    }
};
