<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('imports_cash', 10, 2)->nullable();
            $table->decimal('imports_network', 10, 2)->nullable();
            $table->decimal('imports_bank_transfer', 10, 2)->nullable();
            $table->decimal('card_details_hand_labour', 10, 2)->nullable();
            $table->decimal('card_details_new_parts', 10, 2)->nullable();
            $table->decimal('card_details_used_parts', 10, 2)->nullable();
            $table->decimal('card_details_tax', 10, 2)->nullable();
            $table->decimal('expenses_cash', 10, 2)->nullable();
            $table->decimal('expenses_network', 10, 2)->nullable();
            $table->decimal('custody_administration_cash', 10, 2)->nullable();
            $table->decimal('custody_administration_network', 10, 2)->nullable();
            $table->decimal('cash_to_administration', 10, 2)->nullable();
            $table->decimal('advances_and_salaries_cash', 10, 2)->nullable();
            $table->decimal('advances_and_salaries_network', 10, 2)->nullable();
            $table->longText('notes')->nullable();
            $table->bigInteger('sale_order_id')->unsigned()->nullable();
            $table->bigInteger('client_payment_id')->unsigned()->nullable();
            $table->bigInteger('client_collecting_id')->unsigned()->nullable();
            $table->bigInteger('expenses_id')->unsigned()->nullable();
            $table->bigInteger('purchase_order_id')->unsigned()->nullable();
            $table->bigInteger('supplier_payment_id')->unsigned()->nullable();
            $table->bigInteger('supplier_collecting_id')->unsigned()->nullable();
            $table->bigInteger('sale_order_return_id')->unsigned()->nullable();
            $table->bigInteger('purchase_order_return_id')->unsigned()->nullable();
//            $table->bigInteger('salary_id')->unsigned()->nullable();
            $table->bigInteger('advance_id')->unsigned()->nullable();
//            $table->bigInteger('scheduled_advance_id')->unsigned()->nullable();
            $table->bigInteger('reward_id')->unsigned()->nullable();
//            $table->bigInteger('vacation_id')->unsigned()->nullable();
            $table->bigInteger('discount_id')->unsigned()->nullable();
            $table->bigInteger('employee_salary_log_id')->unsigned()->nullable();
            $table->bigInteger('money_safe_id')->unsigned()->nullable();
            $table->bigInteger('bank_id')->unsigned()->nullable();
            $table->bigInteger('branch_id')->unsigned()->nullable();
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
        Schema::dropIfExists('statements');
    }
}
