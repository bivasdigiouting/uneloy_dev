<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEcardSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ecard_sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ecard_registration_id');
            $table->string('customer_name');
            $table->date('billing_date');
            $table->decimal('purchase_value', 10, 2)->default(0); // Subtotal before tax
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0); // Grand total
            $table->timestamps();

            $table->foreign('ecard_registration_id')->references('id')->on('ecard_registrations')->onDelete('cascade');
        });

        Schema::create('ecard_sale_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ecard_sale_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2); // (price * quantity) + tax
            $table->timestamps();

            $table->foreign('ecard_sale_id')->references('id')->on('ecard_sales')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ecard_sale_items');
        Schema::dropIfExists('ecard_sales');
    }
}
