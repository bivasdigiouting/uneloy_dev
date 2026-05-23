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
        Schema::create('gst_taxes', function (Blueprint $table) {
            $table->id();
            $table->string('tax_name');
            $table->decimal('rate_percent', 5, 2); // e.g., 0.00, 5.00, 12.00, 18.00, 28.00
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('is_active');
            $table->index('rate_percent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gst_taxes');
    }
};
