<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ecard_registrations', function (Blueprint $table) {
            if (! Schema::hasColumn('ecard_registrations', 'department_level')) {
                $table->string('department_level')->nullable()->after('business_location_map');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ecard_registrations', function (Blueprint $table) {
            if (Schema::hasColumn('ecard_registrations', 'department_level')) {
                $table->dropColumn('department_level');
            }
        });
    }
};
