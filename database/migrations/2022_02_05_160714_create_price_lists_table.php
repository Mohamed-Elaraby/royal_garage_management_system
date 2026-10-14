<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriceListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('price_list_number')->nullable();
            $table->double('total');
            $table->double('discount')->nullable();
            $table->double('taxable_amount');
            $table->double('total_vat');
            $table->double('total_amount_due');
            $table->string('chassis_number');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('branch_id')->unsigned();
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
        Schema::dropIfExists('price_lists');
    }
}
