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
        Schema::create('eps_level_distributions', function (Blueprint $table) {
            $table->id();
            $table->decimal('total_amount', 12, 2);
            $table->string('commission_source_type')->nullable();
            $table->unsignedBigInteger('commission_source_id')->nullable();
            $table->json('commission_breakdown')->nullable();
            $table->unsignedBigInteger('created_by_user_id');
            $table->timestamps();

            $table->foreign('created_by_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['commission_source_type', 'commission_source_id'], 'eps_commission_source_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eps_level_distributions');
    }
};
