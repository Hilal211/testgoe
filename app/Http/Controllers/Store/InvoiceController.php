<?php

namespace App\Http\Controllers\Store;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\orders;
use App\StoreDetails;
use App\OrderStoreDetails;
use App\User;
use App\TaxSettings;
use App\Http\Controllers\Controller;

class InvoiceController extends Controller
{
    //
    public function getInvoice(Request $request,$locale="",$id){
    	/*$order = orders::where(['order_number'=>$id])
    			 ->with('OrderProducts')
    			 ->with('User')
    			 ->first();
		$data = [
    		'order'=>$order
    	];*/
        $Order = orders::where(['order_number'=>$id])->with('OrderProducts')->first();
        $storeDetails = OrderStoreDetails::where(['order_id'=>$Order->id])->first();
        if($storeDetails->is_virtual == 0){
            $add = [$storeDetails->add1,$storeDetails->add2,$storeDetails->cityname.' '.$storeDetails->statename,$storeDetails->zip];
        }else{
            $add = [$storeDetails->zip];
        }
        $orderObj = new orders;
        $user = User::where(['id'=>$Order->user_id])->first();
        $customerName = ($user ? $user->firstname.' '.$user->lastname : "");
        $routeName = ($request->has('name') ? $request->input('name') : "");
        
        $data = [
            'order'=>$Order,
            'storeDetails'=>$storeDetails,
            'address'=>implode('<br/> ',array_filter($add)),
            'obj'=>$orderObj,
            'customer_name'=>$customerName,
            'route_name'=>$routeName
        ];
        $InvoiceHTML = view('common.invoice_modal',$data)->render(); 
        return response()->json(array(
            "status" => "success",
            "message"=>'Data Fetched',
            "InvoiceHTML"=>$InvoiceHTML,
        ));
    }
}
