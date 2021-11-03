<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Statistics extends Model
{
    protected $fillable = [
    	'user_id',
        'step'
    ];
}
