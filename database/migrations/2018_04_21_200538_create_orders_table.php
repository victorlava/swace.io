<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->float('amount', 10, 2); 
            $table->float('rate', 10, 2); // USD rate 1,000,000.00
            $table->float('gross', 10, 2);
            $table->float('fee', 8, 2); // CoinGate fee 100,000.00
            $table->float('net', 10, 2); // Calculate tokens from this sum
            $table->integer('tokens');
            $table->integer('bonus'); // Token bonus
            $table->string('invoice')->nullable(); // Invoice link
            $table->integer('coingate_id')->nullable()->unsigned();
            $table->integer('currency_id')->unsigned();
            $table->integer('status_id')->nullable()->unsigned();
            $table->integer('user_id')->unsigned();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('currency_id')->references('id')->on('currencies');
            $table->foreign('status_id')->references('id')->on('statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
