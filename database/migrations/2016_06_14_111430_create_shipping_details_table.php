<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('shipping_firstname',128);
            $table->string('shipping_lastname',128);
            $table->text('shipping_address');
            $table->string('shipping_city',128);
            $table->string('shipping_state',128);
            $table->string('shipping_zip',128);
            $table->string('shipping_phone',64);
            $table->string('shipping_email',128);
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
        Schema::drop('shipping_details');
    }
}
