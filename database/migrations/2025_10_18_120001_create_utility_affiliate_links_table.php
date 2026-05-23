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
        Schema::create('utility_affiliate_links', function (Blueprint $table) {
            $table->id();
            $table->enum('audience_type', ['User', 'E-Card Seva']);
            $table->unsignedBigInteger('district_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->string('type')->default('Utility & Affiliate Link');
            $table->date('from_date');
            $table->date('to_date')->nullable();
            $table->text('link');
            $table->timestamps();

            $table->foreign('district_id')->references('id')->on('districts')->onDelete('set null');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utility_affiliate_links');
    }
};
