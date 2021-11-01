<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Crypt;
use Datatables;
use App\User,
    App\roles,
    App\_list,
    App\CustomerDetails,
    App\CartItems,
    App\ShippingDetails;

class CustomerController extends Controller
{
    //
    public function postCustomerList(){
    	$roles = new roles();        

    	// $users = User::Rolebased($roles->rolesIDS['customer'])
    	// 		->select(['id','firstname','lastname','email']);
        /*$users = DB::table('users as u')
                ->leftJoin('shipping_details as sd', 'sd.user_id', '=', 'u.id')
                ->leftJoin('_list as state', 'state.id', '=', 'sd.shipping_state')
                ->leftJoin('_list as city', 'city.id', '=', 'sd.shipping_city')
                ->where('u.role',$roles->rolesIDS['customer'])
                ->groupBy('u.id')
                ->select(
                    'u.id','u.firstname','u.lastname','u.email','state.item_name as state','city.item_name as city'
                )->get();
        $users = collect($users);*/
        $users = User::where(['users.role'=>$roles->rolesIDS['customer']])
                 ->leftJoin('shipping_details as sd', 'sd.user_id', '=', 'users.id')
                 ->leftJoin('_list as state', 'state.id', '=', 'sd.shipping_state')
                 ->leftJoin('_list as city', 'city.id', '=', 'sd.shipping_city')
                 ->groupBy('users.id')
                 ->select(
                     'users.id',
                     DB::raw('CONCAT_WS(" ",users.firstname,users.lastname) as fullname'),
                     'users.email',
                     'state.item_name as state',
                     'city.item_name as city'
                 );
        
		return Datatables::of($users)
                ->editColumn('email', function ($model) {
                    $name = $model->email;
                    return $name;
                })
                ->editColumn('city', function ($model) {
                    $name = $model->city;
                    $id = $model->id;
                    $name .="<div class='action-controls'>";
                    $name .="   <a href='".route("admin.customer.details",\Crypt::encrypt($id))."'><i class='fa fa-pencil'></i></a>"; 
                    $name .="   <a href='javascript:;' onclick='GetDelete(this)' data-id='".$id."'><i class='fa fa-remove'></i></a>";   
                    $name .="</div>";   
                    return $name;
                })
                ->removeColumn('id')
				->make();
    }
    public function deleteShopper($id){
    	$user = User::find($id);
        $custDetails = CustomerDetails::where(['user_id'=>$id])->first();
        $custDetails->delete();
        ShippingDetails::where(['user_id'=>$id])->delete();
        CartItems::where(['user_id'=>$id])->delete();
        $user->delete();
        return response()->json(array(
            "status" => "success",
        ));
    }
    public function getShopper($id){
        $id = Crypt::decrypt($id);
        $user = User::where(['id'=>$id])->first();        
        $ShippingDetails = ShippingDetails::where(['user_id'=>$id])->get();
        $states = _list::where(['list_name'=>'states'])->lists('item_name','id')->all();
        $cities = _list::where(['list_name'=>'cities'])->lists('item_name','id')->all();
        $data = [
            'user_details'=>$user,
            'shipping_details'=>$ShippingDetails,
            "states"=>$states,
            'cities'=>$cities,
        ];
        return view('admin.customer_profile',$data);
    }
}
