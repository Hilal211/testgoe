<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductRequest extends Model
{
    //
    protected $fillable = [
    	'store_id',
    	'category',
    	'sub_category',
        'product_name',
    	'description',
    	'unit',
    	'image',
    	'status',
    ];
    public $Status = [
        "1"=>['slug'=>'requested','title'=>'Requested','class'=>'text-info'],
        "2"=>['slug'=>'approved','title'=>'Approved','class'=>'text-success'],
        "3"=>['slug'=>'rejected','title'=>'Rejected','class'=>'text-danger'],
    ];

    public function GetCatName($cat){
        return 'a'.$cat;
    }
}
