<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('check_number');
            $table->string('counter_number');
            $table->string('chassis_number');
            $table->string('plate_number');
            $table->string('car_color');
            $table->string('driver_name')->nullable();
            $table->string('fuel_level');
            $table->longText('car_status_report');
            $table->longText('car_status_report_note') -> nullable();
            $table->longText('car_images_note') -> nullable();
            $table->bigInteger('check_status_id')
                ->unsigned()
                ->default(true)
                ->comment('1=>carUnderCheck, 2=>waitingClientResponse, 3=>clientApproved, 4=>carUnderMaintenance, 5=>maintenanceComplete, 6=>carExit');
            $table->string('car_type');
            $table->string('car_size');
            $table->string('car_model') ->nullable();
            $table->string('car_engine') ->nullable();
            $table->string('car_development_code') ->nullable();
            $table->bigInteger('client_id') -> unsigned();
            $table->bigInteger('user_id') -> unsigned();
            $table->bigInteger('branch_id') -> unsigned();
            $table->bigInteger('car_id') -> unsigned()->nullable();
//            $table->bigInteger('technical_id') -> unsigned() ->nullable();
            $table->bigInteger('engineer_id') -> unsigned() ->nullable();
            $table->tinyInteger('client_approved') ->default(false) ->comment('0=>disabled, 1 =>enabled');
            $table->longText('management_notes') ->nullable();
            $table->timestamp('car_exit_date') ->nullable();
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
        Schema::dropIfExists('checks');
    }
}
