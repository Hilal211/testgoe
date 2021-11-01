<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\_list;
use App\TaxSettings;

class TaxController extends Controller
{
    //
    public function index(){
    	$states = TaxSettings::leftJoin('_list', '_list.id', '=', 'tax_settings.state_id')->get();
    	$data = [
    		'states'=>$states
    	];
    	return view('admin.tax',$data);
    }
    public function postTax(Request $request){
    	$states = TaxSettings::leftJoin('_list', '_list.id', '=', 'tax_settings.state_id')->get();
    	$rules = array();
    	$msgs = array();
    	foreach($states as $state){
            $name = 'ftax_'.$state->id;
    		$rules = array_add($rules,$name,'required|numeric');
    		$msgs[''.$name.'.required'] = $state->item_name.' Federal tax is required';
            $msgs[''.$name.'.numeric'] = $state->item_name.' Federal tax is invalid';

            $name = 'ptax_'.$state->id;
            $rules = array_add($rules,$name,'required|numeric');
            $msgs[''.$name.'.required'] = $state->item_name.' Provience tax is required';
            $msgs[''.$name.'.numeric'] = $state->item_name.' Provience tax is invalid';
    	}
    	$this->validate($request,$rules,$msgs);
    	foreach($states as $state){
            $ftax = 'ftax_'.$state->id;
            $ptax = 'ptax_'.$state->id;
            $desc = 'description_'.$state->id;
    		$data = [
                'ftax'=>$request->input($ftax),
                'ptax'=>$request->input($ptax),
                'description'=>$request->input($desc),
                'total'=>$request->input($ftax)+$request->input($ptax)
            ];
			$state->update($data);
    	}
    	return redirect()->back()->with('success',"Details updated");
    }
}
