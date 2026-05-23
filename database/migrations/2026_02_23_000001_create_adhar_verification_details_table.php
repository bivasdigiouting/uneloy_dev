<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('adhar_verification_details', function (Blueprint $table) {
            $table->id();
            $table->string('adhar_no', 20);
            $table->string('adhar_status', 50)->nullable();
            $table->json('adhar_response')->nullable();
            $table->timestamps();

            $table->index('adhar_no');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adhar_verification_details');
    }
};

