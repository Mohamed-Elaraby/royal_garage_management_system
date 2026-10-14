<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // total - discount - taxable_amount - total_vat - total_amount_due - amount_paid - amount_due - payment_method
        Schema::create('sale_orders', function (Blueprint $table) {
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
            $table->double('hand_labour')->nullable();
            $table->double('new_parts')->nullable();
            $table->double('used_parts')->nullable();
            $table->enum('status', ['close', 'open'])->default('close');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('branch_id')->unsigned();
            $table->bigInteger('client_id')->unsigned();
            $table->bigInteger('check_id')->unsigned();
            $table->string('payment_method')->nullable();
            $table->string('payment_method_bank')->nullable();
            $table->string('payment_receipt_number')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();
            $table->timestamp('date_of_supply')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_orders');
    }
}
