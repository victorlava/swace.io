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
            $table->integer('order_id')->unique();
            $table->decimal('amount', 19, 2);
            $table->decimal('rate', 19, 2)->nullable();
            $table->decimal('gross', 19, 2)->nullable();
            $table->decimal('fee', 19, 2)->nullable(); // CoinGate fee
            $table->decimal('net', 19, 2)->nullable();
            $table->decimal('tokens', 19, 2)->nullable();
            $table->decimal('bonus', 19, 2)->nullable(); // Token bonus
            $table->string('invoice')->nullable(); // Invoice link
            $table->integer('coingate_id')->nullable()->unsigned();
            $table->integer('currency_id')->unsigned();
            $table->integer('status_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('hash')->unique();
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
