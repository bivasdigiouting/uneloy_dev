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
            $table->string('member_app_logo')->nullable()->after('favicon');
            $table->string('member_app_favicon')->nullable()->after('member_app_logo');
            $table->string('ecardseva_logo')->nullable()->after('member_app_favicon');
            $table->string('ecardseva_favicon')->nullable()->after('ecardseva_logo');
            $table->string('estore_app_logo')->nullable()->after('ecardseva_favicon');
            $table->string('estore_app_favicon')->nullable()->after('estore_app_logo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn([
                'member_app_logo',
                'member_app_favicon',
                'ecardseva_logo',
                'ecardseva_favicon',
                'estore_app_logo',
                'estore_app_favicon',
            ]);
        });
    }
};
