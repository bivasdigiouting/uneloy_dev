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
        // Ensure a clean slate if a partial migration left the table behind
        Schema::dropIfExists('product_stock_transfers');

        Schema::create('product_stock_transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_category_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('quantity', 12, 2);
            // From level
            $table->enum('from_level_type', ['state', 'district', 'city', 'panchayat', 'village']);
            $table->unsignedBigInteger('from_state_id')->nullable();
            $table->unsignedBigInteger('from_district_id')->nullable();
            $table->unsignedBigInteger('from_city_id')->nullable();
            $table->string('from_panchayat_name')->nullable();
            $table->string('from_village_name')->nullable();
            // To level
            $table->enum('to_level_type', ['state', 'district', 'city', 'panchayat', 'village']);
            $table->unsignedBigInteger('to_state_id')->nullable();
            $table->unsignedBigInteger('to_district_id')->nullable();
            $table->unsignedBigInteger('to_city_id')->nullable();
            $table->string('to_panchayat_name')->nullable();
            $table->string('to_village_name')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index(['product_category_id', 'product_id']);
            $table->index(['from_level_type', 'to_level_type']);
            $table->index(['from_state_id', 'from_district_id', 'from_city_id'], 'pst_from_loc_idx');
            $table->index(['to_state_id', 'to_district_id', 'to_city_id'], 'pst_to_loc_idx');

            $table->foreign('product_category_id')->references('id')->on('product_categories')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('from_state_id')->references('id')->on('states')->onDelete('set null');
            $table->foreign('from_district_id')->references('id')->on('districts')->onDelete('set null');
            $table->foreign('from_city_id')->references('id')->on('cities')->onDelete('set null');
            $table->foreign('to_state_id')->references('id')->on('states')->onDelete('set null');
            $table->foreign('to_district_id')->references('id')->on('districts')->onDelete('set null');
            $table->foreign('to_city_id')->references('id')->on('cities')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_stock_transfers');
    }
};
