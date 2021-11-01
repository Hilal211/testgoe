<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class roles extends Model
{
    //
    protected $fillable = [
        'role', 'slug'
    ];

    protected $table = 'roles';

    /*public $rolesIDS = [
    	'admin'=>'1',
    	'customer'=>'2',
    	'store'=>'3'
    ];*/
    // admin, store_owner, customer
    public $rolesIDS = [
        'admin'=>'1',
        'customer'=>'2',
        'store'=>'3'
    ];

    public function users(){
        return $this->belongsTo(User::class,'role');
    }
}
