<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreProducts extends Model
{
    //
    protected $fillable = [
    	'store_id',
    	'product_id',
    	'price',
    	'inventory',
    	'status',
		'discounted_price'
    ];
}
