<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingResponseForeignAndUserTimezone extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('responses', function (Blueprint $table) {
        $table->foreign('order_id')->references('order_id')->on('orders');
      });

      Schema::table('users', function (Blueprint $table) {
        $table->string('timezone', 60)->nullable();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('responses', function (Blueprint $table) {
        $table->dropForeign('order_id');
      });

      Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('timezone');
      });
    }
}
