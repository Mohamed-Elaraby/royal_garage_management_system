<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeSalaryLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_salary_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('main')->nullable();
            $table->double('housing_allowance')->nullable();
            $table->double('transfer_allowance')->nullable();
            $table->double('travel_allowance')->nullable();
            $table->double('end_service_allowance')->nullable();
            $table->double('other_allowance')->nullable();
            $table->string('description_of_other_allowance')->nullable();
            $table->double('total_salary')->nullable();
            $table->double('total_advances')->nullable();
            $table->double('total_rewards')->nullable();
            $table->double('total_vacations')->nullable();
            $table->double('total_discounts')->nullable();
            $table->double('final_salary');
            $table->string('payment_method');
            $table->bigInteger('employee_id')->unsigned();
            $table->string('salary_month')->nullable();
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
        Schema::dropIfExists('employee_salary_logs');
    }
}
