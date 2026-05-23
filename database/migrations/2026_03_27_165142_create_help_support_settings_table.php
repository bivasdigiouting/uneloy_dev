<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('help_support_settings', function (Blueprint $table) {
            $table->id();
            $table->string('page_title')->nullable();
            $table->text('intro_text')->nullable();
            $table->string('support_email')->nullable();
            $table->string('support_phone')->nullable();
            $table->string('support_whatsapp')->nullable();
            $table->string('live_chat_url')->nullable();
            $table->string('support_address')->nullable();
            $table->string('working_hours')->nullable();
            $table->longText('additional_info')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('help_support_settings');
    }
};
