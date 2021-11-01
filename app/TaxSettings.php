<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaxSettings extends Model
{
    //
    protected $fillable = [
    	'state_id','description','ftax','ptax','total'
    ]; 
}
