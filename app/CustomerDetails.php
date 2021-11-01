<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerDetails extends Model
{
    //
    protected $fillable = [
    	'user_id',
    	'city',
    	'state',
    	'is_subscribed',
    	'zip'
    ];

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }
}
