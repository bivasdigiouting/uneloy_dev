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
        Schema::create('eps_level_user_distributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('distribution_id')->constrained('eps_level_distributions')->onDelete('cascade');
            $table->enum('level_type', ['state_level', 'district_level', 'city_level', 'block_level', 'panchayat_level', 'village_level']);
            $table->unsignedBigInteger('registration_id')->nullable();
            $table->decimal('amount', 12, 2);
            $table->timestamps();

            $table->foreign('registration_id')->references('id')->on('registrations')->onDelete('set null');
            $table->index(['level_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eps_level_user_distributions');
    }
};
