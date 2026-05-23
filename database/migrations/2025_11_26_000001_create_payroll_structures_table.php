<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payroll_structures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('department_id');
            $table->string('title')->nullable();
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
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('department_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_structures');
    }
};
