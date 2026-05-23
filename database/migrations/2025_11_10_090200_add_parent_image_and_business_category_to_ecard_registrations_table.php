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
        Schema::table('ecard_registrations', function (Blueprint $table) {
            if (! Schema::hasColumn('ecard_registrations', 'parent_id')) {
                $table->unsignedBigInteger('parent_id')->nullable()->after('id');
            }
            if (! Schema::hasColumn('ecard_registrations', 'profile_image')) {
                $table->string('profile_image')->nullable()->after('mother_name');
            }
            if (! Schema::hasColumn('ecard_registrations', 'business_category')) {
                $table->string('business_category')->nullable()->after('department_level');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ecard_registrations', function (Blueprint $table) {
            if (Schema::hasColumn('ecard_registrations', 'business_category')) {
                $table->dropColumn('business_category');
            }
            if (Schema::hasColumn('ecard_registrations', 'profile_image')) {
                $table->dropColumn('profile_image');
            }
            if (Schema::hasColumn('ecard_registrations', 'parent_id')) {
                $table->dropColumn('parent_id');
            }
        });
    }
};
