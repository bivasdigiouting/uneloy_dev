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
            $table->text('description')->nullable()->after('detail');
            $table->string('sku')->unique()->nullable()->after('stock');
            $table->string('category')->nullable()->after('sku');
            $table->string('brand')->nullable()->after('category');
            $table->json('images')->nullable()->after('image');
            $table->decimal('weight', 8, 2)->nullable()->after('images');
            $table->json('dimensions')->nullable()->after('weight');
            $table->boolean('is_active')->default(true)->after('dimensions');
            $table->boolean('is_featured')->default(false)->after('is_active');
            $table->json('attributes')->nullable()->after('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'sku',
                'category',
                'brand',
                'images',
                'weight',
                'dimensions',
                'is_active',
                'is_featured',
                'attributes',
            ]);
        });
    }
};
