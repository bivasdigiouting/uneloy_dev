<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ecard_product_stock_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ecard_registration_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('product_name');
            $table->decimal('quantity', 12, 2)->default(0);
            $table->string('unit')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
            $table->string('remark')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->unsignedBigInteger('approved_by_id')->nullable();
            $table->timestamps();
            $table->index('ecard_registration_id');
            $table->index('product_id');
        });

        Schema::create('ecard_ar_product_stock_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ecard_registration_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('product_name');
            $table->decimal('quantity', 12, 2)->default(0);
            $table->string('unit')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
            $table->string('remark')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->unsignedBigInteger('approved_by_id')->nullable();
            $table->timestamps();
            $table->index('ecard_registration_id');
            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ecard_ar_product_stock_requests');
        Schema::dropIfExists('ecard_product_stock_requests');
    }
};
