<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RelatedProducts extends Model
{
    //
    protected $fillable = [
    	'product_id',
    	'related_product_id',
    ];
}
