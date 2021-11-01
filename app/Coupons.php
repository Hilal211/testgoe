<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupons extends Model
{
    //
    protected $fillable = [
    	'type',
    	'zip_codes',
    	'store_codes',
    	/*'amount',
    	'amount_condition',*/
    	'code',
    	'image',
    	'start_date',
        'end_date',
    	'coupon_limit',
    	'value_type',
        'value',
        'min_order_amount',
    	'status',
    ];
    public $types = [
        '1'=>'Postal / Zip Code',
        '2'=>'Stores',
        '3'=>'Order Total',
        '4'=>'Any',
    ];
    public $valueTypes = [
        '1'=>'Flat',
        '2'=>'Percentage'
    ];
}
