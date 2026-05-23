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
        Schema::table('registrations', function (Blueprint $table) {
            $table->string('ecard_number')->nullable()->after('wallet_balance');
            $table->string('ecard_cvv')->nullable()->after('ecard_number');
            $table->string('ecard_security_pin')->nullable()->after('ecard_cvv');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn(['ecard_number', 'ecard_cvv', 'ecard_security_pin']);
        });
    }
};
