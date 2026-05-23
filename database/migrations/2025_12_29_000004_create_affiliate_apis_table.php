<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affiliate_apis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('affiliate_id');
            $table->string('service');
            $table->string('name');
            $table->string('api_name');
            $table->string('api_url', 2048);
            $table->timestamps();

            $table->foreign('affiliate_id')
                ->references('id')
                ->on('affiliates')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_apis');
    }
};
