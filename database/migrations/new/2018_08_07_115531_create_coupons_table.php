<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type')->comment('1-zip, 2-store, 3-all');
            $table->text('zip_codes')->nullable()->comment('comma separated zip codes');
            $table->text('store_codes')->nullable()->comment('comma separated store IDs');
            /*$table->float('amount')->nullable()->comment('order amount');
            $table->integer('amount_condition')->nullable()->comment('1-< (less), 2-> (greater), 3-= (equal)');*/
            $table->string('code')->comment('coupon code');
            $table->string('image');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('coupon_limit')->comment('number of times that user can use same coupon');
            $table->integer('value_type')->comment('1-flat, 2-percentage');
            $table->float('value')->comment('coupons value');
            $table->float('min_order_amount')->comment('Min. Order Total');
            $table->integer('status')->comment('1-Active, 0-InActive');
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
        Schema::drop('coupons');
    }
}
