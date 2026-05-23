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
        Schema::table('website_settings', function (Blueprint $table) {
            $table->boolean('ecard_payment_enabled')->default(false)->after('maintenance_mode');
            $table->boolean('ewallet_payment_enabled')->default(false)->after('ecard_payment_enabled');
            $table->boolean('eqr_payment_enabled')->default(false)->after('ewallet_payment_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn(['ecard_payment_enabled', 'ewallet_payment_enabled', 'eqr_payment_enabled']);
        });
    }
};
