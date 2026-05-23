<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('first_recharge_plan_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('first_recharge_plan_id')
                ->constrained('first_recharge_plans')
                ->cascadeOnDelete();
            $table->foreignId('department_id')
                ->constrained('departments')
                ->cascadeOnDelete();
            $table->decimal('commission_amount', 10, 2)->default(0);
            $table->timestamps();

            $table->unique(['first_recharge_plan_id', 'department_id'], 'frpc_plan_department_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('first_recharge_plan_commissions');
    }
};
