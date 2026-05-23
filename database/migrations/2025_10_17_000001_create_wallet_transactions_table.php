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
        if (! Schema::hasTable('wallet_transactions')) {
            Schema::create('wallet_transactions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('registration_id');
                $table->enum('transaction_type', ['add', 'remove']);
                $table->decimal('amount', 10, 2);
                $table->decimal('previous_balance', 10, 2)->default(0.00);
                $table->decimal('new_balance', 10, 2)->default(0.00);
                $table->string('narration', 255)->nullable();
                $table->unsignedBigInteger('performed_by_user_id')->nullable();
                $table->timestamps();

                $table->index('registration_id');
                $table->foreign('registration_id')->references('id')->on('registrations')->onDelete('cascade');
                $table->foreign('performed_by_user_id')->references('id')->on('users')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
