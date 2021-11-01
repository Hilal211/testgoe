<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Settings;
use App\_list;

class SettingsController extends Controller
{
    //
    public function index(){
    	//$areas = Settings::all();
        $commitionAreas = Settings::where(['slug'=>'store_default_commision'])->get();
        $defaultCharge = Settings::where(['slug'=>'charges_per_mile'])->first();
        $minimumShipping = Settings::where(['slug'=>'minimum_shipping_charge'])->first();
        $storeTypes = _list::where(['list_name'=>'storetypes'])->get();
        $units = _list::where(['list_name'=>'units'])->get();
        $data = [
          'commitionareas'=>$commitionAreas,
          'defaultCharge'=>$defaultCharge,
          'minimumShipping'=>$minimumShipping,
          'storetypes'=>$storeTypes,
          'units'=>$units
        ];

        return view('admin.settings',$data);
    }
    public function postArea(Request $request,$slug){
    	$rules = [$slug=>"required"];
      if($slug=='store_default_commision'){
          $rules[$slug] = 'required|numeric';
      }
      $this->validate($request,$rules);
      $area = Settings::where(['slug'=>$slug])->first();
      $area->update(['value'=>$request->input($slug)]);
      return redirect(route('admin.settings'))->with('success',"Data Saved.");
    }

    public function postSlugArea(Request $request){
        $rules = [
            'charges_per_mile'=>"required|numeric",
            'minimum_shipping_charge'=>"required|numeric"
        ];
        $this->validate($request,$rules);
        $areadefaultSlug = Settings::where(['slug'=>'charges_per_mile'])->first();
        $areaminimumShipping = Settings::where(['slug'=>'minimum_shipping_charge'])->first();

        $areadefaultSlug->update(['value'=>$request->input('charges_per_mile')]);
        $areaminimumShipping->update(['value'=>$request->input('minimum_shipping_charge')]);

        return redirect(route('admin.settings'))->with('success',"Data Saved.");
    }
    public function postStoretype(Request $request){
        $messsages = array(
          "item_name.required"=>"Store Type is required",
          "value_1.required"=>"French Store Type is required",
          "value_1.unique"=>"French Store Type has already been taken.",
          "item_name.unique"=>"Store Type has already been taken.",
        );
        $id = ($request->input('id')=='0' ? 'NULL' : $request->input('id'));
        $rules = [
            "item_name"=>'required|unique:_list,item_name,'.$id.',id,list_name,storetypes',
            "value_1"=>'required|unique:_list,value_1,'.$id.',id,list_name,storetypes',
        ];
        $this->validate($request,$rules,$messsages);
        $StoreType = _list::where(['id'=>$request->input('id')])->first();
        if($StoreType){
          $StoreType->update($request->only(['item_name','value_1']));
          return response()->json(array(
            "status" => "success",
            "message"=>'old',
            "item"=>$StoreType,
          ));
        }else{
          $inputs = $request->all();
          $inputs['list_name'] = 'storetypes';
          $inputs['friendly_name'] = str_slug($inputs['item_name'],'_');
          $inputs['status'] = '1';
          $StoreType = _list::create($inputs);
          return response()->json(array(
            "status" => "success",
            "message"=>'new',
            "item"=>$StoreType,
          ));
        }
    }
    public function getListItem($id){
      $inputs = _list::where(['id'=>$id])->first();
      $filteredArr = [
        'id'=>["type"=>"text",'value'=>$inputs->id],
        'item_name'=>["type"=>"text",'value'=>$inputs->item_name],
        'friendly_name'=>["type"=>"text",'value'=>$inputs->friendly_name],
        'value_1'=>["type"=>"text",'value'=>$inputs->value_1]
      ];
      return response()->json(array(
        "status" => "success",
        "inputs"=>$filteredArr,
      ));
    }
    public function deleteListItem($id){
      $item = _list::where(['id'=>$id])->first();
      $item->delete();
      return response()->json(array(
        "status" => "success",
      ));
    }
    public function postUnit(Request $request){
      $messsages = array(
          "item_name.required"=>"Short Unit Name is required",
          "friendly_name.required"=>"Unit Name is required",
          "item_name.unique"=>"Short Unit Name has already been taken.",
          "friendly_name.unique"=>"Unit Name has already been taken.",
      );
      $id = ($request->input('id')=='0' ? 'NULL' : $request->input('id'));
      $rules = [
          "item_name"=>'required|unique:_list,item_name,'.$id.',id,list_name,units',
          "friendly_name"=>'required|unique:_list,friendly_name,'.$id.',id,list_name,units',
      ];
      $this->validate($request,$rules,$messsages);
      $Unit = _list::where(['id'=>$request->input('id')])->first();
      if($Unit){
        $Unit->update($request->only(['item_name','friendly_name']));
        return response()->json(array(
          "status" => "success",
          "message"=>'old',
          "item"=>$Unit,
        ));
      }else{
        $inputs = $request->all();
        $inputs['list_name'] = 'units';
        $inputs['status'] = '1';
        $Unit = _list::create($inputs);
        return response()->json(array(
            "status" => "success",
            "message"=>'new',
            "item"=>$Unit,
        ));
      }
    }
}
