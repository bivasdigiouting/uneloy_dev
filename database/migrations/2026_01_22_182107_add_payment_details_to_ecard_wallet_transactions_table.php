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
        Schema::table('ecard_wallet_transactions', function (Blueprint $table) {
            $table->string('gateway_transaction_id')->nullable()->after('narration')->comment('Order ID / Payment ID');
            $table->string('gateway_name')->nullable()->after('gateway_transaction_id');
            $table->string('payment_status')->default('pending')->after('gateway_name'); // pending, success, failed
            $table->json('payment_meta')->nullable()->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ecard_wallet_transactions', function (Blueprint $table) {
            $table->dropColumn(['gateway_transaction_id', 'gateway_name', 'payment_status', 'payment_meta']);
        });
    }
};
