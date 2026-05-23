<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('vendor_global_vendor_distributions');
        Schema::create('vendor_global_vendor_distributions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('distribution_id');
            $table->unsignedBigInteger('vendor_id');
            $table->decimal('amount', 12, 2);
            $table->timestamps();

            $table->foreign('distribution_id')->references('id')->on('vendor_global_distributions')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->unique(['distribution_id', 'vendor_id'], 'vgvd_dist_vendor_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_global_vendor_distributions');
    }
};
