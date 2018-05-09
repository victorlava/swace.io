<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->string('email', 254)->unique();
            $table->string('password');
            $table->tinyInteger('verified')->default(0);
            $table->tinyInteger('contributed')->default(0);
            $table->tinyInteger('kyc')->default(0); // Know your customer
            $table->string('email_token')->nullable();
            $table->rememberToken();
            $table->dateTime('created_at');
            $table->dateTime('verified_at')->nullable();
            $table->tinyInteger('admin')->default(0);
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
