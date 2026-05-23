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
        Schema::table('recharge_operators', function (Blueprint $table) {
            $table->string('operator_logo')->nullable()->after('operator_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recharge_operators', function (Blueprint $table) {
            $table->dropColumn('operator_logo');
        });
    }
};
