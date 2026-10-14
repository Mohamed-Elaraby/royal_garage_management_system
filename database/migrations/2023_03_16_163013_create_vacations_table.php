<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVacationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('start_vacation');
            $table->date('end_vacation')->nullable();
            $table->date('extend_vacation')->nullable();
            $table->enum('type', ['مدفوعة الاجر','تخصم من الراتب'])->default('مدفوعة الاجر');
            $table->tinyInteger('total_days')->nullable();
            $table->enum('status', ['تم خصمها من الراتب', 'لم تخصم حتى الان', 'مدفوعة الاجر'])->default('مدفوعة الاجر');
            $table->double('discount_amount')->nullable();
            $table->bigInteger('employee_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->longText('notes')->nullable();
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
        Schema::dropIfExists('vacations');
    }
}
