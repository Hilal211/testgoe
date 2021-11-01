<?php

namespace App\Http\Controllers\Store;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\StoreShippingSettings;
use App\StoreDetails;


class SettingsController extends Controller
{
    //
    public function index($storeid){
    	$settings = "";
    	$store = StoreDetails::where(['id'=>$storeid])->first();
    	$SettingObj = new StoreShippingSettings;
    	$settings='';
    	if($store->homedelievery=='1'){
    		$settings = StoreShippingSettings::where(['store_id'=>$storeid])->first();
    		if(!$settings){
    			for($i=0;$i<8;$i++){
    				$SettingObj->AmountRange[$i]['store_id'] = $storeid;
    				StoreShippingSettings::create($SettingObj->AmountRange[$i]);
    			}
    			$settings = StoreShippingSettings::where(['store_id'=>$storeid])->get();
			}else{
				$settings = StoreShippingSettings::where(['store_id'=>$storeid])->get();
			}
    	}
    	$data = [
    		'store'=>$store,
    		'settings'=>$settings,
    		'settingsobj'=>$SettingObj
    	];
    	return view('store.settings',$data);
    }
    public function postSettings(Request $request,$storeid){
    	$SettingObj = new StoreShippingSettings;
    	$count = count($SettingObj->AmountRange);
    	$rules = array();
    	for($i=0;$i<$count;$i++){
    		$name = 'charge_amount_'.$i;
    		$rules = array_add($rules,$name,'required');
    	}
    	$this->validate($request,$rules);
    	$settings = StoreShippingSettings::where(['store_id'=>$storeid])->get();
    	for($j=0;$j<$count;$j++){
    		$name = 'charge_amount_'.$j;
    		$data = ['charge_amount'=>$request->input($name)];
			$settings[$j]->update($data);
    	}
    	return redirect()->back()->with('success',"store_registered");
    }
}
