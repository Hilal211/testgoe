<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\_category;
use Cookie;
use DB;
use App\_list,
App\ShippingDetails,
App\orders,
App\StoreBankDetails,
App\StoreProducts,
App\orders_details,
App\User,
Emailer,
Functions,
Auth,
App\ProductRequest,
App\CustomerDetails,
App\NewsletterSubscription;

class CommanController extends Controller
{
    public function getSubCategories(Request $request){
    	$catID = $request->input('cat_id');
    	if($catID!='null' && $catID!=''){
            $subCats = _category::where(['parent_id'=>$catID])->lists('category_name','id')->all();
        }else{
            $subCats = "";
        }
         return response()->json(array(
            "status" => "success",
            "sub_cats"=>$subCats,
        ));
    }
    public function getDeleteModal(Request $request){
        $data = [
            "id"=>$request->get('id'),
            "title"=>$request->get('title'),
            "message"=>$request->get('message'),
            "click_function"=>$request->get('event'),
        ];
        return view('admin.layout.delete-modal',$data);
    }
    public function createCookies(Request $request){
        foreach($request->all() as $key => $value){
            Cookie::queue($key,$value,10080);
        }
        sleep(1);
        $zip = Cookie::get('zip');
        $lat = Cookie::get('lat');
        $long = Cookie::get('long');
        $timezone = Cookie::get('timezone');
        return response()->json(array(
            "status" => "success",
            "zip"=>$zip,
            "lat"=>$lat,
            "long"=>$long,
            "timezone"=>$timezone,
        ));
    }
    public function deleteCookies(){
        Cookie::queue(Cookie::forget('zip'));
        Cookie::queue(Cookie::forget('lat'));
        Cookie::queue(Cookie::forget('long'));
        return response()->json(array(
            "status" => "success"
        ));
    }
    public function getCities(Request $request){
        $stateId = $request->input('state_id');
        if($stateId!='null' && $stateId!=''){
            $cities = _list::where(['value_1'=>$stateId])->lists('item_name','id')->all();
        }else{
            $cities = "";
        }
        return response()->json(array(
            "status" => "success",
            "cities"=>$cities,
        ));
    }
    public function ProductSold(){
        $query = DB::statement("UPDATE products set total_sold = (SELECT count(product_id) FROM `orders_details` where product_id=products.id)");
        return response()->json(array(
            "status" => "success",
            "message"=>"Product table updated",
        ));
    }

    public function postRefundOrder(Request $request){
        $orderId = $request->input('order_id');
        \Stripe\Stripe::setApiKey(config('theme.STRIPE_API_KEY'));
        \Stripe\Stripe::setApiVersion(config('theme.STRIPE_API_VERSION'));

        $order = orders::where(['id'=>$orderId])->first();
        if($order->status!='12'){
            $bank = StoreBankDetails::where(['store_id'=>$order->store_id])->first();
            $orderDetails = Orders_details::where(['order_id'=>$orderId])->get();

            foreach($orderDetails as $product){
                $sid = $order->store_id;
                $StoreInveotory = StoreProducts::where(['store_id'=>$sid,'product_id'=>$product->product_id])->first();

                $oldInventory = $StoreInveotory->inventory;
                $newInventory = $oldInventory+$product->product_qty;
                $data = ['inventory'=>$newInventory];
                if($newInventory>'0'){
                    $data['status'] = '1';
                }
                $StoreInveotory->update($data);
            }

            $order->update(['status'=>'12']);
            
            $refund = \Stripe\Refund::create(
                array("charge" => $order->stripe_charge_id),
                array("stripe_account" => $bank->stripe_account_id)
            );

            // customer email
            $customer = User::find($order->user_id);
            if($customer){
                $data['user'] = $customer;
                $data['order'] = $order;
                Emailer::SendEmail('customer.order.refunded',$data);
            }
            return response()->json(array(
                "status" => "success",
                "message"=>"Data saved",
            ));
        }else{
            return response()->json(['error' => 'Order is already refunded'], 403);
        }
    }

    public function PostProductRequestDetails(Request $request){
        $inputs = ProductRequest::where(['id'=>$request->input('id')])->first();
        $image = ($inputs->image!='' ? url(Functions::UploadsPath(config('theme.PRODUCTS_UPLOAD')).$inputs->image) : "");
        $filteredArr = [
            'id'=>["type"=>"text",'value'=>$inputs->id],
            'category'=>["type"=>"select",'value'=>$inputs->category],
            'sub_category'=>["type"=>"select",'value'=>$inputs->sub_category,'wait'=>'1'],
            'product_name'=>["type"=>"text",'value'=>$inputs->product_name],
            'fr_product_name'=>["type"=>"text",'value'=>$inputs->fr_product_name],
            'description'=>["type"=>"textarea",'value'=>$inputs->description],
            'fr_description'=>["type"=>"textarea",'value'=>$inputs->fr_description],
            'unit'=>["type"=>"select",'value'=>$inputs->unit],
            'status'=>["type"=>"checkbox",'checkedValue'=>explode(',',$inputs->status)],
            'recycle_fee'=>["type"=>"text",'value'=>$inputs->recycle_fee],
            'is_taxable'=>["type"=>"checkbox",'checkedValue'=>explode(',',$inputs->is_taxable)],
            'image'=>["type"=>"image",'file'=>$image],
        ];
        return response()->json(array(
            "status" => "success",
            "inputs"=>$filteredArr,
        ));
    }
    public function postSubscribe(Request $request){
        if(Auth::check()){
            $rules = [
                "email"=>'required|email',
                "zip"=>'required'
            ];
            $this->validate($request,$rules);
            $userId = Auth::user()->id;
            $data['is_subscribed'] = '1';
            $data['zip'] = $request->zip;
            $CustDetails = CustomerDetails::where(['user_id'=>$userId])->first();
            $CustDetails->update($data);
            return response()->json(array(
                "status" => "success",
                "message" => trans("keywords.Successfully subscribed to newsletter"),
            ));
        }else{
            $rules = [
                "email"=>'required|email|unique:newsletter_subscriptions',
                "zip"=>'required'
            ];
            $this->validate($request,$rules);
            $inputs = $request->all();
            $inputs['zip'] = strtolower($inputs['zip']);
            $inputs['type'] = '2';
            NewsletterSubscription::create($inputs);
            return response()->json(array(
                "status" => "success",
                "message" => trans("keywords.Successfully subscribed to newsletter"),
            ));
        }
    }
}