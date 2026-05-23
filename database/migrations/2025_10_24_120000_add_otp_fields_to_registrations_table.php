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
            $table->boolean('otp_required')->default(false)->after('aadhaar_no');
            $table->string('otp_code')->nullable()->after('otp_required');
            $table->boolean('otp_verified')->default(false)->after('otp_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn(['otp_required', 'otp_code', 'otp_verified']);
        });
    }
};
