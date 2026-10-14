<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('total_amount');
            $table->string('details')->nullable();
            $table->double('amount_paid')->nullable();
            $table->double('amount_paid_bank')->nullable();
            $table->double('amount_paid_add_to_client_balance')->nullable();
            $table->double('amount_due')->nullable();
            $table->string('transaction_date');
            $table->enum('transaction_type', ['debit', 'credit'])->nullable();
            $table->double('debit')-> nullable();
            $table->double('credit')-> nullable();
            $table->double('client_balance')->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('client_id')->unsigned();
            $table->bigInteger('sale_order_id')->unsigned()->nullable();
            $table->bigInteger('sale_order_return_id')->unsigned()->nullable();
            $table->bigInteger('client_payment_id')->unsigned()->nullable();
            $table->bigInteger('client_collecting_id')->unsigned()->nullable();
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
        Schema::dropIfExists('client_transactions');
    }
}
