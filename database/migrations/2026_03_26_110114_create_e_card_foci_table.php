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
        Schema::create('e_card_foci', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('text_header')->nullable();
            $table->longText('text_description')->nullable();
            $table->text('footer_short_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('e_card_foci');
    }
};
