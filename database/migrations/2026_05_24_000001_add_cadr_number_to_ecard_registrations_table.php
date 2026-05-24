<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ecard_registrations', function (Blueprint $table) {
            if (! Schema::hasColumn('ecard_registrations', 'cadr_number')) {
                $table->string('cadr_number', 16)->nullable()->after('phone_no');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ecard_registrations', function (Blueprint $table) {
            if (Schema::hasColumn('ecard_registrations', 'cadr_number')) {
                $table->dropColumn('cadr_number');
            }
        });
    }
};

