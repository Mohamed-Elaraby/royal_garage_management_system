<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInternalTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internal_transfers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->string('name');
            $table->double('price');
            $table->integer('quantity');
            $table->double('discount')->nullable();
            $table->double('discount_type')->nullable();
            $table->double('discount_amount')->nullable();
            $table->double('price_after_discount')->nullable();
            $table->bigInteger('from_branch');
            $table->bigInteger('to_branch');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('sub_category_id')->unsigned();
            $table->bigInteger('administrator_id') ->nullable()->unsigned();
            $table->timestamp('request_action_date') ->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected']) ->default('pending');
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
        Schema::dropIfExists('internal_transfers');
    }
}
