<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ecard_registrations', function (Blueprint $table) {
            if (! Schema::hasColumn('ecard_registrations', 'password')) {
                $table->string('password')->nullable()->after('gmail_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ecard_registrations', function (Blueprint $table) {
            if (Schema::hasColumn('ecard_registrations', 'password')) {
                $table->dropColumn('password');
            }
        });
    }
};
