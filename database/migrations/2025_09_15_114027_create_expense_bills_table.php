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
        Schema::create('expense_bills', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('expense_id')->constrained('expenses')->onDelete('cascade');
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->string('bill_no');
            $table->enum('payment_mode', ['cash', 'bank', 'upi']);
            $table->string('bank_account_no')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('upi_id')->nullable();
            $table->string('supplier');
            $table->string('bill_file')->nullable();
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_bills');
    }
};
