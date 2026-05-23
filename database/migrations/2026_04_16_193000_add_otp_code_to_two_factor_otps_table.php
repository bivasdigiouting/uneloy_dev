<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('two_factor_otps')) {
            return;
        }

        if (Schema::hasColumn('two_factor_otps', 'otp_code')) {
            return;
        }

        Schema::table('two_factor_otps', function (Blueprint $table) {
            $table->string('otp_code', 6)->nullable()->after('email');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('two_factor_otps')) {
            return;
        }

        if (! Schema::hasColumn('two_factor_otps', 'otp_code')) {
            return;
        }

        Schema::table('two_factor_otps', function (Blueprint $table) {
            $table->dropColumn('otp_code');
        });
    }
};

