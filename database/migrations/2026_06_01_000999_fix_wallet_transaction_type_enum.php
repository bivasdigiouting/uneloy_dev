<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // transaction_type currently defined in create migration as enum('add','remove')
        // but the PayTabController transfer inserts: transfer_out / transfer_in.
        //
        // Enforce correct ENUM values.
        DB::statement("ALTER TABLE ecard_wallet_transactions MODIFY transaction_type ENUM('add','remove','transfer_out','transfer_in') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE ecard_wallet_transactions MODIFY transaction_type ENUM('add','remove') NOT NULL");
    }
};

