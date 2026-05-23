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
        Schema::table('vendor_staff', function (Blueprint $table) {
            $table->decimal('base_salary', 10, 2)->default(0)->after('is_online');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor_staff', function (Blueprint $table) {
            $table->dropColumn('base_salary');
        });
    }
};
