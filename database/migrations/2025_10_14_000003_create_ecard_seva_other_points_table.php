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
        if (! Schema::hasTable('ecard_seva_other_points')) {
            Schema::create('ecard_seva_other_points', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('points')->default(0);

                // Approved info
                $table->string('approved_id_no')->nullable();
                $table->string('approved_name')->nullable();
                $table->timestamp('approved_date')->nullable();

                // Donor/User info
                $table->string('name');
                $table->string('mobile_no');
                $table->unsignedInteger('age')->nullable();
                $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
                $table->string('blood_group')->nullable();

                // Hospital details
                $table->string('hospital_name')->nullable();
                $table->text('hospital_address')->nullable();

                // Request info
                $table->timestamp('request_date')->nullable();
                $table->string('image')->nullable();
                $table->enum('status', ['Pending', 'Approved', 'Send Point'])->default('Pending');

                // Send points info
                $table->unsignedInteger('send_points')->nullable();
                $table->text('remarks')->nullable();
                $table->timestamp('send_points_date')->nullable();

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecard_seva_other_points');
    }
};
