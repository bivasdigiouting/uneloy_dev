<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Wallet Fund Requests
        Schema::create('ecard_wallet_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ecard_registration_id');
            $table->decimal('amount', 12, 2);
            $table->string('payment_mode')->nullable();
            $table->string('reference_number')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
            $table->string('remark')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->unsignedBigInteger('approved_by_id')->nullable();
            $table->timestamps();
            $table->index('ecard_registration_id');
        });

        // Bank Settlements
        Schema::create('ecard_bank_settlements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ecard_registration_id');
            $table->decimal('amount', 12, 2);
            $table->string('settlement_mode')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
            $table->string('remark')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->unsignedBigInteger('approved_by_id')->nullable();
            $table->timestamps();
            $table->index('ecard_registration_id');
        });

        // Wallet Transactions
        Schema::create('ecard_wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ecard_registration_id');
            $table->enum('transaction_type', ['add', 'remove']);
            $table->decimal('amount', 12, 2);
            $table->decimal('previous_balance', 12, 2)->default(0);
            $table->decimal('new_balance', 12, 2)->default(0);
            $table->string('narration')->nullable();
            $table->unsignedBigInteger('performed_by_id')->nullable();
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->timestamps();
            $table->index('ecard_registration_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ecard_wallet_transactions');
        Schema::dropIfExists('ecard_bank_settlements');
        Schema::dropIfExists('ecard_wallet_requests');
    }
};
