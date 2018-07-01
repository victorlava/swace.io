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
            $table->string('email', 255)->unique();
            $table->string('password');
            $table->tinyInteger('verified')->default(0);
            $table->tinyInteger('contributed')->default(0);
            $table->tinyInteger('kyc')->default(0); // Know your customer
            $table->tinyInteger('personal')->default(1);
            $table->string('company_name')->nullable();
            $table->integer('company_code')->nullable();
            $table->integer('company_vat')->nullable();
            $table->string('company_address')->nullable();
            $table->string('email_token')->nullable();
            $table->rememberToken();
            $table->dateTime('verified_at')->nullable();
            $table->tinyInteger('admin')->default(0);
            $table->string('timezone', 60)->nullable();
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
