<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->unique()->nullable();

            $table->BigInteger('NIC')->unique()->nullable();
            
            $table->integer('role')->default(0);
            
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            
            $table->string('fileName')->nullable();
            
            $table->string('photo')->nullable();
            $table->string('userMap')->nullable();

            $table->integer('refBranch')->nullable();

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
