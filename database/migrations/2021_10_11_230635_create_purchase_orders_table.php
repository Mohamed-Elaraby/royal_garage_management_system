<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_number')->nullable();
            $table->string('invoice_date');
            $table->double('total');
            $table->double('discount')->nullable();
            $table->double('taxable_amount');
            $table->double('total_vat');
            $table->double('total_amount_due');
            $table->double('amount_paid')->nullable();
            $table->double('amount_paid_bank')->nullable();
            $table->double('amount_due');
            $table->double('supplier_discount')->nullable();
            $table->tinyInteger('supplier_discount_type')->comment('0=> currency, 1=> percentage')->nullable();
            $table->double('supplier_discount_amount')->nullable();
            $table->enum('status', ['close', 'open'])->default('close');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('branch_id')->unsigned();
            $table->bigInteger('supplier_id')->unsigned();
            $table->string('payment_method')->nullable();
            $table->string('payment_method_bank')->nullable();
            $table->string('payment_receipt_number')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_orders');
    }
}
