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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();

            // Personal Details Section
            $table->string('staff_name');
            $table->string('profile_image')->nullable();
            $table->date('date_of_joining');
            $table->date('date_of_birth');
            $table->unsignedBigInteger('designation_id');
            $table->enum('gender', ['Male', 'Female', 'Other']);

            // Contact Details Section
            $table->text('address_1');
            $table->text('address_2')->nullable();
            $table->string('state');
            $table->string('district');
            $table->string('city');
            $table->string('pincode', 6);
            $table->string('mobile_no', 15);
            $table->string('email_id')->unique();
            $table->string('location')->nullable();

            // Bank Details Section
            $table->string('ifsc_code', 11);
            $table->string('bank_name');
            $table->string('branch_name');
            $table->string('account_no');
            $table->string('pan_no', 10);
            $table->string('aadhar_no', 12);
            $table->decimal('salary', 10, 2);

            // Login Details Section
            $table->string('user_id')->unique();
            $table->string('password');
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Foreign key constraint
            $table->foreign('designation_id')->references('id')->on('designations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
