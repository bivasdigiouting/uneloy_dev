<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('website_settings', 'maintenance_title')) {
                $table->string('maintenance_title', 255)->nullable()->after('maintenance_mode');
            }
            if (! Schema::hasColumn('website_settings', 'maintenance_message')) {
                $table->text('maintenance_message')->nullable()->after('maintenance_title');
            }
        });
    }

    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            if (Schema::hasColumn('website_settings', 'maintenance_message')) {
                $table->dropColumn('maintenance_message');
            }
            if (Schema::hasColumn('website_settings', 'maintenance_title')) {
                $table->dropColumn('maintenance_title');
            }
        });
    }
};
