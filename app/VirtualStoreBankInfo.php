<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VirtualStoreBankInfo extends Model
{
    //
    protected $table = 'virtual_store_bank_info';

    protected $fillable = [
    	'email',
    	'business_name',
    	'firstname',
    	'lastname',
    	'add1',
    	'add2',
    	'city',
    	'state',
    	'zip',
    ];
}
