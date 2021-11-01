<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderStoreDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_store_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('store_id');
            $table->string('storename',128);
            $table->string('contactnumber',24);
            $table->string('add1',512);
            $table->string('add2',512);
            $table->integer('city');
            $table->integer('state');
            $table->integer('zip');
            $table->string('lat_long',64);
            $table->integer('storetype');
            $table->integer('homedelievery');
            $table->string('legalentityname',512);
            $table->string('yearestablished',4);

            // virtual store fields
            $table->string('legal_add1',512)->nullable();
            $table->string('legal_add2',512)->nullable();
            $table->integer('legal_city')->nullable();
            $table->integer('legal_state')->nullable();
            $table->integer('legal_zip')->nullable();
            $table->integer('is_virtual')->default(0);
            
            $table->integer('gstnumber');
            $table->integer('hstnumber');
            $table->integer('commision');
            $table->integer('commision_on');
            $table->integer('ftax_percentage');
            $table->integer('ptax_percentage');
            $table->string('tax_description',512);
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
        Schema::drop('order_store_details');
    }
}
