<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('emergency_contact_details')) {
            Schema::create('emergency_contact_details', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('registration_id')->unique();

                $table->string('self_name');
                $table->string('self_mobile_no');
                $table->string('blood_group')->nullable();

                $table->string('family_contact_1')->nullable();
                $table->string('family_contact_2')->nullable();
                $table->string('family_contact_3')->nullable();

                $table->string('best_friend_contact_1')->nullable();
                $table->string('best_friend_contact_2')->nullable();
                $table->string('best_friend_contact_3')->nullable();

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('emergency_contact_details');
    }
};
