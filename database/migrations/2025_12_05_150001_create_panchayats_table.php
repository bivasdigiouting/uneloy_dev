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
        if (! Schema::hasTable('panchayats')) {
            Schema::create('panchayats', function (Blueprint $table) {
                $table->id();
                $table->string('panchayat_name')->unique();
                $table->foreignId('state_id')->constrained('states')->onDelete('cascade');
                $table->foreignId('district_id')->constrained('districts')->onDelete('cascade');
                $table->foreignId('city_id')->constrained('cities')->onDelete('cascade');
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->timestamps();

                $table->index(['state_id', 'district_id', 'city_id']);
                $table->index('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('panchayats');
    }
};
