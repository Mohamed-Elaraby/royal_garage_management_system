<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoneySafeOpeneingBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('money_safe_openeing_balances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('balance');
            $table->bigInteger('branch_id')->unsigned();
            $table->bigInteger('money_safe_id')->unsigned();
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
        Schema::dropIfExists('money_safe_openeing_balances');
    }
}
