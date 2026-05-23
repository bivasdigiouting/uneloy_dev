<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('first_recharge_plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_name')->unique();
            $table->decimal('plan_value', 10, 2)->default(0);
            $table->decimal('bonus_value', 10, 2)->default(0);
            $table->decimal('total_value', 10, 2)->default(0);
            $table->decimal('benefit_amount', 10, 2)->default(0);
            $table->unsignedInteger('benefit_duration_years')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('first_recharge_plans');
    }
};
