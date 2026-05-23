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
        Schema::table('ecard_registrations', function (Blueprint $table) {
            $table->string('aadhaar_front')->nullable()->after('work_experience');
            $table->string('aadhaar_back')->nullable()->after('aadhaar_front');
            $table->string('pan_card')->nullable()->after('aadhaar_back');
            $table->string('cheque_book')->nullable()->after('pan_card');
            $table->string('business_document')->nullable()->after('cheque_book');
            $table->string('gst_document')->nullable()->after('business_document');
            $table->string('business_photo')->nullable()->after('gst_document');
            $table->string('signature')->nullable()->after('business_photo');
            $table->string('user_photo')->nullable()->after('signature');
            $table->enum('kyc_status', ['pending', 'approved', 'rejected'])->default('pending')->after('user_photo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ecard_registrations', function (Blueprint $table) {
            $table->dropColumn([
                'aadhaar_front', 'aadhaar_back', 'pan_card', 'cheque_book', 
                'business_document', 'gst_document', 'business_photo', 
                'signature', 'user_photo', 'kyc_status'
            ]);
        });
    }
};
