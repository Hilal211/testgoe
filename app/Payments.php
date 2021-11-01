<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    //
    protected $fillable = [
    	'user_id',
    	'order_id',
    	'amt_paid_to',
    	'amt_received',
    	'amt_paid_to_store',
    	'comm',
    ];
}
