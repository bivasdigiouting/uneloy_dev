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
            if (! Schema::hasColumn('website_settings', 'recharge_api_username')) {
                $table->string('recharge_api_username')->nullable();
            }
            if (! Schema::hasColumn('website_settings', 'recharge_api_token')) {
                $table->string('recharge_api_token')->nullable();
            }
            if (! Schema::hasColumn('website_settings', 'recharge_callback_url')) {
                $table->string('recharge_callback_url')->nullable();
            }
            if (! Schema::hasColumn('website_settings', 'recharge_pan_redirect_url')) {
                $table->string('recharge_pan_redirect_url')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            if (Schema::hasColumn('website_settings', 'recharge_api_username')) {
                $table->dropColumn('recharge_api_username');
            }
            if (Schema::hasColumn('website_settings', 'recharge_api_token')) {
                $table->dropColumn('recharge_api_token');
            }
            if (Schema::hasColumn('website_settings', 'recharge_callback_url')) {
                $table->dropColumn('recharge_callback_url');
            }
            if (Schema::hasColumn('website_settings', 'recharge_pan_redirect_url')) {
                $table->dropColumn('recharge_pan_redirect_url');
            }
        });
    }
};
