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
        Schema::table('user_login_histories', function (Blueprint $table) {
            if (! Schema::hasColumn('user_login_histories', 'logged_out_at')) {
                $table->timestamp('logged_out_at')->nullable()->after('logged_in_at');
                $table->index(['platform', 'logged_out_at']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_login_histories', function (Blueprint $table) {
            if (Schema::hasColumn('user_login_histories', 'logged_out_at')) {
                $table->dropIndex(['platform', 'logged_out_at']);
                $table->dropColumn('logged_out_at');
            }
        });
    }
};
