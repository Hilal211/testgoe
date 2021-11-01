<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class _list extends Model
{
    //
    protected $fillable = [
    	'list_name',
    	'item_name',
    	'friendly_name',
    	'value_1',
    	'status',
    ];
    protected $table = '_list';
    
    public static function getName($id){
        $name = self::find($id);
        if($name){
            return $name->item_name;
        }else{
            return "";
        }
    }
    public static function getExtraValue($id){
		$name = self::find($id);
		if($name){
			return $name->value_1;
		}else{
			return "";
		}
	}
}
