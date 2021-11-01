<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('customer id');
            $table->integer('amt_paid_to')->comment('store id');
            $table->float('amt_received')->comment('Total Amound Received');
            $table->float('amt_paid_to_store')->comment('Amount paid to store');
            $table->float('comm')->comment('Commision Amount paid to goecolo');
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
        Schema::drop('payments');
    }
}
