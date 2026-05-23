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
        Schema::create('ecard_registrations', function (Blueprint $table) {
            $table->id();

            // Business Details
            $table->string('business_name')->nullable();
            $table->string('business_mobile')->nullable();
            $table->string('business_whatsapp')->nullable();
            $table->string('business_gmail')->nullable();
            $table->text('business_address')->nullable();
            $table->string('business_gst')->nullable();
            $table->string('business_upi')->nullable();
            $table->text('business_location_map')->nullable();

            // Personal Details
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('blood_group')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->enum('marital_status', ['Single', 'Married', 'Divorced', 'Widowed'])->nullable();

            // Contact Details
            $table->text('current_address')->nullable();
            $table->text('permanent_address')->nullable();
            $table->string('nationality')->nullable();
            $table->string('state')->nullable();
            $table->string('district')->nullable();
            $table->string('city')->nullable();
            $table->string('pin_code')->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('email_id')->nullable();
            $table->string('gmail_id')->nullable();
            $table->text('live_location_map')->nullable();

            // Bank Details
            $table->string('ifsc_code')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('account_no')->nullable();
            $table->string('pan_no')->nullable();
            $table->string('aadhaar_no')->nullable();

            // Qualification & Experience Details
            $table->string('last_qualification')->nullable();
            $table->string('work_type')->nullable();
            $table->string('work_experience')->nullable();

            $table->enum('status', ['active', 'inactive', 'pending', 'rejected'])->default('active');
            $table->decimal('wallet_balance', 10, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecard_registrations');
    }
};
