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
            $table->json('theme_settings')->nullable()->after('wallet_balance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ecard_registrations', function (Blueprint $table) {
            $table->dropColumn('theme_settings');
        });
    }
};
