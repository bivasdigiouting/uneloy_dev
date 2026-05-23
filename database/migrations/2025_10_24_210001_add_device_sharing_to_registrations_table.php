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
        Schema::table('registrations', function (Blueprint $table) {
            if (! Schema::hasColumn('registrations', 'device_number')) {
                $table->string('device_number')->nullable()->after('wallet_balance');
            }
            if (! Schema::hasColumn('registrations', 'device_sharing_enabled')) {
                $table->boolean('device_sharing_enabled')->default(true)->after('device_number');
            }
            if (! Schema::hasColumn('registrations', 'max_device_shares')) {
                $table->unsignedInteger('max_device_shares')->default(1)->after('device_sharing_enabled');
            }
            $table->index(['device_sharing_enabled']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            if (Schema::hasColumn('registrations', 'max_device_shares')) {
                $table->dropColumn('max_device_shares');
            }
            if (Schema::hasColumn('registrations', 'device_sharing_enabled')) {
                $table->dropColumn('device_sharing_enabled');
            }
            if (Schema::hasColumn('registrations', 'device_number')) {
                $table->dropColumn('device_number');
            }
        });
    }
};
