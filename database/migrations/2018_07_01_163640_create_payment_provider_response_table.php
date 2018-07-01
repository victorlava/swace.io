<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentProviderResponseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('response', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('coingate_id')->unsigned();
            $table->integer('order_id')->unsigned();
            $table->string('response');
            $table->timestamps();

            $table->foreign('coingate_id')->references('id')->on('orders');
            $table->foreign('order_id')->references('id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_provider_response');
    }
}
