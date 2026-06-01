<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // reference_id is used for transaction reference IDs like "EC_TRX_XXXX...".
        // Current schema appears to have reference_id as numeric/UNSIGNED INT causing:
        // Invalid datetime format / Incorrect integer value errors.
        //
        // Change it to VARCHAR(64).
        DB::statement("ALTER TABLE ecard_wallet_transactions MODIFY reference_id VARCHAR(64) NULL");
    }

    public function down(): void
    {
        // Best-effort rollback to NULLable BIGINT.
        DB::statement("ALTER TABLE ecard_wallet_transactions MODIFY reference_id BIGINT UNSIGNED NULL");
    }
};

