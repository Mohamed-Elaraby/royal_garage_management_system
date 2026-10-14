<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_order_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('item_code');
            $table->string('item_name');
            $table->integer('item_quantity');
            $table->double('item_purchasing_price');
            $table->double('item_price');
            $table->double('item_amount_taxable');
            $table->double('item_discount')->nullable();
            $table->tinyInteger('item_discount_type') ->comment('0=> currency, 1=> percentage')->nullable();
            $table->double('item_discount_amount') ->nullable();
            $table->double('item_sub_total_after_discount') ->nullable();
            $table->double('item_tax_amount');
            $table->double('item_sub_total');
            $table->bigInteger('sale_order_id')->unsigned();
            $table->bigInteger('product_id')->unsigned();
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
        Schema::dropIfExists('sale_order_products');
    }
}
