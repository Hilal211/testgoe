<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NonServiceUser extends Model
{
    //
    protected $fillable = [
    	'city',
    	'email',
    	'user_type',
    ];
}
