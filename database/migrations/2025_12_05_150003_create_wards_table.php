<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('wards')) {
            Schema::create('wards', function (Blueprint $table) {
                $table->id();
                $table->string('ward_no');
                $table->foreignId('state_id')->constrained('states')->onDelete('cascade');
                $table->foreignId('district_id')->constrained('districts')->onDelete('cascade');
                $table->foreignId('city_id')->constrained('cities')->onDelete('cascade');
                $table->foreignId('municipality_id')->constrained('municipalities')->onDelete('cascade');
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->timestamps();

                $table->index(['state_id', 'district_id', 'city_id', 'municipality_id']);
                $table->index('status');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('wards');
    }
};
