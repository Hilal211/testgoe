<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreBankDetails extends Model
{
    //
    protected $fillable = [
    	'store_id',
    	'stripe_account_id',
    	'bank_name',
    	'account_holder_name',
    	'routing_number',
        'account_number',
        'dob',
        'tos_acceptance_date',
        'tos_acceptance_ip',
        'personal_id_number',
    	'document',
    	'status',
    ];

    protected $dates = ['dob'];
}
