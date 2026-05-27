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
        Schema::create('blood_donor_requests', function (Blueprint $table) {
            $table->id();

            // who requested blood help
            $table->unsignedBigInteger('requester_user_id')->nullable();
            $table->string('requester_name', 200)->nullable();
            $table->string('requester_mobile_no', 30)->nullable();

            // which donor
            $table->unsignedBigInteger('donor_id')->nullable();
            $table->string('donor_name', 200)->nullable();
            $table->string('donor_mobile_no', 30)->nullable();
            $table->string('blood_group', 20)->nullable();

            $table->string('status', 50)->default('pending'); // pending|accepted|rejected|completed

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['donor_id', 'status']);
            $table->index(['requester_mobile_no']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_donor_requests');
    }
};
