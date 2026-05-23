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
        Schema::create('department_commissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('department_id');
            $table->decimal('security_amount', 12, 2)->nullable();
            $table->decimal('plan1_commission_percent', 5, 2)->nullable();
            $table->decimal('plan2_commission_percent', 5, 2)->nullable();
            $table->decimal('service_charge', 12, 2)->nullable();
            $table->decimal('admin_charge', 12, 2)->nullable();
            $table->decimal('tds_charge', 12, 2)->nullable();
            $table->timestamps();

            $table->unique('department_id');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_commissions');
    }
};
