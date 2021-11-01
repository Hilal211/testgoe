<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CartItems extends Model
{
    protected $fillable = [
        'user_id',
        'qty',
        'user_type',
        'product_id',
    ];

    public function product(){
    	return $this->hasOne(Product::class,'id','product_id');
    }
}
