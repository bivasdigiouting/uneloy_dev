<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ecard_kyc_documents')) {
            Schema::create('ecard_kyc_documents', function (Blueprint $table) {
                $table->id();
                // Reference to ecard_registrations (authenticated ecard user)
                $table->unsignedBigInteger('ecard_registration_id');
                // File path columns (stored on public disk)
                $table->string('aadhaar_front')->nullable();
                $table->string('aadhaar_back')->nullable();
                $table->string('pan_front')->nullable();
                $table->string('pan_back')->nullable();
                $table->string('cheque_book')->nullable();
                $table->string('business_document')->nullable();
                $table->string('business_photo')->nullable();
                $table->string('signature')->nullable();
                $table->timestamps();

                $table->unique('ecard_registration_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('ecard_kyc_documents')) {
            Schema::dropIfExists('ecard_kyc_documents');
        }
    }
};
