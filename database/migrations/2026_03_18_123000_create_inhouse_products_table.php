<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inhouse_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inhouse_product_category_id');
            $table->unsignedBigInteger('gst_tax_id')->nullable();
            $table->string('name');
            $table->string('sku', 64)->unique();
            $table->decimal('mrp', 10, 2)->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->string('thumbnail')->nullable();
            $table->json('images')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['inhouse_product_category_id', 'is_active'], 'inhouse_products_cat_active_idx');

            $table->foreign('inhouse_product_category_id')
                ->references('id')
                ->on('inhouse_product_categories')
                ->onDelete('cascade');
            $table->foreign('gst_tax_id')
                ->references('id')
                ->on('gst_taxes')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inhouse_products');
    }
};
