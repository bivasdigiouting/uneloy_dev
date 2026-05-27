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
        Schema::create('blood_donor_messages', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('blood_donor_request_id');

            // simple message log
            $table->unsignedBigInteger('sender_user_id')->nullable();
            $table->string('sender_name', 200)->nullable();
            $table->string('sender_mobile_no', 30)->nullable();

            $table->text('message')->nullable();

            $table->timestamps();

            $table->foreign('blood_donor_request_id')->references('id')->on('blood_donor_requests')->onDelete('cascade');
            $table->index(['blood_donor_request_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_donor_messages');
    }
};
