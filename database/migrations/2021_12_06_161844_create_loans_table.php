<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id('loanID');
            $table->float('loanRate',4,2)->nullable();
            $table->float('loanAmount',10,2)->nullable();
            $table->float('penaltyRate',4,2)->nullable();
            $table->date('loanDate')->nullable();
           // $table->integer('dueDate')->nullable();
            $table->string('description')->nullable();

            $table->integer('loanLandID');


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
        Schema::dropIfExists('loans');
    }
}
