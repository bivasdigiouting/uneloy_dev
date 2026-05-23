<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('two_factor_otps')) {
            return;
        }

        Schema::create('two_factor_otps', function (Blueprint $table) {
            $table->id();
            $table->string('context', 20);
            $table->unsignedBigInteger('subject_id');
            $table->string('email', 255);
            $table->string('otp_hash', 255);
            $table->unsignedSmallInteger('attempts')->default(0);
            $table->dateTime('last_sent_at')->nullable();
            $table->dateTime('expires_at');
            $table->dateTime('consumed_at')->nullable();
            $table->timestamps();

            $table->index(['context', 'subject_id', 'consumed_at']);
            $table->index(['context', 'email']);
            $table->index(['expires_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('two_factor_otps');
    }
};

