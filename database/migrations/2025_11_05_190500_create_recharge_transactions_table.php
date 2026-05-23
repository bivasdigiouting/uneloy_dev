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
        Schema::create('recharge_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('service_code'); // MOBILE, DTH, FASTAG, BBPS
            $table->unsignedBigInteger('operator_id')->nullable();
            $table->string('biller_code')->nullable();
            $table->string('recharge_no')->nullable(); // mobile/account/consumer number/tag id
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('payment_method')->nullable(); // ecard/ewallet/eqr
            $table->string('status')->default('pending');
            $table->string('transaction_id')->unique();
            $table->json('response')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recharge_transactions');
    }
};
