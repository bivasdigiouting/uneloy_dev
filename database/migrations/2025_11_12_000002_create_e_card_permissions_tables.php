<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Department-level permissions
        Schema::create('e_card_department_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('department_level');
            $table->unsignedBigInteger('module_id');
            $table->boolean('can_view')->default(false);
            $table->boolean('can_create')->default(false);
            $table->boolean('can_update')->default(false);
            $table->boolean('can_delete')->default(false);
            $table->timestamps();
            $table->unique(['department_level', 'module_id']);
            $table->index('module_id');
        });

        // User-specific permissions
        Schema::create('e_card_user_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ecard_registration_id');
            $table->unsignedBigInteger('module_id');
            $table->boolean('can_view')->default(false);
            $table->boolean('can_create')->default(false);
            $table->boolean('can_update')->default(false);
            $table->boolean('can_delete')->default(false);
            $table->timestamps();
            $table->unique(['ecard_registration_id', 'module_id']);
            $table->index('ecard_registration_id');
            $table->index('module_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('e_card_user_permissions');
        Schema::dropIfExists('e_card_department_permissions');
    }
};
