<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductPriceHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_product_price_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id');
            $table->integer('product_id');
            $table->float('old_price');
            $table->float('new_price');
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
        Schema::drop('store_product_price_logs');
    }
}
