<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('adhar_verification_details', function (Blueprint $table) {
            $table->string('verification_id')->nullable()->after('adhar_status');
        });
    }

    public function down(): void
    {
        Schema::table('adhar_verification_details', function (Blueprint $table) {
            $table->dropColumn('verification_id');
        });
    }
};

