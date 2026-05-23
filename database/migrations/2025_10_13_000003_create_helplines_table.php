<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('helplines', function (Blueprint $table) {
            $table->id();
            $table->string('helpline_name');
            $table->string('helpline_number');
            $table->foreignId('state_id')->constrained('states')->onDelete('cascade');
            $table->foreignId('district_id')->constrained('districts')->onDelete('cascade');
            $table->foreignId('city_id')->constrained('cities')->onDelete('cascade');
            $table->string('icon')->nullable();
            $table->timestamps();

            $table->index(['state_id', 'district_id', 'city_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('helplines');
    }
};
