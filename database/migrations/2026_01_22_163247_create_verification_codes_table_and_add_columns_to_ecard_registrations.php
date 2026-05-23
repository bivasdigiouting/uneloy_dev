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
        Schema::create('verification_codes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ecard_registration_id');
            $table->string('type'); // 'mobile' or 'email'
            $table->string('contact'); // The mobile number or email address
            $table->string('otp');
            $table->timestamp('expires_at');
            $table->timestamps();
            
            $table->index(['ecard_registration_id', 'type']);
        });

        Schema::table('ecard_registrations', function (Blueprint $table) {
            if (!Schema::hasColumn('ecard_registrations', 'mobile_verified_at')) {
                $table->timestamp('mobile_verified_at')->nullable()->after('mobile_no');
            }
            if (!Schema::hasColumn('ecard_registrations', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('email_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verification_codes');
        
        Schema::table('ecard_registrations', function (Blueprint $table) {
            $table->dropColumn(['mobile_verified_at', 'email_verified_at']);
        });
    }
};
