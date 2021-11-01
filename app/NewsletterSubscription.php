<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsletterSubscription extends Model
{
    //
    protected $fillable = [
    	'email',
    	'zip',
    	'type',
    ];

    public $types = [
    	'1'=>'customer',
    	'2'=>'guest',
    ];
}
