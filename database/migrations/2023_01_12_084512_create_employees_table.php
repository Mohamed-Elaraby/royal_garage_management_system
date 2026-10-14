<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->date('birth_date')->nullable();
            $table->date('date_of_hiring');
            $table->bigInteger('job_title_id')->unsigned();
            $table->string('id_number')->nullable();
            $table->string('passport_number')->nullable();
            $table->bigInteger('branch_id')->unsigned();
            $table->bigInteger('nationality_id')->unsigned();
            $table->date('date_of_leaving_work')->nullable();
            $table->string('username')->unique();
            $table->string('hashed_password');
            $table->string('text_password');
            $table->enum('status',['active', 'inactive'])->default('active');
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
        Schema::dropIfExists('employees');
    }
}
