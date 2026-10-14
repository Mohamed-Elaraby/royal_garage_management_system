<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->string('name');
            $table->double('price');
            $table->double('discount')->nullable();
            $table->double('discount_type')->nullable();
            $table->double('discount_amount')->nullable();
            $table->double('price_after_discount')->nullable();
            $table->double('selling_price')->nullable();
            $table->integer('quantity');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('sub_category_id')->unsigned()->nullable();
            $table->bigInteger('branch_id')->unsigned();
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
        Schema::dropIfExists('products');
    }
}
