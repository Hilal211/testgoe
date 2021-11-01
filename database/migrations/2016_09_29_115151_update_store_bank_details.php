<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateStoreBankDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('store_bank_details', function ($table) {
            $table->date('dob')->after('account_number');
            $table->date('tos_acceptance_date')->after('dob');
            $table->string('tos_acceptance_ip')->after('tos_acceptance_date');
            $table->string('personal_id_number')->after('tos_acceptance_ip');
            $table->string('document',50)->after('personal_id_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
