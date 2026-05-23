<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ecard_login_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ecard_registration_id');
            $table->string('ip_address', 64)->nullable();
            $table->string('platform', 32)->default('ecard');
            $table->text('user_agent')->nullable();
            $table->timestamp('logged_in_at')->nullable();
            $table->timestamp('logged_out_at')->nullable();
            $table->timestamps();

            $table->index(['ecard_registration_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ecard_login_histories');
    }
};
