<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->tinyInteger('type')->comment('2=>device_report_file');
            $table->string('path')->nullable();
            $table->string('extension');
            $table->bigInteger('check_id') ->nullable()->unsigned()->unique();
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
        Schema::dropIfExists('files');
    }
}
