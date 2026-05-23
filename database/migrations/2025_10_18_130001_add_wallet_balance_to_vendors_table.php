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
        if (Schema::hasTable('vendors') && ! Schema::hasColumn('vendors', 'wallet_balance')) {
            Schema::table('vendors', function (Blueprint $table) {
                $table->decimal('wallet_balance', 10, 2)->default(0.00)->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('vendors') && Schema::hasColumn('vendors', 'wallet_balance')) {
            Schema::table('vendors', function (Blueprint $table) {
                $table->dropColumn('wallet_balance');
            });
        }
    }
};
