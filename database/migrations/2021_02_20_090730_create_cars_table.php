<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->bigIncrements('id');
//            $table->string('counter_number');
            $table->string('chassis_number');
            $table->string('plate_number');
            $table->string('car_color');
            $table->bigInteger('client_id')->unsigned();
            $table->bigInteger('car_type_id')->unsigned();
            $table->bigInteger('car_size_id') ->unsigned();
            $table->bigInteger('car_model_id') ->unsigned() ->nullable();
            $table->bigInteger('car_engine_id') ->unsigned() ->nullable();
            $table->bigInteger('car_development_code_id') ->unsigned() ->nullable();
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
        Schema::dropIfExists('cars');
    }
}
