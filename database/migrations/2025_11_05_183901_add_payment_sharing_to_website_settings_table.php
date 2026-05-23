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
            $table->decimal('ecard_share_percent', 5, 2)->default(0)->after('eqr_payment_enabled');
            $table->decimal('ewallet_share_percent', 5, 2)->default(0)->after('ecard_share_percent');
            $table->decimal('eqr_share_percent', 5, 2)->default(0)->after('ewallet_share_percent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn(['ecard_share_percent', 'ewallet_share_percent', 'eqr_share_percent']);
        });
    }
};
