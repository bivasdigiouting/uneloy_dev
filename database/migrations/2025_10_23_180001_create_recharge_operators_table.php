<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recharge_operators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recharge_service_id')
                ->constrained('recharge_services')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->string('operator_name');
            $table->string('operator_code')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recharge_operators');
    }
};
