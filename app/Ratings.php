<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ratings extends Model
{
    //
    protected $fillable = [
    	'store_id','order_id','rating','comments'
    ];
}
