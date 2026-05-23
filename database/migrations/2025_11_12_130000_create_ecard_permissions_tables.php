<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ecard_modules', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('key')->unique();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('route_name')->nullable()->index();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('ecard_department_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('department_level');
            $table->unsignedBigInteger('module_id');
            $table->boolean('can_view')->default(false);
            $table->boolean('can_create')->default(false);
            $table->boolean('can_update')->default(false);
            $table->boolean('can_delete')->default(false);
            $table->timestamps();
            $table->unique(['department_level', 'module_id']);
        });

        Schema::create('ecard_user_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ecard_registration_id');
            $table->unsignedBigInteger('module_id');
            $table->boolean('can_view')->default(false);
            $table->boolean('can_create')->default(false);
            $table->boolean('can_update')->default(false);
            $table->boolean('can_delete')->default(false);
            $table->timestamps();
            $table->unique(['ecard_registration_id', 'module_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ecard_user_permissions');
        Schema::dropIfExists('ecard_department_permissions');
        Schema::dropIfExists('ecard_modules');
    }
};
