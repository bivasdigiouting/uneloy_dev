<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('redeem_value_histories', function (Blueprint $table) {
            $table->id();
            $table->decimal('total_user_points', 12, 2)->default(0);
            $table->decimal('redeem_amount', 12, 2)->default(0);
            $table->decimal('redeem_value', 12, 2)->default(0);
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->index('updated_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('redeem_value_histories');
    }
};
