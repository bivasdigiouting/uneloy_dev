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
        Schema::create('security_amount_masters', function (Blueprint $table) {
            $table->id();
            $table->decimal('state_level_amount', 10, 2)->default(0.00);
            $table->decimal('district_level_amount', 10, 2)->default(0.00);
            $table->decimal('block_level_amount', 10, 2)->default(0.00);
            $table->decimal('panchayat_level_amount', 10, 2)->default(0.00);
            $table->decimal('village_level_amount', 10, 2)->default(0.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_amount_masters');
    }
};
