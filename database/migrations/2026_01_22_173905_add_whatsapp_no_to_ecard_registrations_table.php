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
            if (!Schema::hasColumn('ecard_registrations', 'whatsapp_no')) {
                $table->string('whatsapp_no')->nullable()->after('mobile_no');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ecard_registrations', function (Blueprint $table) {
            if (Schema::hasColumn('ecard_registrations', 'whatsapp_no')) {
                $table->dropColumn('whatsapp_no');
            }
        });
    }
};
