<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image_name');
            $table->string('image_path');
            $table->tinyInteger('type') ->comment('1=>chocks, 2=>device_report, 3=>client_signature_entry, 4=>client_signature_exit');
            $table->bigInteger('check_id') -> unsigned() -> nullable();
            $table->bigInteger('employee_salary_log_id') -> unsigned() -> nullable();
            $table->bigInteger('advance_id') -> unsigned() -> nullable();
            $table->bigInteger('reward_id')->unsigned()->nullable();
            $table->bigInteger('vacation_id')->unsigned()->nullable();
            $table->bigInteger('discount_id')->unsigned()->nullable();
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
        Schema::dropIfExists('images');
    }
}
