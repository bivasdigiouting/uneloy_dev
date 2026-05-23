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
        Schema::create('redeem_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('total_user_points', 15, 2)->default(0);
            $table->decimal('redeem_amount', 15, 2)->default(0);
            $table->decimal('redeem_value', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('redeem_settings');
    }
};
