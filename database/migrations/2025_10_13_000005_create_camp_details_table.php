<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('camp_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('camp_id')->constrained('camps')->onDelete('cascade');
            $table->foreignId('state_id')->constrained('states')->onDelete('cascade');
            $table->foreignId('district_id')->constrained('districts')->onDelete('cascade');
            $table->foreignId('city_id')->constrained('cities')->onDelete('cascade');
            $table->string('title');
            $table->unsignedInteger('capacity')->default(0);
            $table->date('from_date');
            $table->date('to_date');
            $table->string('banner')->nullable();
            $table->text('short_description')->nullable();
            $table->timestamps();

            $table->index(['camp_id', 'state_id', 'district_id', 'city_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('camp_details');
    }
};
