<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShippingDetails extends Model
{
    //
    protected $fillable = [
    	'user_id',
        'shipping_address',
    	'shipping_apt',
    	'shipping_city',
    	'shipping_state',
    	'shipping_zip',
    	'shipping_phone',
    	'shipping_email'
    ];
}
