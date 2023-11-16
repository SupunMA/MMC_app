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
            $table->float('transPaidAmount',10,2)->default();

            $table->float('transAllPaid',10,2)->default(0.0);
            $table->float('transPaidInterest',10,2)->default(0.0);
            $table->float('transPaidPenaltyFee',10,2)->default(0.0);
            $table->float('transRestInterest',10,2)->default(0.0);
            $table->float('transRestPenaltyFee',10,2)->default(0.0);
            $table->float('transReducedAmount',10,2)->default(0.0);
            $table->float('transExtraMoney',10,2)->default(0.0);

            $table->integer('transLoanID')->nullable();

            $table->integer('transStatus')->default(0); //0 = interestPaying, 1 = loan reducing, 2 = interest,late fee,loan cut off
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
