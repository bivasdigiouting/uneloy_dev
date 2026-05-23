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
        // Add custom fields to roles table
        Schema::table('roles', function (Blueprint $table) {
            $table->string('display_name')->nullable()->after('name');
            $table->text('description')->nullable()->after('display_name');
            $table->boolean('is_active')->default(true)->after('description');
        });

        // Add custom fields to permissions table
        Schema::table('permissions', function (Blueprint $table) {
            $table->string('display_name')->nullable()->after('name');
            $table->text('description')->nullable()->after('display_name');
            $table->string('module')->nullable()->after('description');
            $table->boolean('is_active')->default(true)->after('module');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove custom fields from roles table
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn(['display_name', 'description', 'is_active']);
        });

        // Remove custom fields from permissions table
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn(['display_name', 'description', 'module', 'is_active']);
        });
    }
};
