<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('total_amount');
            $table->string('details')->nullable();
            $table->double('amount_paid')->nullable();
            $table->double('amount_paid_bank')->nullable();
            $table->double('amount_paid_subtract_from_supplier_balance')->nullable();
            $table->double('amount_due')->nullable();
            $table->string('transaction_date');
            $table->enum('transaction_type', ['debit', 'credit'])->nullable();
            $table->double('debit')-> nullable();
            $table->double('credit')-> nullable();
            $table->double('supplier_discount_on_purchase_order')->nullable();
            $table->double('supplier_balance')->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('supplier_id')->unsigned();
            $table->bigInteger('purchase_order_id')->unsigned()->nullable();
            $table->bigInteger('purchase_order_return_id')->unsigned()->nullable();
            $table->bigInteger('supplier_payment_id')->unsigned()->nullable();
            $table->bigInteger('supplier_collecting_id')->unsigned()->nullable();
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
        Schema::dropIfExists('supplier_transactions');
    }
}
