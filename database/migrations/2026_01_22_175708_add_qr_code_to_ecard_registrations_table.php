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
            if (!Schema::hasColumn('ecard_registrations', 'qr_code')) {
                $table->string('qr_code')->nullable()->after('profile_image');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ecard_registrations', function (Blueprint $table) {
            if (Schema::hasColumn('ecard_registrations', 'qr_code')) {
                $table->dropColumn('qr_code');
            }
        });
    }
};
