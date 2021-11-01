<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\_list;

class orders extends Model
{
    //
    protected $fillable = [
        'order_number','invoice_number',
        'store_id','user_id','f_tax','p_tax','tax','recycle_fee',
        'sub_total','discount','coupon_code','shipping_charge',
        'grand_total','shipping_firstname',
        'shipping_lastname','shipping_address','shipping_apt',
        'shipping_city','shipping_state',
        'shipping_zip','shipping_phone',
        'shipping_email','preferred_date',
        'preferred_time','shipped_by',
        'status','stripe_charge_id',
    ];
    public $Status = [
        "1"=>['slug'=>'placed','title'=>'Placed','class'=>'text-success'],
        "2"=>['slug'=>'approved','title'=>'Approved','class'=>'text-success'],
        "3"=>['slug'=>'rejected','title'=>'Rejected','class'=>'text-danger'],
        "4"=>['slug'=>'noticed','title'=>'Approved','class'=>'text-info'],
        "5"=>['slug'=>'processing','title'=>'Processing','class'=>'text-info'],
        "6"=>['slug'=>'ready_to_ship','title'=>'Ready to ship','class'=>'text-success'],
        "7"=>['slug'=>'shipped','title'=>'Shipped','class'=>'text-success'],
        "8"=>['slug'=>'delivered','title'=>'Delivered','class'=>'text-success'],
        "9"=>['slug'=>'cancellation_requested','title'=>'Cancelled','class'=>''],
        "10"=>['slug'=>'cancellation_approved','title'=>'Cancellation approved','class'=>''],
        "11"=>['slug'=>'cancellation_rejected','title'=>'Cancellation rejected','class'=>''],
        "12"=>['slug'=>'refunded','title'=>'Refunded','class'=>'']
    ];

    public $slots = [
        ''=>'',
        '1'=>'9AM - 10AM',
        '2'=>'10AM - 11AM',
        '3'=>'11AM - 12PM',
        '4'=>'12PM - 1PM',
        '5'=>'1PM - 2PM',
        '6'=>'2PM - 3PM',
        '7'=>'3PM - 4PM',
        '8'=>'4PM - 5PM',
        '9'=>'5PM - 6PM',
        '10'=>'6PM - 7PM',
        '11'=>'7PM - 8PM',
        '12'=>'8PM - 9PM'
    ];

    public $AllDaySlots = [
        ''=>'',
        '1'=>'12AM - 1AM',
        '2'=>'1AM - 2AM',
        '3'=>'2AM - 3AM',
        '4'=>'3AM - 4AM',
        '5'=>'4AM - 5AM',
        '6'=>'5AM - 6AM',
        '7'=>'6AM - 7AM',
        '8'=>'7AM - 8AM',
        '9'=>'8AM - 9AM',
        '10'=>'9AM - 10AM',
        '11'=>'10AM - 11AM',
        '12'=>'11AM - 12PM',
        '13'=>'12PM - 1PM',
        '14'=>'1PM - 2PM',
        '15'=>'2PM - 3PM',
        '16'=>'3PM - 4PM',
        '17'=>'4PM - 5PM',
        '18'=>'5PM - 6PM',
        '19'=>'6PM - 7PM',
        '20'=>'7PM - 8PM',
        '21'=>'8PM - 9PM'
//        '22'=>'9PM - 10PM'
//        '23'=>'10PM - 11PM'
//        '24'=>'11PM - 12AM'
    ];

    protected $dates = ['preferred_date'];
    
    public function OrderProducts(){
    	return $this->hasMany(orders_details::class, 'order_id','id');
    }
    public function User(){
    	return $this->hasOne(User::class, 'id','user_id');
    }
    public function getSubTotalPriceAttribute(){
    	return '$'.number_format($this->sub_total, 2);
    }
    public function getShippingPriceAttribute(){
        return '$'.number_format($this->shipping_charge, 2);
    }
    public function getGrandTotalPriceAttribute(){
        return '$'.number_format($this->grand_total, 2);
    }
    public function getCityNameAttribute(){
        return _list::GetName($this->shipping_city);
    }
    public function getStateNameAttribute(){
        return _list::GetName($this->shipping_state);
    }
}
