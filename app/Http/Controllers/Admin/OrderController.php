<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB,
Datatables,
Functions,
Html,
Form,
App\orders;
use App\Http\Controllers\Controller;

class OrderController extends Controller {

    //
    public function getOrders() {
        return view('admin.orders');
    }

    public function postOrders(Request $request) {
        $orders = orders::leftJoin('users', 'users.id', '=', 'orders.user_id')
                  ->leftJoin('store_details','store_details.id','=','orders.store_id')
                  ->leftJoin('payments','payments.order_id','=','orders.id')
                  ->select(['orders.order_number','users.username as customer',DB::raw('(select count(id) from `orders_details` where order_id=orders.id) as total_items'),'orders.user_id','store_details.id as store_id','store_details.storename','orders.created_at','orders.grand_total','payments.comm','orders.status']);
        $orderObj = new orders;
        /*$orders = DB::table('orders as o')
        ->leftJoin('users as u', 'u.id', '=', 'o.user_id')
        ->leftJoin('store_details as s', 's.id', '=', 'o.store_id')
        ->leftJoin('payments as p', 'p.order_id', '=', 'o.id')
        ->select(
            'o.order_number', DB::raw('(select count(id) from `orders_details` where order_id=o.id) as total_items'), 'o.user_id', 'u.username as customer', 's.id as store_id', 's.storename', 'o.created_at', 'o.grand_total','p.comm', 'o.status'
            )->get();
        $orders = collect($orders);*/
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
        ->editColumn('grand_total', function ($data) {
            $price = Functions::GetPrice($data->grand_total);
            return $price;
        })
        ->editColumn('comm', function ($data) {
            $price = Functions::GetPrice($data->comm);
            return $price;
        })
        ->editColumn('created_at', function ($data) {
            $date = \Carbon::parse($data->created_at)->format('d M Y');
            return $date;
        })
        ->removeColumn('user_id')
        ->removeColumn('store_id')
        ->make();
    }

    public function getPickups(){
        return view('admin.pickups');
    }

    public function postPickups(Request $request){
        $Ids = $request->input('status');
        $orders = DB::table('orders as o')
        ->leftJoin('users as u', 'u.id', '=', 'o.user_id')
        ->leftJoin('_list as city', 'city.id', '=', 'o.shipping_city')
        ->leftJoin('_list as state', 'state.id', '=', 'o.shipping_state')

        ->leftJoin('store_details as store', 'store.id', '=', 'o.store_id')
        ->leftJoin('_list as store_city', 'store_city.id', '=', 'store.city')
        ->leftJoin('_list as store_state', 'store_state.id', '=', 'store.state')

        ->whereIn('o.status', explode(',',$Ids))
        ->where('o.shipped_by','1')
        ->orderBy('o.updated_at', 'desc')
        ->select(
            'o.order_number',
            'u.username',
            DB::raw('(select count(id) from `orders_details` where order_id=o.id) as total_items'),
            'store.storename as storename',
            'store.add1 as store_add1',
            'store.add2 as store_add2',
            'store.zip as store_zip',
            'store_city.item_name as store_city',
            'store_state.item_name as store_state',
            'o.shipping_address',
            'city.item_name as shipping_city',
            'state.item_name as shipping_state',
            'o.shipping_zip',
            'o.invoice_number',
            'o.shipped_by',
            'o.grand_total',
            'o.status'
            );
        //$orders = collect($orders);
        if($Ids=='6'){
            return Datatables::of($orders)
            ->editColumn('order_number', function ($data) {
                $id = $data->order_number;
                $order = '#'.$id;
                $order = "<a href='javascript:;' data-id='".$id."'' onclick='OpenInvoice(this)'>$order</a>";
                return $order;
            })
            ->editColumn('storename', function ($data) {
                $add = [$data->storename,$data->store_add1,$data->store_add2,$data->store_city.' '.$data->store_state,$data->store_zip];
                $address = Html::link("#",$data->storename,['title'=>implode(', ',array_filter($add)),'class'=>'show-tooltip']);
                return $address;
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
            ->editColumn('grand_total', function ($data) {
                $price = Functions::GetPrice($data->grand_total);
                return $price;
            })
            ->removeColumn('username')
            ->removeColumn('store_add1')
            ->removeColumn('store_add2')
            ->removeColumn('store_city')
            ->removeColumn('store_state')
            ->removeColumn('store_zip')
            ->removeColumn('shipping_city')
            ->removeColumn('shipping_state')
            ->removeColumn('shipping_zip')
            ->removeColumn('invoice_number')
            ->removeColumn('shipped_by')
            ->removeColumn('status')
            ->make();
        }else{
            return Datatables::of($orders)
            ->editColumn('order_number', function ($data) {
                $id = $data->order_number;
                $order = '#'.$id;
                $order = "<a href='javascript:;' data-id='".$id."'' onclick='OpenInvoice(this)'>$order</a>";
                return $order;
            })
            ->editColumn('storename', function ($data) {
                $add = [$data->storename,$data->store_add1,$data->store_add2,$data->store_city.' '.$data->store_state,$data->store_zip];
                $address = Html::link("#",$data->storename,['title'=>implode(', ',array_filter($add)),'class'=>'show-tooltip']);
                return $address;
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
            ->editColumn('grand_total', function ($data) {
                $price = Functions::GetPrice($data->grand_total);
                return $price;
            })
            ->addColumn('actions', function ($data) {
                $status = $data->status;
                if($status=='7'){
                   $dataArr = ['new_status'=>'8','old_status'=>$status,'id'=>$data->order_number];
                    /* store doing delivery */
                    if($data->shipped_by=='1'){
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
            ->removeColumn('store_add1')
            ->removeColumn('store_add2')
            ->removeColumn('store_city')
            ->removeColumn('store_state')
            ->removeColumn('store_zip')
            ->removeColumn('shipping_city')
            ->removeColumn('shipping_state')
            ->removeColumn('shipping_zip')
            ->removeColumn('invoice_number')
            ->removeColumn('shipped_by')
            ->removeColumn('status')
            ->make();
        }
    }

    public function changeStatus(Request $request){
        $order = orders::where(['order_number'=>$request->input('id')])->first();
        if($order){
            $newStatus = $request->input('new_status');
            $order->update(['status'=>$newStatus]);
            return response()->json(array(
                "status" => "success",
                "message"=>"Data saved",
            ));
        }else{
            return response()->json(['error' => 'Error msg'], 404);
        }
    }

    public function index()
    {
       
        return view('admin.statOrder');
    }
}
