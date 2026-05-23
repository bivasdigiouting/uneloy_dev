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
        Schema::table('registrations', function (Blueprint $table) {
            $table->string('aadhaar_front_image')->nullable()->after('aadhaar_no');
            $table->string('aadhaar_back_image')->nullable()->after('aadhaar_front_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn(['aadhaar_front_image', 'aadhaar_back_image']);
        });
    }
};
