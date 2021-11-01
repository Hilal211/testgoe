<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreBankDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_bank_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id');
            $table->string('stripe_account_id','32');
            $table->string('bank_name','256');
            $table->string('account_holder_name','256');
            $table->string('routing_number','256');
            $table->string('account_number','256');
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
        Schema::drop('store_bank_details');
    }
}
