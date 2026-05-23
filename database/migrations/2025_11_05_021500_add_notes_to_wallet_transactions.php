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
        if (Schema::hasTable('wallet_transactions')) {
            Schema::table('wallet_transactions', function (Blueprint $table) {
                if (! Schema::hasColumn('wallet_transactions', 'credit_note')) {
                    $table->string('credit_note', 255)->nullable()->after('narration');
                }
                if (! Schema::hasColumn('wallet_transactions', 'debit_note')) {
                    $table->string('debit_note', 255)->nullable()->after('credit_note');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('wallet_transactions')) {
            Schema::table('wallet_transactions', function (Blueprint $table) {
                if (Schema::hasColumn('wallet_transactions', 'debit_note')) {
                    $table->dropColumn('debit_note');
                }
                if (Schema::hasColumn('wallet_transactions', 'credit_note')) {
                    $table->dropColumn('credit_note');
                }
            });
        }
    }
};
