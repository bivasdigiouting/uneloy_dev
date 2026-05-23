<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('utility_affiliate_link_states')) {
            Schema::create('utility_affiliate_link_states', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('utility_affiliate_link_id');
                $table->unsignedBigInteger('state_id');
                $table->timestamps();

                $table->foreign('utility_affiliate_link_id')
                    ->references('id')->on('utility_affiliate_links')
                    ->onDelete('cascade');
                $table->foreign('state_id')
                    ->references('id')->on('states')
                    ->onDelete('cascade');
                $table->unique(['utility_affiliate_link_id', 'state_id'], 'uals_link_state_unique');
            });
        } else {
            Schema::table('utility_affiliate_link_states', function (Blueprint $table) {
                $table->unique(['utility_affiliate_link_id', 'state_id'], 'uals_link_state_unique');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('utility_affiliate_link_states');
    }
};
