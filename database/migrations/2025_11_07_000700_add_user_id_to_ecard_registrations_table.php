<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ecard_registrations', function (Blueprint $table) {
            if (! Schema::hasColumn('ecard_registrations', 'user_id')) {
                $table->string('user_id', 32)->nullable()->unique()->after('email_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ecard_registrations', function (Blueprint $table) {
            if (Schema::hasColumn('ecard_registrations', 'user_id')) {
                $table->dropUnique(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};
