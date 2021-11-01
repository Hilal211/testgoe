<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreProductPriceLogs extends Model
{
    //
    protected $table = 'store_product_price_logs';

    protected $fillable = [
    	'store_id',
    	'product_id',
    	'old_price',
    	'new_price',
    ];
}
