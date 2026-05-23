<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('emergency_family_contacts')) {
            Schema::create('emergency_family_contacts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('registration_id')->nullable()->index();
                $table->string('name');
                $table->string('mobile_no');
                $table->string('relation')->nullable();
                $table->unsignedInteger('age')->nullable();
                $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
                $table->string('live_location')->nullable();
                $table->text('description')->nullable();
                $table->string('image')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('emergency_family_contacts');
    }
};
