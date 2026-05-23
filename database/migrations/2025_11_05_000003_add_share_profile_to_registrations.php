<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            if (! Schema::hasColumn('registrations', 'share_profile_to_ecard_seva')) {
                $table->boolean('share_profile_to_ecard_seva')->default(false)->after('wallet_balance');
            }
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            if (Schema::hasColumn('registrations', 'share_profile_to_ecard_seva')) {
                $table->dropColumn('share_profile_to_ecard_seva');
            }
        });
    }
};
