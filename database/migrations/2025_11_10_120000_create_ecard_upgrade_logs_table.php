<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ecard_upgrade_logs')) {
            Schema::create('ecard_upgrade_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('ecard_registration_id');
                $table->string('from_level')->nullable();
                $table->string('to_level');
                $table->unsignedBigInteger('upgraded_by_id')->nullable();
                $table->string('remark')->nullable();
                $table->timestamps();
                $table->index(['ecard_registration_id']);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('ecard_upgrade_logs')) {
            Schema::dropIfExists('ecard_upgrade_logs');
        }
    }
};
