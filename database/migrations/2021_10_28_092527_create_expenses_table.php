<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('amount');
            $table->text('notes')->nullable();
            $table->string('expenses_date');
            $table->string('payment_method')->nullable();
            $table->bigInteger('expenses_type_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
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
        Schema::dropIfExists('expenses');
    }
}
