<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('status', ['غير مسددة','سداد جزئي','مسددة بالكامل'])->default('مسددة بالكامل');
            $table->double('amount');
            $table->longText('notes')->nullable();
            $table->enum('type', ['تخصم مباشرة', 'مجدولة'])->default('تخصم مباشرة');
            $table->tinyInteger('number_of_schedule')->nullable();
            $table->tinyInteger('refunds')->nullable();
            $table->tinyInteger('remaining_payments')->nullable();
            $table->string('payment_method')->nullable();
            $table->bigInteger('employee_id')->unsigned();
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
        Schema::dropIfExists('advances');
    }
}
