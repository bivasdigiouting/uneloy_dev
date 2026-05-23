<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('adhar_temp_data', function (Blueprint $table) {
            $table->id();
            $table->string('adhar_no', 20)->unique();
            $table->string('verification_id')->nullable();
            $table->string('reference_id')->nullable();
            $table->text('url')->nullable();
            $table->string('orderid')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adhar_temp_data');
    }
};

