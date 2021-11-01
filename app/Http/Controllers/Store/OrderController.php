<?php

namespace App\Http\Controllers\Store;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB,
	Form,
    Html,
	Auth,
    Functions,
    App\orders,
    App\User,
    App\StoreBankDetails,
    App\orders_details,
    App\StoreProducts,
	Emailer,
	Datatables;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    //
    public function postOrders(Request $request){
        $userID = Auth::user()->id;
        $user = User::where(['id'=>$userID])->with('StoreDetails')->first();
        $StoreID = $user->StoreDetails->id;
        if($request->draw>1){
            $lastUpdatedrow = orders::orderBy('updated_at','desc')->first();
        }else{
            $lastUpdatedrow = "";
        }
        //dd($lastUpdatedrow->toArray());
        $Ids = $request->input('status');
    	$orders = DB::table('orders as o')
            ->leftJoin('users as u', 'u.id', '=', 'o.user_id')
            ->leftJoin('_list as city', 'city.id', '=', 'o.shipping_city')
            ->leftJoin('_list as state', 'state.id', '=', 'o.shipping_state')
            ->whereIn('o.status', explode(',',$Ids))
            ->where('o.store_id',$StoreID)
            ->orderBy('o.updated_at', 'desc')
            ->select(
            	'o.order_number',
            	'u.username',
            	DB::raw('(select count(id) from `orders_details` where order_id=o.id) as total_items'),
            	'o.shipping_address',
            	'city.item_name as shipping_city',
            	'state.item_name as shipping_state',
            	'o.shipping_zip',
            	'o.invoice_number',
                'o.sub_total',
                'o.recycle_fee',
                'o.grand_total',
                'o.shipped_by',
                'o.tax',
                'o.shipped_by',
            	'o.status'
        	)->get();
        	$orders = collect($orders);
            //$orders = DB::table('orders as o')->get();
            //dd($orders);
        return Datatables::of($orders)
        		->editColumn('order_number', function ($data) {
                    $id = $data->order_number;
                    $order = '#'.$id;
                    $order = "<a href='javascript:;' data-id='".$id."'' onclick='OpenInvoice(this)'>$order</a>";
                    //$order = Html::link("javascript:;",$order,["data-toggle"=>"control-sidebar"]);
                    return $order;
                })
                ->editColumn('shipping_address', function ($data) {
                    $shortAdd = Functions::GetAddress($data,$type="short");
                    $fullAdd = Functions::GetAddress($data,$type="full");
                    $address = Html::link("#",$shortAdd,['title'=>$fullAdd,'class'=>'show-tooltip']);
                    return $address;
                })
                ->editColumn('total_items', function ($data) {
                    $items = $data->total_items.' items';
                    return $items;
                })
                ->editColumn('sub_total', function ($data) {
                    if($data->shipped_by=='2'){
                        $price = Functions::GetPrice($data->grand_total);
                    }else{
                        $price = Functions::GetPrice($data->sub_total+$data->tax+$data->recycle_fee);
                    }
                    return $price;
                })
                ->addColumn('actions', function ($data) use($lastUpdatedrow) {
                    $status = $data->status;
                    if($status=='1'){
                       $dataArr = ['new_status'=>'4','old_status'=>$status,'id'=>$data->order_number,'blink'=>true];
                	   $html = Form::button('<i class="fa fa-thumbs-o-up"></i>',["type"=>"button","class"=>"btn-default btn btn-xs btn-block show-tooltip change-order-status",'data-json'=>json_encode($dataArr),"title"=>'Approve',"onclick"=>'change_status(this,event)']);

                       $dataArr = ['new_status'=>'3','old_status'=>$status,'id'=>$data->order_number,'blink'=>true];
                       $html .= Form::button('<i class="fa fa-ban"></i>',["type"=>"button","class"=>"btn-default btn btn-xs btn-block show-tooltip change-order-status",'data-json'=>json_encode($dataArr),"title"=>'Reject',"onclick"=>'change_status(this,event)']);
                    }else if($status=='4'){
                        $dataArr = ['new_status'=>'5','old_status'=>$status,'id'=>$data->order_number];
                        $html = Form::button('<i class="fa fa-shopping-cart"></i>',["type"=>"button","class"=>"btn-default btn btn-xs btn-block show-tooltip change-order-status",'data-json'=>json_encode($dataArr),"title"=>'Process',"onclick"=>'change_status(this,event)']);
                    }else if($status=='5'){
                        $dataArr = ['new_status'=>'6','old_status'=>$status,'id'=>$data->order_number];
                        if(@$lastUpdatedrow->order_number==$data->order_number){
                            $dataArr['blink'] = true;
                        }
                        $html = Form::button('<i class="fa fa-gift"></i>',["type"=>"button","class"=>"btn-default btn btn-xs btn-block show-tooltip change-order-status",'data-json'=>json_encode($dataArr),"title"=>'Ready to Ship',"onclick"=>'change_status(this,event)']);
                    }else if($status=='6'){
                        $dataArr = ['new_status'=>'7','old_status'=>$status,'id'=>$data->order_number];
                        if(@$lastUpdatedrow->order_number==$data->order_number){
                            $dataArr['blink'] = true;
                        }
                        $html = Form::button('<i class="fa fa-truck"></i>',["type"=>"button","class"=>"btn-default btn btn-xs btn-block show-tooltip change-order-status",'data-json'=>json_encode($dataArr),"title"=>'Shipped',"onclick"=>'change_status(this,event)']);
                    }else if($status=='7'){
                        $dataArr = ['new_status'=>'8','old_status'=>$status,'id'=>$data->order_number];
                        if(@$lastUpdatedrow->order_number==$data->order_number){
                            $dataArr['blink'] = true;
                        }
                        /* store doing delivery */
                        if($data->shipped_by=='2'){
                            $html = Form::button('<i class="fa fa-check-circle"></i>',["type"=>"button","class"=>"btn-default btn btn-xs btn-block show-tooltip change-order-status",'data-json'=>json_encode($dataArr),"title"=>'Delivered',"onclick"=>'change_status(this,event)']);
                        }else{
                            $html = Form::button('<i class="fa fa-check-circle"></i>',["type"=>"button","class"=>"hide btn-default btn btn-xs btn-block show-tooltip change-order-status",'data-json'=>json_encode($dataArr),"title"=>'Delivered',"onclick"=>'change_status(this,event)']);
                        }
                    }else{
                        $html = "";
                    }
                    return $html;
                })
                ->removeColumn('username')
                ->removeColumn('shipping_city')
                ->removeColumn('shipping_state')
                ->removeColumn('shipping_zip')
                ->removeColumn('tax')
                ->removeColumn('grand_total')
                ->removeColumn('recycle_fee')
                ->removeColumn('invoice_number')
                ->removeColumn('shipped_by')
                ->removeColumn('status')
                ->make();
    }
    public function postOrdersTotal(Request $request){
        $userID = Auth::user()->id;
        $user = User::where(['id'=>$userID])->with('StoreDetails')->first();
        $StoreID = $user->StoreDetails->id;

        $Ids = $request->input('status');
       
        /* admin doing delivery */
        /*$orders = orders::whereIn('status',explode(',',$Ids))
                  ->where('store_id',$StoreID)
                  ->where('shipped_by','1')
                  ->sum('sub_total');*/

        /* store doing delivery */          
        /*$store_delivered_orders = orders::whereIn('status',explode(',',$Ids))
                  ->where('store_id',$StoreID)
                  ->where('shipped_by','2')
                  ->sum('grand_total');*/

        $orders = orders::whereIn('status',explode(',',$Ids))
                  ->where('store_id',$StoreID)
                  ->get();
        $total = 0;
        foreach ($orders as $key => $order) {
            # code...
            if($order->shipped_by=='1'){
                $total += $order->sub_total+$order->tax+$order->recycle_fee;
            }elseif($order->shipped_by=='2'){
                $total += $order->grand_total;
            }
        }
        $orders = Functions::getPrice($total);
        return response()->json(array(
            "status" => "success",
            "total"=>$orders,
        ));
    }
    public function postOrdersStatus(Request $request){
        \Stripe\Stripe::setApiKey(config('theme.STRIPE_API_KEY'));
        \Stripe\Stripe::setApiVersion(config('theme.STRIPE_API_VERSION'));

        $order = orders::where(['order_number'=>$request->input('id')])->first();
        $bank = StoreBankDetails::where(['store_id'=>$order->store_id])->first();
        
        if($order){
            if($order->status=='9'){
                return response()->json(['error' => 'Customer has cancelled this order'], 403);
            }
            if($order->status=='12'){
                return response()->json(['error' => 'Customer has Refunded this order'], 403);
            }
            $newStatus = $request->input('new_status');
            if($newStatus=='4'){
                try {
                    $ch = \Stripe\Charge::retrieve(
                        $order->stripe_charge_id,
                        array("stripe_account" => $bank->stripe_account_id)
                    );
                    $ch->capture();
                }catch (\Stripe\Error\InvalidRequest $e) {
                  $body = $e->getJsonBody();
                  $err  = $body['error'];
                }
            }elseif($newStatus=='3'){
                $orderDetails = Orders_details::where(['order_id'=>$order->id])->get();
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
                
                $refund = \Stripe\Refund::create(
                    array(
                        "charge" => $order->stripe_charge_id,
                    ),
                    array("stripe_account" => $bank->stripe_account_id)
                );

                // customer email
                $customer = User::find($order->user_id);
                if($customer){
                    $data['user'] = $customer;
                    Emailer::SendEmail('customer.order.rejected',$data);
                }
            }elseif($newStatus=='7'){
                // item shipped 
                $customer = User::find($order->user_id);
                if($customer){
                    $data['user'] = $customer;
                    $data['order'] = $order;
                    Emailer::SendEmail('customer.order.shipped',$data);
                }
            }

            $order->update(['status'=>$newStatus]);
            return response()->json(array(
                "status" => "success",
                "message"=>"Data saved",
            ));
        }else{
            return response()->json(['error' => 'Error msg'], 404);
        }
    }
    public function getAllOrders(){
        return view('store.all_orders');
    }
    public function postAllOrders(Request $request){
        $userID = Auth::user()->id;
        $user = User::where(['id'=>$userID])->with('StoreDetails')->first();
        $StoreID = $user->StoreDetails->id;

        $orderObj = new orders;
        /*$orders = DB::table('orders as o')
            ->leftJoin('users as u', 'u.id', '=', 'o.user_id')
            ->leftJoin('payments as p', 'p.order_id', '=', 'o.id')
            ->where('o.store_id',$StoreID)
            ->select(
                'o.order_number',
                DB::raw('(select count(id) from `orders_details` where order_id=o.id) as total_items'),
                'o.user_id',
                'u.username as customer',
                'o.created_at',
                'o.grand_total',
                'p.comm',
                'o.status'
            )->get();
            $orders = collect($orders);*/
            $orders = orders::leftJoin('users', 'users.id', '=', 'orders.user_id')
                  ->where('orders.store_id',$StoreID)
                  ->select([
                        'orders.order_number',
                        DB::raw('(select count(id) from `orders_details` where order_id=orders.id) as total_items'),
                        'orders.user_id',
                        'users.username as customer',
                        'orders.created_at',
                        'orders.sub_total',
                        'orders.shipped_by',
                        'orders.recycle_fee',
                        'orders.grand_total',
                        'orders.tax',
                        'orders.status'
                    ]);
            return Datatables::of($orders)
                    ->editColumn('order_number', function ($data) {
                        $id = $data->order_number;
                        $ids ="<a href='javascript:;' data-id='".$id."' onclick='OpenInvoice(this)'>".$id."</a>"; 
                        return $ids;
                    })
                    ->editColumn('status', function ($data) use($orderObj) {
                        $status = $orderObj->Status[$data->status]['title'];
                        return $status;
                    })
                    ->editColumn('sub_total', function ($data) {
                        if($data->shipped_by=='2'){
                            $price = Functions::GetPrice($data->grand_total);
                        }else{
                            $price = Functions::GetPrice($data->sub_total+$data->tax+$data->recycle_fee);
                        }
                        //$price = $data->sub_total.' '.$data->tax.' '.$data->recycle_fee;
                        return $price;
                    })
                    ->editColumn('created_at', function ($data) {
                        $date = \Carbon::parse($data->created_at)->format('d M Y');
                        return $date;
                    })
                    ->removeColumn('user_id')
                    ->removeColumn('grand_total')
                    ->removeColumn('tax')
                    ->removeColumn('shipped_by')
                    ->removeColumn('recycle_fee')
                    ->removeColumn('store_id')
                    ->make();
    }
}
