<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id');
            $table->string('category',256);
            $table->string('sub_category',256);
            $table->string('product_name',512);
            $table->string('unit',256);
            $table->string('image',50);
            $table->float('min_price');
            $table->float('max_price');
            $table->integer('status');
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
        Schema::drop('product_requests');
    }
}
