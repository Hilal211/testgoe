<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('_list', function (Blueprint $table) {
            $table->increments('id');
            $table->string('list_name',256);
            $table->string('item_name',256);
            $table->string('friendly_name',256);
            $table->string('value_1',256);
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
        Schema::drop('_list');
    }
}
