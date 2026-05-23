<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            if (! Schema::hasColumn('registrations', 'area')) {
                $table->string('area')->nullable()->after('city');
            }
            if (! Schema::hasColumn('registrations', 'panchayat')) {
                $table->string('panchayat')->nullable()->after('area');
            }
            if (! Schema::hasColumn('registrations', 'municipality')) {
                $table->string('municipality')->nullable()->after('panchayat');
            }
            if (! Schema::hasColumn('registrations', 'village_name')) {
                $table->string('village_name')->nullable()->after('municipality');
            }
            if (! Schema::hasColumn('registrations', 'ward_no')) {
                $table->string('ward_no')->nullable()->after('village_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            if (Schema::hasColumn('registrations', 'ward_no')) {
                $table->dropColumn('ward_no');
            }
            if (Schema::hasColumn('registrations', 'village_name')) {
                $table->dropColumn('village_name');
            }
            if (Schema::hasColumn('registrations', 'municipality')) {
                $table->dropColumn('municipality');
            }
            if (Schema::hasColumn('registrations', 'panchayat')) {
                $table->dropColumn('panchayat');
            }
            if (Schema::hasColumn('registrations', 'area')) {
                $table->dropColumn('area');
            }
        });
    }
};
