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
            if (! Schema::hasColumn('website_settings', 'firebase_server_key')) {
                $table->text('firebase_server_key')->nullable()->after('site_description');
            }
            if (! Schema::hasColumn('website_settings', 'firebase_api_key')) {
                $table->string('firebase_api_key', 255)->nullable()->after('firebase_server_key');
            }
            if (! Schema::hasColumn('website_settings', 'firebase_project_id')) {
                $table->string('firebase_project_id', 255)->nullable()->after('firebase_api_key');
            }
            if (! Schema::hasColumn('website_settings', 'firebase_sender_id')) {
                $table->string('firebase_sender_id', 255)->nullable()->after('firebase_project_id');
            }
            if (! Schema::hasColumn('website_settings', 'firebase_app_id')) {
                $table->string('firebase_app_id', 255)->nullable()->after('firebase_sender_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            if (Schema::hasColumn('website_settings', 'firebase_server_key')) {
                $table->dropColumn('firebase_server_key');
            }
            if (Schema::hasColumn('website_settings', 'firebase_api_key')) {
                $table->dropColumn('firebase_api_key');
            }
            if (Schema::hasColumn('website_settings', 'firebase_project_id')) {
                $table->dropColumn('firebase_project_id');
            }
            if (Schema::hasColumn('website_settings', 'firebase_sender_id')) {
                $table->dropColumn('firebase_sender_id');
            }
            if (Schema::hasColumn('website_settings', 'firebase_app_id')) {
                $table->dropColumn('firebase_app_id');
            }
        });
    }
};
