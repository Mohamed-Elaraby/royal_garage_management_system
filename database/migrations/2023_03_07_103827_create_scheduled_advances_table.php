<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduledAdvancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduled_advances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('amount');
            $table->enum('status', ['مسددة', 'غير مسددة'])->default('غير مسددة');
            $table->enum('payment_method', ['خصم من الراتب', 'اخرى'])->nullable();
            $table->date('date_of_payment')->nullable();
            $table->bigInteger('advance_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
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
        Schema::dropIfExists('scheduled_advances');
    }
}
