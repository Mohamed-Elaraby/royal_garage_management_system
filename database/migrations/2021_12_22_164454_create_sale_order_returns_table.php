<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleOrderReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_order_returns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_number')->nullable();
            $table->string('invoice_date');
            $table->double('total');
            $table->double('discount')->nullable();
            $table->double('taxable_amount');
            $table->double('total_vat');
            $table->double('total_return_items');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('branch_id')->unsigned();
            $table->string('notes')->nullable();
            $table->bigInteger('sale_order_id')->unsigned();
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
        Schema::dropIfExists('sale_order_returns');
    }
}
