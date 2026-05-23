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
        Schema::table('ecard_sales', function (Blueprint $table) {
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending')->after('total_amount');
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending')->after('status');
            $table->string('payment_method')->nullable()->after('payment_status');
            $table->string('transaction_id')->nullable()->after('payment_method');
            $table->json('payment_details')->nullable()->after('transaction_id');
        });

        Schema::table('ecard_registrations', function (Blueprint $table) {
            $table->decimal('bonus_wallet_balance', 10, 2)->default(0)->after('wallet_balance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ecard_sales', function (Blueprint $table) {
            $table->dropColumn(['status', 'payment_status', 'payment_method', 'transaction_id', 'payment_details']);
        });

        Schema::table('ecard_registrations', function (Blueprint $table) {
            $table->dropColumn('bonus_wallet_balance');
        });
    }
};
