<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderStoreDetails extends Model
{
    //
    protected $fillable = [
        'order_id',
        'store_id',
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
        'ftax_percentage',
        'ptax_percentage',
        'tax_description',
    ];
    public function getCityNameAttribute(){
        return _list::GetName($this->city);
    }
    public function getStateNameAttribute(){
        return _list::GetName($this->state);
    }
}
