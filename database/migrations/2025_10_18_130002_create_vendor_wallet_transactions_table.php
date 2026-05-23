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
        if (! Schema::hasTable('vendor_wallet_transactions')) {
            Schema::create('vendor_wallet_transactions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('vendor_id');
                $table->enum('transaction_type', ['add', 'remove']);
                $table->decimal('amount', 10, 2);
                $table->decimal('previous_balance', 10, 2)->default(0.00);
                $table->decimal('new_balance', 10, 2)->default(0.00);
                $table->string('narration', 255)->nullable();
                $table->unsignedBigInteger('performed_by_user_id')->nullable();
                $table->timestamps();

                $table->index('vendor_id');
                $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
                $table->foreign('performed_by_user_id')->references('id')->on('users')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_wallet_transactions');
    }
};
