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
        Schema::create('product_stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_category_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedInteger('quantity');
            $table->enum('type', ['in', 'out']);
            $table->text('remarks')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('product_category_id');
            $table->index('product_id');
            $table->index(['product_id', 'type']);

            // Foreign keys (safe if tables exist)
            $table->foreign('product_category_id')
                ->references('id')->on('product_categories')
                ->onDelete('cascade');
            $table->foreign('product_id')
                ->references('id')->on('products')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_stock_transactions');
    }
};
