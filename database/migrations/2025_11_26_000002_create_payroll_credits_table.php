<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_credits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_id');
            $table->unsignedBigInteger('department_id');
            $table->unsignedTinyInteger('month'); // 1-12
            $table->unsignedSmallInteger('year');
            // Earnings
            $table->decimal('basic', 10, 2)->default(0);
            $table->decimal('hra', 10, 2)->default(0);
            $table->decimal('da', 10, 2)->default(0);
            $table->decimal('ta', 10, 2)->default(0);
            $table->decimal('medical', 10, 2)->default(0);
            $table->decimal('special_allowance', 10, 2)->default(0);
            $table->decimal('bonus', 10, 2)->default(0);
            $table->decimal('eic', 10, 2)->default(0);
            // Deductions
            $table->decimal('pf', 10, 2)->default(0);
            $table->decimal('loan', 10, 2)->default(0);
            $table->decimal('esic', 10, 2)->default(0);
            // Totals
            $table->decimal('gross_earnings', 12, 2)->default(0);
            $table->decimal('total_deductions', 12, 2)->default(0);
            $table->decimal('net_pay', 12, 2)->default(0);
            $table->string('status')->default('processed');
            $table->timestamp('credited_at')->nullable();
            $table->timestamps();

            $table->index(['staff_id', 'department_id']);
            $table->index(['month', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_credits');
    }
};
