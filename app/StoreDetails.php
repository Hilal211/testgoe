<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\_list;
class StoreDetails extends Model
{
    protected $fillable = [
        'user_id',
        'storename',
        'contactnumber',
        'add1',
        'add2',
        'state',
        'city',
        'zip',
        'lat_long',
        'storetype',
        'homedelievery',
        'gstnumber',
        'hstnumber',
        'status',
        'legalentityname',
        'yearestablished',

        'legal_add1',
        'legal_add2',
        'legal_city',
        'legal_state',
        'legal_zip',
        'is_virtual',
        
        'commision',
        'commision_on',
        'image',
    ];
    public $sizes = [
        'small_thumbnail'=>['width'=>'16','height'=>'16'],
        'normal_thumbnail'=>['width'=>'45','height'=>'40']
    ];

    public function User(){
    	return $this->belongsTo(User::class);
    }
    public function getCityNameAttribute(){
        return _list::GetName($this->city);
    }
    public function getLegelCityNameAttribute(){
        return _list::GetName($this->legal_city);
    }
    public function getStateNameAttribute(){
        return _list::GetName($this->state);
    }
    public function getLegelStateNameAttribute(){
        return _list::GetName($this->legal_state);
    }
    public function Bank(){
        return $this->belongsTo(StoreBankDetails::class,'id','store_id');
    }
}
