<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreShippingSettings extends Model
{
    //
    protected $fillable = [
    	'store_id',
    	'min_amount',
    	'max_amount',
    	'charge_amount',
    ];

    public $AmountRange = [
    	'0'=>['min_amount'=>"0"   , 'max_amount'=>"50"  ],
    	'1'=>['min_amount'=>"51"  , 'max_amount'=>"100" ],
    	'2'=>['min_amount'=>"101" , 'max_amount'=>"150" ],
    	'3'=>['min_amount'=>"151" , 'max_amount'=>"200" ],
    	'4'=>['min_amount'=>"201" , 'max_amount'=>"300" ],
    	'5'=>['min_amount'=>"301" , 'max_amount'=>"400" ],
    	'6'=>['min_amount'=>"401" , 'max_amount'=>"500" ],
    	'7'=>['min_amount'=>"501" , 'max_amount'=>">500"],
    ];
    public $RangeTitle = [
    	'0'=>['title'=>"below $50.00"],
		'1'=>['title'=>"above $50.00"],
		'2'=>['title'=>"above $100.00"],
		'3'=>['title'=>"above $150.00"],
		'4'=>['title'=>"above $200.00"],
		'5'=>['title'=>"above $300.00"],
		'6'=>['title'=>"above $400.00"],
		'7'=>['title'=>"above $500.00"],
    ];
}
