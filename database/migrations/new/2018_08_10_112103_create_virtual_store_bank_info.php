<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVirtualStoreBankInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('virtual_store_bank_info', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->string('business_name',128);
            $table->string('firstname');
            $table->string('lastname');
            $table->string('add1',512);
            $table->string('add2',512);
            $table->integer('city');
            $table->integer('state');
            $table->string('zip');
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
        Schema::drop('virtual_store_bank_info');
    }
}
