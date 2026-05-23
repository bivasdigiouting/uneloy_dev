<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('website_settings', 'third_party_api_username')) {
                $table->string('third_party_api_username', 100)->nullable()->after('maintenance_mode');
            }
            if (! Schema::hasColumn('website_settings', 'third_party_api_token')) {
                $table->string('third_party_api_token', 255)->nullable()->after('third_party_api_username');
            }
            if (! Schema::hasColumn('website_settings', 'third_party_api_url')) {
                $table->string('third_party_api_url', 255)->nullable()->after('third_party_api_token');
            }
        });

        // Seed initial values if not set
        $existing = DB::table('website_settings')->first();
        $seedValues = [
            'third_party_api_username' => '9564853492',
            'third_party_api_token' => '6d8b045b5c92ff9916806d5bd652fe6a',
            'third_party_api_url' => 'https://connect.ekychub.in/v3/',
            'updated_at' => now(),
        ];

        if ($existing) {
            DB::table('website_settings')->update($seedValues);
        } else {
            DB::table('website_settings')->insert(array_merge($seedValues, [
                'created_at' => now(),
            ]));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            if (Schema::hasColumn('website_settings', 'third_party_api_username')) {
                $table->dropColumn('third_party_api_username');
            }
            if (Schema::hasColumn('website_settings', 'third_party_api_token')) {
                $table->dropColumn('third_party_api_token');
            }
            if (Schema::hasColumn('website_settings', 'third_party_api_url')) {
                $table->dropColumn('third_party_api_url');
            }
        });
    }
};
