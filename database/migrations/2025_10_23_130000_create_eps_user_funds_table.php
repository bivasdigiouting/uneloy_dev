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
        if (! Schema::hasTable('eps_user_funds')) {
            Schema::create('eps_user_funds', function (Blueprint $table) {
                $table->id();
                $table->string('fund_type', 100); // e.g., Global Distribute User Fund
                $table->enum('user_type', ['recharge_1', 'recharge_2', 'deactivate']);
                $table->decimal('amount', 12, 2);
                $table->unsignedBigInteger('added_by_user_id')->nullable();
                $table->timestamps();

                $table->index('user_type');
                $table->foreign('added_by_user_id')->references('id')->on('users')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eps_user_funds');
    }
};
