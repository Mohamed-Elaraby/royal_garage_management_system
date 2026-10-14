<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRewardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rewards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('status', ['اضيفت الى الراتب', 'غير مضافة حتى الان', 'حصل عليها العامل فورا'])->default('غير مضافة حتى الان');
            $table->double('amount');
            $table->enum('type', ['تضاف الى الراتب', 'يحصل عليها العامل فورا'])->default('تضاف الى الراتب');
            $table->string('payment_method')->nullable();
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
        Schema::dropIfExists('rewards');
    }
}
