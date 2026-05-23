<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('recharge_commission_rules')) {
            Schema::create('recharge_commission_rules', function (Blueprint $table) {
                $table->id();
                $table->foreignId('recharge_service_id')
                    ->constrained('recharge_services')
                    ->onUpdate('cascade')
                    ->onDelete('restrict');
                $table->foreignId('recharge_operator_id')
                    ->nullable()
                    ->constrained('recharge_operators')
                    ->onUpdate('cascade')
                    ->onDelete('restrict');
                $table->string('department_level')->nullable();
                $table->enum('commission_type', ['percentage', 'flat'])->default('percentage');
                $table->decimal('commission_value', 10, 2)->default(0);
                $table->decimal('min_amount', 10, 2)->nullable();
                $table->decimal('max_amount', 10, 2)->nullable();
                $table->boolean('is_active')->default(true);
                $table->unsignedBigInteger('created_by_user_id')->nullable();
                $table->timestamps();

                $table->index(['recharge_service_id', 'recharge_operator_id'], 'rc_rules_srv_op_idx');
                $table->index(['department_level', 'is_active'], 'rc_rules_level_active_idx');
            });
        }

        if (! Schema::hasTable('recharge_commissions')) {
            Schema::create('recharge_commissions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('recharge_transaction_id');
                $table->foreignId('registration_id')
                    ->constrained('registrations')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
                $table->foreignId('recharge_commission_rule_id')
                    ->nullable()
                    ->constrained('recharge_commission_rules')
                    ->onUpdate('cascade')
                    ->onDelete('set null');
                $table->foreignId('recharge_service_id')
                    ->constrained('recharge_services')
                    ->onUpdate('cascade')
                    ->onDelete('restrict');
                $table->foreignId('recharge_operator_id')
                    ->nullable()
                    ->constrained('recharge_operators')
                    ->onUpdate('cascade')
                    ->onDelete('restrict');
                $table->string('department_level')->nullable();
                $table->enum('commission_type', ['percentage', 'flat'])->default('percentage');
                $table->decimal('commission_value', 10, 2)->default(0);
                $table->decimal('recharge_amount', 10, 2)->default(0);
                $table->decimal('commission_amount', 10, 2)->default(0);
                $table->enum('status', ['pending', 'credited', 'reversed'])->default('credited');
                $table->unsignedBigInteger('wallet_transaction_id')->nullable();
                $table->json('meta')->nullable();
                $table->timestamps();

                $table->unique('recharge_transaction_id', 'rc_comm_txn_uq');
                $table->index('recharge_transaction_id', 'rc_comm_txn_idx');
                $table->index(['registration_id', 'created_at'], 'rc_comm_reg_created_idx');
                $table->index(['recharge_service_id', 'recharge_operator_id'], 'rc_comm_srv_op_idx');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('recharge_commissions');
        Schema::dropIfExists('recharge_commission_rules');
    }
};
