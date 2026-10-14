<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('receipt_number')->nullable();
            $table->double('amount_paid')->nullable();
            $table->double('amount_paid_bank')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_method_bank')->nullable();
            $table->string('notes')->nullable();
            $table->string('payment_date');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('branch_id')->unsigned()->nullable();
            $table->bigInteger('client_id')->unsigned();
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
        Schema::dropIfExists('client_payments');
    }
}
