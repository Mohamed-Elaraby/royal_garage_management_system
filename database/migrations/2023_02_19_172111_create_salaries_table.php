<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('main')->nullable();
            $table->double('housing_allowance')->nullable();
            $table->double('transfer_allowance')->nullable();
            $table->double('travel_allowance')->nullable();
            $table->double('end_service_allowance')->nullable();
            $table->double('other_allowance')->nullable();
            $table->string('description_of_other_allowance')->nullable();
            $table->bigInteger('employee_id')->unsigned();
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
        Schema::dropIfExists('salaries');
    }
}
