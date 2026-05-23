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
        Schema::create('advertisement_requests', function (Blueprint $table) {
            $table->id();
            $table->string('campaign_name');
            $table->unsignedBigInteger('business_category_id')->nullable();
            $table->unsignedBigInteger('lead_id')->nullable();
            $table->string('location')->nullable();
            $table->enum('advertisement_type', ['Banner', 'Poster', 'Social Media Link']);
            $table->date('from_date');
            $table->date('to_date');
            $table->enum('requester_type', ['user', 'vendor']);
            $table->enum('request_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('requester_id')->nullable();
            $table->string('requester_name')->nullable();
            $table->string('requester_email')->nullable();
            $table->timestamps();

            // Indexes for faster filtering
            $table->index(['from_date', 'to_date']);
            $table->index(['requester_type', 'request_status']);
            $table->index(['business_category_id', 'lead_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisement_requests');
    }
};
