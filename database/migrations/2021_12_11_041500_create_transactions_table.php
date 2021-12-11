<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id('transID');
            $table->date('paidDate')->nullable();
            $table->string('transDetails')->nullable();
            $table->double('transPaidAmount',10,2)->nullable();

            $table->double('transAllPaid',10,2)->nullable();
            $table->double('transReducedAmount',10,2)->nullable();
            $table->double('transPaidInterest',10,2)->nullable();
            $table->double('transPaidPenaltyFee',10,2)->nullable();
            $table->double('transRestInterest',10,2)->nullable();
            $table->double('transRestPenaltyFee',10,2)->nullable();

            $table->integer('transLoanID')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
