<?php
namespace App\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use App\CartItems;
use App\orders;
use App\StoreDetails;
use Mailgun;
use App\orders_details;
use App\User,
App\Product,
App\Settings,
App\StoreProducts,
App\Payments,
App\_list,
App\TaxSettings,
App\OrderStoreDetails,
App\Ratings,
App\RelatedProducts,
App\Coupons,
Emailer,
Cookie,
Carbon,
Functions;
class CartController extends Controller
{
    public function __construct(){
        \Stripe\Stripe::setApiKey(config('theme.STRIPE_API_KEY'));
        \Stripe\Stripe::setApiVersion(config('theme.STRIPE_API_VERSION'));
    }
    public function index(){
        if(Auth::check())
        {
            $userType = "1";
            $userId = Auth::user()->id;
        }
        else 
        {
            $userType = "2";
            //$userId = $_COOKIE['guid'];
            $userId = Cookie::get('guid');
        }
        //dd($userId);
        $cartItems = DB::table('cart_items')
        ->leftJoin('products', 'products.id', '=', 'cart_items.product_id')
        ->leftJoin('_list', '_list.id', '=', 'products.unit')
        ->select('cart_items.id','cart_items.qty','products.product_name','products.fr_product_name','products.description','products.image','products.min_price','_list.item_name','_list.friendly_name')
        ->where([["cart_items.user_id","=",$userId],["cart_items.user_type","=",$userType]])
        ->get();
        $cartItemsNew = array();
        $dir = config('theme.PRODUCTS_UPLOAD');
        foreach($cartItems as $cartItem)
        {
            $image = url(\Functions::UploadsPath($dir).$cartItem->image);
            $data = ['cartitem'=>$cartItem,'image'=>$image];
            $InfoHTML = view('frontend.single-items.cart-description',$data)->render(); 
            $cartItem->info = html_entity_decode($InfoHTML);
            $cartItem->image = url(\Functions::UploadsPath($dir).\Functions::GetImageName($cartItem->image,'-32x32'));
            $cartItem->product_name = \App::getLocale()=='en' ? $cartItem->product_name : $cartItem->fr_product_name;
            array_push($cartItemsNew,$cartItem);
        }
        \Session::forget('order_success');
        
        return json_encode($cartItemsNew);
    }
    public function addCartItem(Request $request){
        if(Auth::check())
        {
            $userType = "1";
            $userId = Auth::user()->id;
        }
        else 
        {
            $userType = "2";
            $userId = Cookie::get('guid');
        }
        $pid = $request->get('pid');
        $isProduct = CartItems::where(['user_id'=>$userId,'product_id'=>$pid])->first();
        if($isProduct){
            $isProduct->update(['qty'=>$isProduct->qty+1]);
        }else{
            $cart = new CartItems;
            $cart->user_id      = $userId;
            $cart->user_type    = $userType;
            $cart->product_id   = $pid;
            $cart->qty          = 1;
            $cart->save();
        }
        return json_encode(array('result'=>'success'));
    }
    public function updateCartItem(Request $request){
        
        $cart = CartItems::find($request->get('cid'));
        if($request->get('opt')=="add")
            $cart->qty++;
        elseif($request->get('opt')=="minus")
            $cart->qty--;
        elseif($request->get('opt')=="value") {

            // RJ - add round() function for the get round figure quantity //
            $request->offsetSet('qty_value', round($request->qty_value));
            $rules = [
                'qty_value'=>'required|integer|min:1'
            ];            
        
            $this->validate($request,$rules);
            $cart->qty = $request->input('qty_value');
        }

        $cart->update();
        return response()->json(array(
            "result" => "success",
        ));
        /*$cart = CartItems::find($request->get('cid'));
        if($request->get('opt')=="add")
            $cart->qty++;
        elseif($request->get('opt')=="minus")
            $cart->qty--;
        elseif($request->get('opt')=="value"){
            $rules = [
                'qty_value'=>'required|integer|min:1'
            ];
            $this->validate($request,$rules);
            $cart->qty = $request->input('qty_value');
        }

        $cart->update();
        return response()->json(array(
            "result" => "success",
        ));*/
    }
    public function deleteCartItem(Request $request){
        $cart = CartItems::find($request->get('cid'));
        $cart->delete();
        return response()->json(array(
            "result" => "success",
            ));
    }
    public function storeSelection(Request $request){
        $lat = Cookie::get('lat');
        $long = Cookie::get('long');
        
        $limit = $request->input('limit');
        $offset = $request->input('offset');
        
        if(Auth::check())
        {
            $userType = "1";
            $userId = Auth::user()->id;
        }
        else 
        {
            $userType = "2";
            $userId = Cookie::get('guid');
        }
        
        $newArr = "";
        $totalCartProduct = DB::table('cart_items')->where("user_id","=",$userId)->count();
        if($lat && $long){
            $distance = '(
            3959 *
            acos(
            cos( radians( '.$lat.') ) *
            cos( radians( SUBSTRING(store_details.lat_long, 1, POSITION("," IN store_details.lat_long) - 1) ) ) *
            cos(
            radians( SUBSTRING(store_details.lat_long, POSITION("," IN store_details.lat_long) + 1) ) - radians( '.$long.' )
            ) +
            sin(radians('.$lat.')) *
            sin(radians(SUBSTRING(store_details.lat_long, 1, POSITION("," IN store_details.lat_long) - 1)))
            )
            ) `distance`';

            $storeProductsCount = DB::select('select 
                                    '.$distance.',store_details.id from `store_products`
                                    left join `store_details` on 
                                    `store_products`.`store_id` = `store_details`.`id` 
                                    where 
                                    `store_products`.`product_id` in 
                                    (SELECT product_id FROM cart_items WHERE user_id = :user_id) 
                                    and `store_details`.`id`!=""
                                    and store_products.status=1
                                    group by `store_products`.`store_id` 
                                    having distance <=3',
                                    array("user_id"=>$userId)
                                );
            $count = count($storeProductsCount);

            $storeProducts = DB::select('select 
                                            '.$distance.',store_details.id,
                                            store_details.add1,store_details.add2,store_details.image,
                                            store_details.zip,store_details.lat_long,
                                            store_details.storename,
                                            store_details.is_virtual,
                                            count(store_products.product_id) as total_product,
                                            sum(store_products.price) as total_price,
                                            GROUP_CONCAT(store_products.product_id) as ids,
                                            GROUP_CONCAT(store_products.price) as prices,
                                            AVG(ratings.rating) as avg_rating
                                            from `store_products` 
                                            left join `store_details` on 
                                            `store_products`.`store_id` = `store_details`.`id` 
                                            left join `ratings` on
                                            `ratings`.`store_id` = store_details.id
                                            where
                                            `store_products`.`product_id` in 
                                            (SELECT product_id FROM cart_items WHERE user_id = :user_id) 
                                            and `store_details`.`id`!=""
                                            and store_products.status=1
                                            and store_products.inventory!=0
                                            group by `store_products`.`store_id`
                                            having distance <=3 
                                            order by `distance` asc 
                                            LIMIT '.$limit.' OFFSET '.$offset.'',
                                            array("user_id"=>$userId)
                                        );
            foreach($storeProducts as $sp){
                $getProducts = StoreProducts::where(['store_id'=>$sp->id])
                                      ->whereIn('product_id', explode(',',$sp->ids))
                                      ->get();
                $storeTotal = 0;
                $sp->total_product = 0;
                foreach($getProducts as $p){
                    $qty = CartItems::where(['product_id'=>$p->product_id,'user_id'=>$userId])->select(['qty'])->first();
                    /* all qty & all products are available */
                    if($p->inventory>=$qty->qty){
                        if(@$sp->store_qty_check!='table-shopping-orange'){
                            $sp->store_qty_check = "table-shopping-success";
                        }
                        $storeTotal = $storeTotal + ($qty->qty*$p->price);
                        $sp->total_product++;
                    }/* partial qty & all products are available */
                    if($p->inventory<$qty->qty){
                        $sp->store_qty_check = "table-shopping-orange";
                        $storeTotal = $storeTotal + ($p->inventory*$p->price);
                        $sp->total_product++;
                    }
                }
                /* all / partial qty & some products are available */
                if(count($getProducts)<$totalCartProduct){
                    $sp->store_qty_check = "table-shopping-red";
                }
                $sp->qty_total = Functions::GetPrice($storeTotal);
                if($sp->avg_rating!=''){
                    $sp->avg_rating = Functions::GetRate($sp->avg_rating);
                }
                $sp->distance = round($sp->distance,2);
                $image = ($sp->image!='' ? $sp->image : 'default.jpg');
                $sp->image = url(Functions::UploadsPath(config('theme.STORE_UPLOAD')).Functions::GetImageName($image,'-45x40'));
                // $newArr[] = $sp;
                $newArr =array();
                array_push($newArr,$sp);
            }
                /*$sp->total_price = Functions::GetPrice($sp->total_price);
                $sp->distance = round($sp->distance,2);
                $idArr = explode(',',$sp->ids);
                $priceArr = explode(',',$sp->prices);
                $storeTotal = 0;
                for($i=0;$i<count($idArr);$i++){
                    $cartItem = CartItems::where(['product_id'=>$idArr[$i],'user_id'=>$userId])->select(['qty'])->first();
                    $storeTotal = $storeTotal + ($cartItem->qty*$priceArr[$i]);
                }
                $sp->qty_total = Functions::GetPrice($storeTotal);

                $image = ($sp->image!='' ? $sp->image : 'default.jpg');
                $sp->image = url(Functions::UploadsPath(config('theme.STORE_UPLOAD')).Functions::GetImageName($image,'-45x40'));
                $newArr[] = $sp;
            }
            $data = array("total_cart_product"=>$totalCartProduct,"storeList"=>$newArr,'user_id'=>$userId,'totcount'=>$count);
            return json_encode($data);*/
            $data = array("total_cart_product"=>$totalCartProduct,"storeList"=>$newArr,'user_id'=>$userId,'totcount'=>$count);
            return json_encode($data);
            //return view('welcome',['sp'=>$newArr]);
        }else{
            $data = array("total_cart_product"=>'0',"storeList"=>'','user_id'=>$userId,'totcount'=>'0');
            return json_encode($data);
        }
    }

    public function storeSelectionWithProduct(Request $request,$locale,$sid){
        if(Auth::check())
        {
            $userType = "1";
            $userId = Auth::user()->id;
        }
        else 
        {
            $userType = "2";
            //$userId = $_COOKIE['guid'];
            $userId = Cookie::get('guid');
        }
        $lat = Cookie::get('lat');
        $long = Cookie::get('long');

        $store = StoreDetails::where(['id'=>$sid])->with('User')->first();
        $explatlong = explode(",",$store->lat_long);
        $totDistance = Functions::distance($lat,$long,$explatlong[0],$explatlong[1],"M");

        $milesShippingCharge = Settings::where(['slug'=>'charges_per_mile'])->first();
        $minimumShippingCharge = Settings::where(['slug'=>'minimum_shipping_charge'])->first();

        //$userId = "1164311413519080";
        $cartItems = DB::table('cart_items')
        ->leftJoin('store_products', 'cart_items.product_id', '=', 'store_products.product_id')
        ->leftJoin('products', 'store_products.product_id', '=', 'products.id')
        ->leftJoin('_list', 'products.unit', '=', '_list.id')
        ->select(DB::raw('cart_items.id,cart_items.qty,store_products.price,store_products.inventory,products.product_name,products.description,products.image,_list.item_name,_list.friendly_name'))
        ->where('cart_items.user_id','=',$userId)
        ->where('cart_items.user_type','=',$userType)
        ->where('store_products.store_id','=',$sid)
        //->whereColumn('store_products.inventory','>=','cart_items.qty')
        ->where('store_products.status','=','1')
        ->get();
        $cartItemsNew = array();
        $subTotal = 0;
        $dir = config('theme.PRODUCTS_UPLOAD');
        foreach($cartItems as $cartItem)
        {
            if($cartItem->qty < $cartItem->inventory){
                $subTotal += $cartItem->qty*$cartItem->price;
                $cartItem->total_product_price = $cartItem->qty*$cartItem->price;
            }else{
                $subTotal += $cartItem->inventory*$cartItem->price;
                $cartItem->total_product_price = $cartItem->inventory*$cartItem->price;
            }
            $cartItem->price = Functions::GetPrice($cartItem->price);

            $cartItem->total_product_price = Functions::GetPrice($cartItem->total_product_price);
            
            $image = url(\Functions::UploadsPath($dir).$cartItem->image);
            $data = ['cartitem'=>$cartItem,'image'=>$image];
            $InfoHTML = view('frontend.single-items.cart-description',$data)->render(); 
            $cartItem->info = html_entity_decode($InfoHTML);
            $cartItem->image = url(\Functions::UploadsPath($dir).\Functions::GetImageName($cartItem->image,'-32x32'));

            array_push($cartItemsNew,$cartItem);
        }
        $subTotal = round($subTotal,2);
        //$shippingCharge = round(($totDistance * $defaultShippingCharge->value) + 2);
        $shippingCharge = round(($milesShippingCharge->value * $totDistance) + $minimumShippingCharge->value
            );
        $grandTotal    =  round($subTotal+$shippingCharge,2);
        if($store->is_virtual == 1){
            /*$add = [
                $store->legal_add1,
                $store->legelcityname.' '.
                $store->legelstatename,
                $store->zip
            ];
            $store->add2 = ($store->legal_add2 ? $store->legal_add2.', ' : "");*/
            $add = [
                $store->zip
            ];
            $store->add2 = "";
        }else{
            $store->add2 = ($store->add2 ? $store->add2.', ' : "");
            $add = [$store->add1,$store->cityname.' '.$store->statename,$store->zip];
        }
        //dd($cartItems);
        $data = array(
            "subTotal"=>Functions::GetPrice($subTotal),
            "shippingCharge"=>Functions::GetPrice($shippingCharge),
            "grandTotal"=>Functions::GetPrice($grandTotal),
            "productList"=>$cartItems,
            'store_address'=>$store->add2.implode('<br/> ',array_filter($add)),
            'store'=>$store,
        );
        return json_encode($data);
    }
    
    public function checkoutFinal(Request $request,$locale,$sid) {
        //dd($request->all());
        $rules = [
            'shipping_firstname'=>'required',
            'shipping_lastname'=>'required',
            'shipping_address'=>'required',
            'shipping_zip'=>'required',
            'card_number'=>'required',
            'card_expiration_month'=>'required',
            'card_expriration_year'=>'required',
            'card_cvc'=>'required',
        ];
        $this->validate($request,$rules);

        $lat = Cookie::get('lat');
        $long = Cookie::get('long');

        if(Auth::check())
        {
            $userId = Auth::user()->id;
        }
        else 
        {
            # Redirect for login / register while checkout
        }
        $store = StoreDetails::where(['id'=>$sid])->with('User')->with('Bank')->first();
        $storeState = TaxSettings::where(['state_id'=>$store->state])->leftJoin('_list', '_list.id', '=', 'tax_settings.state_id')->first();
        $storeArr = $store->toArray();
        $CloneStoreArr = array_except($storeArr,['id','user_id','user','bank','image','status','created_at','updated_at']);
        $CloneStoreArr['store_id'] = $sid;
        $CloneStoreArr['ftax_percentage'] = $storeState->ftax;
        $CloneStoreArr['ptax_percentage'] = $storeState->ptax;
        $CloneStoreArr['tax_description'] = $storeState->description;
        //dd($CloneStoreArr);
        $StripeAcc = ($store->Bank->stripe_account_id!='' ? $store->Bank->stripe_account_id : '');
        # Getting cart item for order and order details
        $cartItems = DB::table('cart_items')
        ->leftJoin('store_products', 'cart_items.product_id', '=', 'store_products.product_id')
        ->leftJoin('products', 'store_products.product_id', '=', 'products.id')
        ->leftJoin('_list', 'products.unit', '=', '_list.id')
        ->select(DB::raw('cart_items.id,cart_items.product_id,cart_items.qty,store_products.price,(cart_items.qty * store_products.price) as total_product_price,products.product_name,products.fr_product_name,products.image,_list.item_name,_list.friendly_name'))
        ->where('cart_items.user_id','=',$userId)
        ->where('cart_items.user_type','=','1')
        ->where('store_products.store_id','=',$sid)
        //->whereColumn('store_products.inventory','>=','cart_items.qty')
        ->where('store_products.status','=','1')
        ->where('store_products.inventory','!=','0');
         $deleteObj = $cartItems;
        
        $ToBeInsertedData = array();
        $ToBeDeletedData = array();
        $cartItems = $cartItems->get();
        $cartItemsNew = array();
        $subTotal = 0;
        DB::beginTransaction();
        foreach($cartItems as $cartItem){
            array_push($cartItemsNew,$cartItem);
            $StoreInveotory = StoreProducts::where(['store_id'=>$sid,'product_id'=>$cartItem->product_id])->first();
            $oldInventory = $StoreInveotory->inventory;
            if($cartItem->qty < $oldInventory){
                $newInventory = $oldInventory-$cartItem->qty;
            }else{
                $newInventory = 0;
                if($cartItem->qty > $oldInventory){
                    $ToBeInsertedData[] = [
                        'user_id'=>$userId,
                        'user_type'=>'1',
                        'product_id'=>$cartItem->product_id,
                        'qty'=>$cartItem->qty - $oldInventory,
                    ];
                }
                //if($cartItem->qty == $oldInventory){
                    $ToBeDeletedData[] = $cartItem->id;
                //}
            }
            //$newInventory = $oldInventory-$cartItem->qty;
            $data = ['inventory'=>$newInventory];
            if($newInventory=='0'){
                //$data['status'] = '0';
            }
            $StoreInveotory->update($data);
        }
        
        //$subTotal = round($subTotal,2);

        // $explatlong = explode(",",$store->lat_long);
        // $totDistance = Functions::distance($lat,$long,$explatlong[0],$explatlong[1],"M");

        // $milesShippingCharge = Settings::where(['slug'=>'charges_per_mile'])->first();
        // $minimumShippingCharge = Settings::where(['slug'=>'minimum_shipping_charge'])->first();

        //$shippingCharge = round(($subTotal*5)/100,2);
        //$shippingCharge = round(($milesShippingCharge->value * $totDistance) + $minimumShippingCharge->value);
        //$grandTotal  = round($subTotal+$shippingCharge,2);
        $shippingCharge = ltrim($request->input('shipping_charge'),'$');
        $subTotal = ltrim($request->input('sub_total'),'$');
        $discount = ltrim($request->input('discount'),'-$');
        $grandTotal = ltrim($request->input('grand_total'),'$');
        $tax = ltrim($request->input('tax_total'),'$');
        $f_tax = ltrim($request->input('ftax_total'),'$');
        $p_tax = ltrim($request->input('ptax_total'),'$');
        $recycle_fee = ltrim($request->input('recycle_fee'),'$');

        $grandTotal = (float)str_replace(',','',$grandTotal);
        $subTotal = (float)str_replace(',','',$subTotal);
        $ActualSubTotal = (float)str_replace(',','',$subTotal);
        $discount = (float)str_replace(',','',$discount);
        $subTotal = $subTotal - $discount;
        $shippingCharge = (float)str_replace(',','',$shippingCharge);
        $tax = (float)str_replace(',','',$tax);
        $f_tax = (float)str_replace(',','',$f_tax);
        $p_tax = (float)str_replace(',','',$p_tax);
        $recycle_fee = (float)str_replace(',','',$recycle_fee);

        
        $Commision = $store->commision;
        if($store->is_virtual == 0){
            if($store->commision_on=='1'){
                $PaidToGoecolo = round(($subTotal*$Commision)/100,2);
            }elseif($store->commision_on=='2'){
                $sub_ship = $subTotal + $shippingCharge;
                $PaidToGoecolo = round(($sub_ship*$Commision)/100,2);
            }
            $PaidToStore = $subTotal+$tax+$recycle_fee;
            $PaidToStore = $PaidToStore-$PaidToGoecolo;
            if($store->homedelievery=='0'){
                $PaidToGoecolo = $PaidToGoecolo + $shippingCharge;
            }else{
                $PaidToStore = $PaidToStore + $shippingCharge;
            }
        } else {
            $PaidToGoecolo = $subTotal+$tax+$recycle_fee+$shippingCharge;
            $PaidToStore = 0;
        }

        $Orderdata = '';

        $order = new Orders;
        //$arr = ['sub'=>$subTotal,'shipping'=>$shippingCharge,'tax'=>$tax,'recycle'=>$recycle_fee,'grand'=>$grandTotal,'store'=>$PaidToStore,'Goecolo'=>$PaidToGoecolo,'actualSubTotal'=>$ActualSubTotal,'discount'=>$discount,'commision'=>$Commision];
        //dd($arr);
        //if(env('APP_ENV')!='local'){
            try {
                $token = \Stripe\Token::create(array(
                  "card" => array(
                    "number" => $request->input('card_number'),
                    "exp_month" => $request->input('card_expiration_month'),
                    "exp_year" => $request->input('card_expriration_year'),
                    "cvc" => $request->input('card_cvc')
                    )
                  ));
                if($store->is_virtual == 1){
                    try{
                        $charge = \Stripe\Charge::create(
                            array(
                                "amount" => ($PaidToGoecolo*100),
                                "currency" => "cad",
                                "source" => $token,
                                "capture" => false
                            ),
                            array("stripe_account" => $StripeAcc)
                        );
                    }catch(\Stripe\Error\Base $e) {
                        DB::rollBack();
                        $body = $e->getJsonBody();
                        $error = $body['error'];
                        return redirect()->back()->withErrors(trans('keywords.'.$error['message']))->withInput();
                    }
                }else{
                    try{
                        $charge = \Stripe\Charge::create(
                            array(
                                "amount" => ($grandTotal*100),
                                "currency" => "cad",
                                "source" => $token,
                                "application_fee" => ($PaidToGoecolo*100),
                                "capture" => false
                            ),
                            array("stripe_account" => $StripeAcc)
                        );
                    }catch(\Stripe\Error\Base $e) {
                        DB::rollBack();
                        $body = $e->getJsonBody();
                        $error = $body['error'];
                        return redirect()->back()->withErrors(trans('keywords.'.$error['message']))->withInput();
                    }
                }
                
                $Orderdata['stripe_charge_id'] = $charge->id;
            }catch(\Stripe\Error\Card $e) {
                DB::rollBack();
                $body = $e->getJsonBody();
                $error = $body['error'];
                return redirect()->back()->withErrors(trans('keywords.'.$error['message']))->withInput();
            }
        //}
        DB::commit();
        $Orderdata['store_id'] = $sid;
        $Orderdata['user_id'] = $userId;
        $Orderdata['sub_total'] = $ActualSubTotal;
        $Orderdata['discount'] = $discount;
        if($request->coupon_applied){
            $Orderdata['coupon_code'] = $request->input('coupon_code');
        }
        $Orderdata['shipping_charge'] = $shippingCharge;
        $Orderdata['grand_total'] = $grandTotal;
        $Orderdata['recycle_fee'] = $recycle_fee;
        $Orderdata['tax'] = $tax;
        $Orderdata['f_tax'] = $f_tax;
        $Orderdata['p_tax'] = $p_tax;
        $Orderdata['shipping_firstname']       = $request->input('shipping_firstname');
        $Orderdata['shipping_lastname']        = $request->input('shipping_lastname');
        $Orderdata['shipping_address']         = $request->input('shipping_address');
        $Orderdata['shipping_apt']             = $request->input('shipping_apt');
        $Orderdata['shipping_city']            = $request->input('shipping_city');
        $Orderdata['shipping_state']           = $request->input('shipping_state');
        $Orderdata['shipping_zip']             = $request->input('shipping_zip');
        $Orderdata['shipping_phone']           = $request->input('shipping_phone');
        $Orderdata['shipping_email']           = $request->input('shipping_email');
        $Orderdata['shipping_note_delivery']           = $request->input('shipping_note_delivery');


        if($request->input('preferred_date')!=''){
            $datetime = new Carbon($request->input('preferred_date'));
            $Orderdata['preferred_date']          = $datetime->format('Y-m-d');
            $Orderdata['preferred_time']          = $request->input('preferred_time');
        }

        if($store->homedelievery=='1'){
            /* home delivery by store*/
            $Orderdata['shipped_by'] = "2";
        }else{
            /* home delivery by Goecolo*/
            $Orderdata['shipped_by'] = "1";
        }

        $Orderdata['status']                  = 1;
        $order = Orders::create($Orderdata);
        $orderNumber = $invoiceNumber = str_pad($order->id, 10, "0", STR_PAD_LEFT);
        $order->order_number = $orderNumber;

        /* updating charge item description with order id */
        $ch = \Stripe\Charge::retrieve(
            $charge->id,
            array("stripe_account" => $StripeAcc)
        );
        $ch->description = env('APP_ENV')." Charge for ".$orderNumber;
        $ch->save();

        $order->invoice_number = $invoiceNumber;
        $order->update();
        $orderDetails = array();
        foreach($cartItemsNew as $cartItem)
        {
            $data = array(  'order_id'=>$order->id, 
                'product_id'=>$cartItem->product_id,
                'product_name'=>$cartItem->product_name,
                'product_price'=>$cartItem->price,
                'product_qty'=>$cartItem->qty,
                'product_unit'=>$cartItem->item_name
                );
            array_push($orderDetails,$data);
        }
        Payments::create([
            'user_id'=>$userId,
            'order_id'=>$order->id,
            'amt_paid_to'=>$sid,
            'amt_received'=>$grandTotal,
            'amt_paid_to_store'=>$PaidToStore,
            'comm'=>$PaidToGoecolo,
        ]);
        Orders_details::insert($orderDetails);
        $CloneStoreArr['order_id'] = $order->id;
        $CloneedStore = OrderStoreDetails::create($CloneStoreArr);
        # Delete users   cart items
        $cart = CartItems::where(array("user_id"=>$userId));
        
        //dd($store->User->email);
        $user = Auth::user();
        $orderObj = new orders;
        if($order->preferred_time!='0000-00-00' && $order->preferred_time!=''){
            $order->preferred_time = $orderObj->AllDaySlots[$order->preferred_time];
            $order->preferred_date = $order->preferred_date;
        }
        if($store->is_virtual == 0){
            $storeAdd = [$store->cityname,$store->statename,$store->zip];
        } else{
            $storeAdd = [$store->legal_zip];
        }
        $data = array(
            'user'=>$user,
            'cartItems'=>$cartItemsNew,
            'orderNumber'=>$orderNumber,
            //'store_details'=>$store,
            'store_details'=>$CloneedStore,
            'store_user'=>$store,
            'order'=>$order,
            'storeAdd'=>implode(', ',array_filter($storeAdd))
        );
        Emailer::SendEmail('customer.order',$data);
        if($store->is_virtual != 1){
            Emailer::SendEmail('store.order',$data);
        }
        Emailer::SendEmail('admin.new_order',$data);
        $deleteObj->delete();
        CartItems::whereIn('id',$ToBeDeletedData)->delete();
        CartItems::insert($ToBeInsertedData);
        $StoreInveotory = StoreProducts::where(['store_id'=>$sid,'inventory'=>0]);
        $StoreInveotory->update(['status'=>0]);
        //return view('welcome');
        return redirect(route('customer.dashboard'))->with(['order_success'=>"success",'order_id'=>'#'.$orderNumber]);
        //exit("Order Created Successfully");
    }

    public function ProductSearch(Request $request){
        $keyword = $request->input('keyword');
        $products = Product::where('product_name','like','%'.$keyword.'%')->get();
        $filteredArr = "";
        foreach($products as $item){
            $filteredArr[] = ['label'=>$item->product_name,'value'=>$item->product_name];
        }
        return response()->json($filteredArr);
        //dd($products);
    }
    public function ProductsWithStores(Request $request,$locale,$sid){ 
        if(Auth::check())
        {
            $userType = "1";
            $userId = Auth::user()->id;
        }
        else 
        {
            $userType = "2";
            //$userId = $_COOKIE['guid'];
            $userId = Cookie::get('guid');
        }
        //dd($userType.' '.$userId);
        
        // $products = DB::select("select * from (select @store_id:='$sid' p) parm , _store_products where store_id !='' and id in (SELECT product_id FROM cart_items where user_id='$userId' and user_type='$userType')");

        $products = DB::table('cart_items')
        ->leftJoin('store_products', 'cart_items.product_id', '=', 'store_products.product_id')
        ->leftJoin('products', 'store_products.product_id', '=', 'products.id')
        ->leftJoin('_list', 'products.unit', '=', '_list.id')
        ->select(DB::raw('
            store_products.price as price,
            store_products.inventory,
            cart_items.qty,
            (cart_items.qty * store_products.price) as total_product_price,
            products.product_name,
            products.id as product_id,
            _list.item_name,
            _list.friendly_name'
        ))
        ->where('cart_items.user_id','=',$userId)
        ->where('cart_items.user_type','=',$userType)
        ->where('store_products.store_id','=',$sid)
        //->whereColumn('store_products.inventory','>=','cart_items.qty')
        ->where('store_products.status','=','1')
        ->where('store_products.inventory','!=','0');
        $products = $products->get();
        $productArr = json_decode(json_encode($products), true);

        $AvailableProducts = array_column($productArr,'product_id');
        $NotAvailableProducts = CartItems::where(['user_id'=>$userId])->with('product')->whereNotIn('product_id',$AvailableProducts)->get();
        
        $products = collect($products);
        $StoreDetails = StoreDetails::where(['id'=>$sid])->first();
        if($StoreDetails->is_virtual == 0){
            $add = [$StoreDetails->add1,$StoreDetails->add2,$StoreDetails->cityname.' '.$StoreDetails->statename,$StoreDetails->zip];
        }else{
            $add = [$StoreDetails->zip];
        }
        $data = [
            'StoreDetails'=>$StoreDetails,
            'address'=>implode('<br/> ',array_filter($add)),
            'products'=>$products,
            'store_class'=>$request->input('class'),
            'not_available_products'=>$NotAvailableProducts
        ];
        $ModalHTML = view('frontend.single-items.store-product-price',$data)->render(); 
        return response()->json(array(
            "status" => "success",
            "products"=>$products,
            "HTML"=>$ModalHTML,
        ));
    }
    public function RelatedProductsWithStores(Request $request,$locale,$sid, $pid){
        $products = DB::table('related_products')
                    ->leftJoin('store_products', 'related_products.related_product_id', '=', 'store_products.product_id')
                    ->leftJoin('products', 'related_products.related_product_id', '=', 'products.id')
                    ->select(DB::raw('
                        related_product_id,
                        related_products.product_id,
                        products.product_name,
                        store_products.price,
                        store_products.inventory
                    '))
                    ->where('related_products.product_id','=',$pid)
                    ->where('store_products.store_id','=',$sid);
        $products = $products->get();
        
        return response()->json(array(
            "status" => "success",
            "products"=>$products,
        ));
    }
    public function getStoreRatings(Request $request,$locale,$sid){
        $storeRatings = Ratings::leftJoin('orders as o', 'o.id', '=', 'ratings.order_id')
                        ->leftJoin('users as u', 'u.id', '=', 'o.user_id')
                        ->select([
                            'ratings.rating',
                            'ratings.store_id',
                            'ratings.comments',
                            'u.firstname',
                        ])
                        ->where(['ratings.store_id'=>$sid])
                        ->get();
        $StoreDetails = StoreDetails::where(['id'=>$sid])->first();
        $StoreDetails->image = Functions::UploadsPath(config('theme.STORE_UPLOAD')).Functions::GetImageName($StoreDetails->image,'-45x40');
        $add = [$StoreDetails->add1,$StoreDetails->add2,$StoreDetails->cityname.' '.$StoreDetails->statename,$StoreDetails->zip];
        $data = [
            'ratings'=>$storeRatings,
            'address'=>implode(', ',array_filter($add)),
            'StoreDetails'=>$StoreDetails
        ];
        $ModalHTML = view('frontend.single-items.ratings-modal',$data)->render(); 
        return response()->json(array(
            "status" => "success",
            "HTML"=>$ModalHTML,
        ));
    }
    public function getShipping(Request $request){
        if(Auth::check())
        {
            $userType = "1";
            $userId = Auth::user()->id;
        }
        else 
        {
            $userType = "2";
            //$userId = $_COOKIE['guid'];
            $userId = Cookie::get('guid');
        }

        $lat = $request->input('lat');
        $long = $request->input('long');
        $sid = $request->input('sid');
        $store = StoreDetails::where(['id'=>$sid])->first();
        if($store->is_virtual == 0){
            $storeState = TaxSettings::where(['state_id'=>$store->state])->leftJoin('_list', '_list.id', '=', 'tax_settings.state_id')->first();
        } else if($store->is_virtual == 1){
            $storeState = TaxSettings::where(['state_id'=>$store->legal_state])->leftJoin('_list', '_list.id', '=', 'tax_settings.state_id')->first();
        }
        $TaxPercantage = $storeState->total;
        //dd($TaxPercantage);

        $cartItems = DB::table('cart_items')
        ->leftJoin('store_products', 'cart_items.product_id', '=', 'store_products.product_id')
        ->leftJoin('products', 'store_products.product_id', '=', 'products.id')
        ->leftJoin('_list', 'products.unit', '=', '_list.id')
        ->select(
            DB::raw('cart_items.id,cart_items.qty,store_products.inventory,store_products.price,(cart_items.qty * store_products.price) as total_product_price,
                products.product_name,
                products.image,
                products.is_taxable,
                products.recycle_fee,
                _list.item_name,
                _list.friendly_name
        '))
        ->where('cart_items.user_id','=',$userId)
        ->where('cart_items.user_type','=',$userType)
        ->where('store_products.store_id','=',$sid)
        //->whereColumn('store_products.inventory','>=','cart_items.qty')
        ->where('store_products.status','=','1')
        ->get();
        $cartItemsNew = array();
        $subTotal = 0;
        $tax = 0;
        $ftax = 0;
        $ptax = 0;
        $recycle_fee = 0;
        $taxArr = array();
        foreach($cartItems as $cartItem){
            if($cartItem->qty < $cartItem->inventory){
                $newTotal = $cartItem->qty*$cartItem->price;
                $subTotal += $newTotal;
                $cartItem->total_product_price = $newTotal;

                $recycle_fee += $cartItem->qty*$cartItem->recycle_fee;
            }else{
                $newTotal = $cartItem->inventory*$cartItem->price;
                $subTotal += $newTotal;
                $cartItem->total_product_price = $newTotal;

                $recycle_fee += $cartItem->inventory*$cartItem->recycle_fee;
            }
            
            if($cartItem->is_taxable=='1'){
                $TotalAmount = $cartItem->total_product_price;
                $CalculatedTax = ($TotalAmount*$TaxPercantage) / 100;
                $taxArr[]['tax'] = $cartItem->id.' '.$cartItem->is_taxable.' '.$CalculatedTax;
                $tax += $CalculatedTax;

                $ftax += ($TotalAmount*$storeState->ftax) / 100;
                $ptax += ($TotalAmount*$storeState->ptax) / 100;
            }
            //dd($cartItem);
            
        }

        $subTotal = round($subTotal,2);
        $actualSubTotal = round($subTotal,2);
        $ftax = floor(($ftax*100))/100;
        $ptax = floor(($ptax*100))/100;
        $tax = $ftax+$ptax;

        // apply coupon code
        $coupon = Coupons::where(['code'=>$request->coupon])->first();
        $date = Carbon::now(new \DateTimeZone('GMT-4'))->format('Y-m-d');
        $apply = false;
        $couponError = "";
        if($coupon && $coupon->status == 1){
            $usedCount = Orders::where(['coupon_code'=>$coupon->code])->where(['user_id'=>$userId])->count();

            if($usedCount < $coupon->coupon_limit){
                if($subTotal >= (int)$coupon->min_order_amount){
                    if($date >= $coupon->start_date && $date <= $coupon->end_date){
                        if($coupon->type==1){
                            $zip = substr($request->zip,0,3);
                            $zips = explode(',',$coupon->zip_codes);
                            if(in_array($zip, $zips)){
                                $apply = true;
                            }
                        }else if($coupon->type==2){
                            $sid = $request->sid;
                            $storeIds = explode(',',$coupon->store_codes);
                            if(in_array($sid,$storeIds)){
                                $apply = true;
                            }
                        }else if($coupon->type==3){
                            $apply = true;
                        }
                    }else{
                        $couponError = trans('keywords.Sorry, this coupon is expired.');
                    }
                }else{
                    $couponError = trans('keywords.Sorry, order subtotal must be minimum').' '.$coupon->min_order_amount.' '.trans('keywords.to apply this coupon.');    
                }
            }else{
                //$couponError = 'Sorry, Coupon can be used only '.$coupon->coupon_limit.' times';
                $couponError = 'Sorry, this coupon has been redeemed maximum allowed number of times.';
            }
        }else{
            if($request->coupon !=''){
                $couponError = trans('keywords.Sorry, invalid coupon code.');
            }
        }
        if($apply){
            if($coupon->value_type == '1'){
                // flat value
                $discount = $coupon->value;
            }else if($coupon->value_type == '2'){
                // percentage value
                $per = $coupon->value;
                $discount = round(($subTotal*$per)/100,2);
            }
            $subTotal = $subTotal - $discount;
        }
        if($couponError == '' && !$apply && $request->coupon !=''){
            $couponError = trans('keywords.Sorry, this coupon is not applicable for this order.');
        }
        //$recycle_fee = floor(($recycle_fee*100))/100;

        //dd($tax.' '.$ftax.' '.$ptax);
        
        if($store->homedelievery!='1'){
            $explatlong = explode(",",$store->lat_long);

            $milesShippingCharge = Settings::where(['slug'=>'charges_per_mile'])->first();
            $minimumShippingCharge = Settings::where(['slug'=>'minimum_shipping_charge'])->first();

            $toDistance = Functions::distance($lat,$long,$explatlong[0],$explatlong[1],"M");
            $shippingCharge = round(($milesShippingCharge->value * $toDistance) + $minimumShippingCharge->value
                );

            $grandTotal = round($subTotal+$shippingCharge+$tax+$recycle_fee,2);
        }else{
            $toDistance=0;
            $shippingCharge=0;
            $grandTotal=0;
            
            $explatlong = explode(",",$store->lat_long);
            $toDistance = Functions::distance($lat,$long,$explatlong[0],$explatlong[1],"M");
            
            $charge=DB::select("SELECT * FROM `store_shipping_settings` where store_id = $sid and $subTotal BETWEEN min_amount and max_amount");
            if($charge){
                $charge = $charge[0];
                $shippingCharge = $charge->charge_amount;
                $grandTotal = round($subTotal+$shippingCharge+$tax+$recycle_fee,2);
            }else{
                $shippingCharge = 0;
                $grandTotal = round($subTotal+$shippingCharge+$tax+$recycle_fee,2);
            }
        }
        $data = array(
            "toDistance"=>$toDistance,
            //"subTotal"=>Functions::GetPrice($subTotal),
            "actualSubTotal"=>Functions::GetPrice($actualSubTotal),
            "subTotal"=>Functions::GetPrice($subTotal),
            "grandTotal"=>Functions::GetPrice($grandTotal),
            "shippingCharge"=>Functions::GetPrice($shippingCharge),
            "ftax_percantage"=>$storeState->ftax.'%',
            "ptax_percantage"=>$storeState->ptax.'%',
            "tax_desc"=>$storeState->item_name.' ('.$storeState->description.') Tax',
            "ftax"=>'$'.number_format($ftax,2),
            "ptax"=>'$'.number_format($ptax,2),
            "tax"=>'$'.number_format($tax,2),
            "recycle_fee"=>'$'.number_format($recycle_fee,2),
            'taxArr'=>$taxArr,
            'coupon_applied'=>$apply,
            'coupon_error'=>$couponError
        );
        if($apply){
            $data['discount'] = '-'.Functions::GetPrice($discount);
            $data['coupon'] = $coupon->code;
        }
        return response()->json($data);
    }
    public function cartCopy(Request $request,$type){
        $id = $request->input('id');
        $order = orders::find($id);
        
        $userId = Auth::user()->id;
        $CartItems = orders_details::where(['order_id'=>$id])->get();
        foreach($CartItems as $item){

            $pid = $item->product_id;
            $isProduct = CartItems::where(['user_id'=>$userId,'product_id'=>$pid])->first();
            if($isProduct){
                $isProduct->update(['qty'=>$isProduct->qty + $item->product_qty]);
            }else{
                $cart = new CartItems;
                $cart->user_id      = $userId;
                $cart->user_type    = '1';
                $cart->product_id   = $item->product_id;
                $cart->qty          = $item->product_qty;
                $cart->save();
            }

        }
        if($type=='copy'){
            return response()->json(array(
                "status" => "success",
                "message"=>'Data Saved',
            ));
        }else{
            return response()->json(array(
                "status" => "success",
                "message"=>route("frontend.cart-checkout",[\App::getLocale(),$order->store_id]),
            ));
        }
    }
    public function applyCoupon(Request $request){
        $coupon = Coupons::where(['code'=>$request->coupon])->first();
        $date = Carbon::now()->format('Y-m-d');
        $subTotal = ltrim($request->input('sub_total'),'$');
        $grandTotal = ltrim($request->input('grand_total'),'$');
        $apply = false;
        if($coupon){
            if($subTotal < $coupon->min_order_amount){
                // amount is not valid
                return response()->json(array(
                    "success" => false,
                    "message" => 'Sub total should be greater/equal to coupon minimum order amount',
                ));
            }
            if($date >= $coupon->start_date && $date <= $coupon->end_date){
                if($coupon->type==1){
                    $zip = substr($request->zip,0,3);
                    $zips = explode(',',$coupon->zip_codes);
                    if(in_array($zip, $zips)){
                        $apply = true;
                    }
                }else if($coupon->type==2){
                    $sid = $request->sid;
                    $storeIds = explode(',',$coupon->store_codes);
                    if(in_array($sid,$storeIds)){
                        $apply = true;
                    }
                }else if($coupon->type==3){
                    $apply = true;
                }
            }else{
                return response()->json(array(
                    "success" => false,
                    "message" => 'Coupon expired',
                ));
            }
        }else{
            return response()->json(array(
                "success" => false,
                "message" => 'Coupon not found',
            ));
        }
        if($apply){
            if($coupon->value_type == '1'){
                // flat value
                $discount = $coupon->value;
            }else if($coupon->value_type == '2'){
                // percentage value
                $per = $coupon->value;
                $discount = round(($subTotal*$per)/100,2);
            }
            $grandTotal = $grandTotal - $discount;
            return response()->json(array(
                "success" => true,
                "message" => 'apply coupon',
                "discount" => '-'.Functions::GetPrice($discount),
                "coupon" => $coupon->code,
                "grand_total" => $grandTotal,
            ));
        }else{
            return response()->json(array(
                "success" => false,
                "message" => 'coupon condition not satisfy',
            ));
        }
    }
}
?>