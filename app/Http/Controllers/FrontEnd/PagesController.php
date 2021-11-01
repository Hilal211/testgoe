<?php

namespace App\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\_list,
App\User,
App\ShippingDetails,
Functions,
Emailer,
Validator,
Cookie,
Crypt,
DB,
Mailgun,
App\_category,
App\Product,
App\roles,
App\orders,
App\Settings,
App\CustomerDetails,
App\NonServiceUser,
Carbon,
App\StoreBankDetails,
App\orders_details,
App\StoreProducts,
App\Ratings,
App\StoreDetails,
App\NewsletterSubscription;

class PagesController extends Controller
{
    //
    public function index(Request $request){
        $cats = _category::getAllCategories('1','1','1','home');
        
        $guid = '';
        if(Cookie::get('guid')==''){
            $cookiesValue = round(microtime(true)).round(rand(10000,90000));
            Cookie::queue('guid',$cookiesValue,10080,'','',false,'');
            sleep(1);
        }
        $guid = Cookie::get('guid');

        if(Auth::check()){
            $user = Auth::user();
            if($user->hasRole('admin')){
                return redirect(route('admin.dashboard'));
            }elseif($user->hasRole('store_owner')){
                return redirect(route('store_owner.dashboard'));
            }
        }
       
        $zip = Cookie::get('zip');
        $lat = Cookie::get('lat');
        $long = Cookie::get('long');
        $data = [
            'cats'=>$cats,
            "zip"=>$zip,
            "lat"=>$lat,
            "long"=>$long,
            "guid"=>$guid
        ];
        //session(['order_success' => 'success']);
        //session(['order_id' => '#00000001']);

        //\Session::forget('order_success');
        //\Session::forget('order_id');

        return view('frontend.home',$data);
    }

    public function NormalProductView($locale,$cat,$subcat=""){
        $Id = _category::where(['slug'=>$cat])->first();
        $cats = _category::getAllCategories('1','1','1','home');
        $SelectedCat = _category::getSingleCategoriesProducts($Id->id,'1','1','product');
        $data = [
            'cats'=>$cats,
            'selectedCat'=>$SelectedCat,
        ];
        return view('frontend.generic_product',$data);
    }

    public function getStoreOwner(){
        $states = [""=>""] + _list::where(['list_name'=>'states'])->lists('item_name','id')->all();
        $cities = [""=>""] + _list::where(['list_name'=>'cities'])->lists('item_name','id')->all();
        if(\App::getLocale()=='en'){
            $types = [""=>""] + _list::where(['list_name'=>'storetypes'])->lists('item_name','id')->all();
        }else{
            $types = [""=>""] + _list::where(['list_name'=>'storetypes'])->lists('value_1','id')->all();
        }
        $data = [
            "states"=>$states,
            'cities'=>$cities,
            'types'=>$types,
        ];
        //session(['success' => 'success']);
        //\Session::forget('success');
        return view('frontend.store_owner',$data);
    }

    public function getAbout(){
    	return view('frontend.aboutus');
    }

    public function get404(){
        return view('errors.404');
    }

    public function getTerms(){
    	return view('frontend.terms');
    }

    public function getFAQ(){
        $TitleArray = [
            trans("keywords.How do I order from Goecolo?"),
            trans("keywords.How do I sell my products on Goecolo?"),
            trans("keywords.In which areas is your service available?"),
            trans("keywords.How does it work?"),
            trans("keywords.What are your shipping charges?"),
            trans("keywords.How is Goecolo different from other grocery delivery options?"),
            trans("keywords.Can I return my order after it's delivered?"),
            trans("keywords.Can I compare from any store?"),
            trans("keywords.Can I order without registering?"),
            trans("keywords.Why Goecolo?"),
            trans("keywords.Who Decides on the prices of the products?"),
            trans("keywords.Is there a minimum or maximum i can order?"),
            trans("keywords.What happens if I order beyond the delivery scheduled on the Goecolo.com?"),
            trans("keywords.Can i order from different stores ?"),
            trans("keywords.How far off can you deliver?"),
            trans("keywords.How can I compare with other areas?"),
            trans("keywords.How can I change my address?"),
            trans("keywords.Is there an application?"),
        ];
        $ContentArray = [
            trans("keywords.To order from Goecolo, you need to be registered as Shopper. So click Shopper icon in menu and create your shopper account to get started."),
            trans("keywords.Just create your account by clicking on Store Owner in menu and follow the indicated steps."),
            trans("keywords.Currently we are serving Montreal, Qubec area for store owners who do not provide home delivery. If you are a store owner and you provide home delivery service, you can register from anywhere in Canada."),
            trans("keywords.Add items to your cart and click on store selection.")."<br>".
            trans("keywords.The store selection page lists all stores that carries at least one of your cart items within 3 mile radius of your saved zipcode. Please note that you can access details of items in stock at perticular store by clicking on the counters in 'Qty In Stock' column.")."<br>".
            trans("keywords.The price column displays total price of all items in stock at perticular store. So you can carefully compare prices at your nearby stores."),
            trans("keywords.Goecolo shipping charges are flat fee of CAD $ X + CAD $ X per mile based on your shipping address. Some stores may provide their own delivery services, in which case shipping charge is set by stores and it will vary by your order total and store selection. You can view final shipping charge on Checkout page."),
            trans("keywords.On our site you can compare prices of availble items from all registered stores in 3 mile radius of your saved location. We help you save money & time."),
            trans("keywords.Currently Goecolo does not take returns, however unless already shipped, you have until one hour after the order is placed to cancel your order."),
            trans("keywords.Yes. as long as the store has his items sold and prices updated."),
            trans("keywords.To safeguard your right we have put a system to allow you to keep track of all your orders, so you need to register in order to order from Goecolo"),
            trans("keywords.Goecolo is a platfom that allows you to shop for your favorite products in a convenient, economical and ecological way, Goecolo bases its searh on the actual prices you can have in any store hence, you will benefit from the same price at any store. Goecolo allows you to search for the best price quality match and displays the items in a user friendly way"),
            trans("keywords.Store owner have total control on the prices they sell over Goecolo"),
            trans("keywords.There is no minimum nor maximum as long as the store (if the store is physically delivering your items) or Goecolo deems your order deliverable "),
            trans("keywords.If you chose a delivery time, it will automatically be the next day available time, or anytime you deem convenient as long as it is scheduled list."),
            trans("keywords.Yes, you will be charged separately for delivery charges"),
            trans("keywords.The system displays stores who offer the items that you are looking for within a radius of 3 miles, in consequence Delivery occurs within a three miles radius of your store"),
            trans("keywords.You can simply change the zip code, items, stores and prices will be updated accordingly"),
            trans("keywords.Please click the link on the Change your postal/zip code, a pop up will appear and it will allow you to change your location"),
            trans("keywords.We are currently working to bring you a mobile application.  You can always use the site on a mobile device or tablet as we have designed it to adapt to any possible environment."),
        ];
        $data = [
            'title'=>$TitleArray,
            'content'=>$ContentArray,
        ];
    	return view('frontend.faq',$data);
    }

    public function getPrivacy(){
        return view('frontend.privacy');
    }

    public function getOrders($locale,$id){
        $id = Crypt::decrypt($id);
        $orderObj = new orders;
        $MyOrders = DB::table('orders as o')
        ->leftJoin('ratings as r', 'r.order_id', '=', 'o.id')
        ->leftJoin('store_details as s', 's.id', '=', 'o.store_id')
        ->where('o.user_id',$id)
        ->orderBy('o.id', 'desc')
        ->select(
            'o.*',
            'r.id as rating_id',
            'r.rating as rating',
            's.storename as store_name',
            DB::raw('(select count(id) from `orders_details` where order_id=o.id) as total_items')
            )->Paginate(20);
            //$MyOrders = collect($MyOrders);
        $data = [
            'myorders'=>$MyOrders,
            'obj'=>$orderObj
        ];
        //return view('welcome');
        return view('frontend.orders',$data);
    }

    public function getSettings(){
        return view('frontend.settings');
    }

    public function getContact(){
    	return view('frontend.contact');
    }

    public function getHowItWorks(){
        return view('frontend.how-it-works');
    }

    public function getBuyerRegister(){
        $states = [""=>""] + _list::where(['list_name'=>'states'])->lists('item_name','id')->all();
        $cities = [""=>""] + _list::where(['list_name'=>'cities'])->lists('item_name','id')->all();
        $data = [
            "states"=>$states,
            'cities'=>$cities,
        ];
        return view('frontend.buyer-register',$data);
    }
    public function postBuyerRegister(Request $request){
        $roles = new roles();
        $rules = array();
        $rules = [
            "email"=>'required|email|unique:users',
            "password"=>'required|confirmed|min:6|max:255',
            "state"=>'required',
            "city"=>'required',
            "g-recaptcha-response"=>'required|captcha',
        ];
        if($request->has('subscribe')){
            $rules['zip'] = 'required';
        }
        $messages = [
            'g-recaptcha-response.required'=>trans('keywords.The Captcha field is required.')
        ];
        $validate = $this->validate($request,$rules,$messages);
        $user = new User;
        if($request->input('email') != "")
        {
            $username = explode("@",$request->input('email'));
        }
        $user->username = $username[0];
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->role=$roles->rolesIDS['customer'];
        $user->save();
        $data['user_id'] = $user->id;
        $data['city'] = $request->input('city');
        $data['state'] = $request->input('state');
        if($request->has('subscribe')){
            $data['is_subscribed'] = '1';
            $data['zip'] = $request->zip;
        }

        CustomerDetails::create($data);
        NewsletterSubscription::where(['email'=>$user->email])->delete();

        if ($user) {
            $data = array(
                'user'=>$user
            );
            Emailer::SendEmail('verify.shopper',$data);
            Emailer::SendEmail('admin.new_shopper',$data);
            return response()->json(array(
                "status" => "buyer_registered",
                "message" => trans('keywords.Thank you for registering Now please log on to your email.')
            ));
        }
    }
    public function postStoreOwner(Request $request,$locale,$step){
        $store = new StoreDetails;
        $user = new User;
        $rules = array();
        if($step=='step-1'){
            $rules = [
                "username"=>'required|unique:users',
                "email"=>'required|email|unique:users',
                "password"=>'required|confirmed|min:6|max:255',
                "contact"=>'required'
            ];
            $messages = [
                'username.required'=>trans('keywords.The Store Contact Person Name field is required.'),
            ];
            $this->validate($request,$rules,$messages);
        }else if($step=='step-2'){
            $add1 = $request->input('address_1');
            $add2 = $request->input('address_2');
            $state = $request->input('state');
            $city = $request->input('city');
            $zip = $request->input('zip');
            $rules = [
                "store_name"=>'required',
                "address_1"=>'required',
                "state"=>'required',
                "city"=>'required',
                "zip"=>'required',
                "storetype"=>'required',
                "home_delivery"=>'required',
                "lat_long"=>'required'
            ];
            $messages = array();
            if($request->input('home_delivery')=='0'){
                $rules["state"]='in:11';
                $messages = ['state.in' => trans('keywords.We are currently serving only Montreal area. You must select Qubec and Montreal for Province and city. You can register from other areas if you offer home delivery.')];

                if($request->input('state')=='11'){
                    $rules["city"]='in:727';
                    $messages = ['city.in'=>trans('keywords.We are currently serving only Montreal area. You must select Qubec and Montreal for Province and city. You can register from other areas if you offer home delivery.')];
                }
            }
            $output = Functions::GetLatLong($add1,$add2,_list::getName($city),_list::getName($state),$zip);
            \Log::error(json_encode($output));
            if("ZERO_RESULTS" == $output->status){
                $request->merge(['lat_long' => ""]);
            }else if("OVER_QUERY_LIMIT" == $output->status){
                $request->merge(['lat_long' => ""]);
            }
            else{
                $latitude = $output->results[0]->geometry->location->lat;
                $longitude = $output->results[0]->geometry->location->lng;
                $lat_long = $latitude.','.$longitude;
                session(['lat_long' => $lat_long]);
                $request->merge(['lat_long' => $lat_long]);
            }
            $messages['lat_long.required']=trans('keywords.Invalid Address.');
            $this->validate($request,$rules,$messages);
        }else if($step=='all'){
            $rules = [
                // "gst"=>'required',
                // "hst"=>'required',
                "legal_entity_name"=>'required',
                "year"=>'required|date_format:Y',
                "g-recaptcha-response"=>'required|captcha',
            ];
            $messages = [
                'g-recaptcha-response.required'=>trans('keywords.The Captcha field is required.')
            ];
            $valid = Validator::make($request->all(),$rules,$messages);
            if($valid->fails()){
                $messages = $valid->errors();
                $messages->add($step,'');
                if($request->session()->has('password')){
                    session(['password' => $request->input('password')]);
                }
                return redirect()->back()->withErrors($messages)->withInput();
            }else{
                $settings = Settings::where(['slug'=>'store_default_commision'])->first();
                $user->username = $request->input('username');
                $user->email = $request->input('email');
                if($request->session()->has('password')){
                    $user->password = session('password');
                }else{
                    $user->password = $request->input('password');
                }
                $user->password = bcrypt($user->password);

                $storeObj = [
                    'contactnumber'=>$request->input('contact'),
                    'storename'=>$request->input('store_name'),
                    'add1'=>$request->input('address_1'),
                    'add2'=>$request->input('address_2'),
                    'state'=>$request->input('state'),
                    'city'=>$request->input('city'),
                    'zip'=>$request->input('zip'),
                    'storetype'=>$request->input('storetype'),
                    'homedelievery'=>$request->input('home_delivery'),
                    'legalentityname'=>$request->input('legal_entity_name'),
                    'yearestablished'=>$request->input('year'),
                    'gstnumber'=>$request->input('gst'),
                    'hstnumber'=>$request->input('hst'),
                    'commision'=>$settings->value,
                    'commision_on'=>1,
                ];
                
                $store->contactnumber = $request->input('contact');
                $store->storename = $request->input('store_name');
                $store->add1 = $request->input('address_1');
                $store->add2 = $request->input('address_2');
                $store->state = $request->input('state');
                $store->city = $request->input('city');
                $store->zip = $request->input('zip');

                /*$output = Functions::GetLatLong($store->add1,$store->add2,_list::getName($store->city),_list::getName($store->state),$store->zip);
                if("ZERO_RESULTS" == $output->status){
                    return redirect()->back()->withErrors(array('address'=>"Invalid Address."))->withInput();
                }else if("OVER_QUERY_LIMIT" == $output->status){
                    return redirect()->back()->withErrors(array('address'=>"Something went wrong, Please try again after some time."))->withInput();
                }
                else{
                    $latitude = $output->results[0]->geometry->location->lat;
                    $longitude = $output->results[0]->geometry->location->lng;
                    $lat_long = $latitude.','.$longitude;
                }*/

                $store->lat_long = session('lat_long');
                $store->storetype = $request->input('storetype');
                $store->homedelievery = $request->input('home_delivery');
                $store->legalentityname = $request->input('legal_entity_name');
                $store->yearestablished = $request->input('year');
                $store->gstnumber = $request->input('gst');
                $store->hstnumber = $request->input('hst');
                $store->commision = $settings->value;
                $store->commision_on = 1;
                $user->role="3";
                $user->save();
                $store->user_id = $user->id;
                $store->save();

                \Session::forget('lat_long');

                $add = [$store->add1,$store->add2,$store->cityname.' '.$store->statename,$store->zip];
                $storeAddress = implode('<br/> ',array_filter($add));
                $store->storetype = _list::getName($store->storetype);
                $data = array(
                    'user'=>$user,
                    'store'=>$store,
                    'storeAddress'=>$storeAddress,
                );
                
                /*Mailgun::send('frontend.emails.store_verification', $data, function($message) use ($user) {
                    $message->to($user->email,$user->username)->subject('Welcome to Goecolo! Please verify your account.');
                });*/
                Emailer::SendEmail('store.welcome',$data);
                Emailer::SendEmail('admin.new_store',$data);
                \Session::forget('password');
                return redirect(route('frontend.store_owner'))->with('success',"store_registered");
            }
        }
        return response()->json(array(
            "status" => "success",
            "token" => csrf_token()
        ));
    }

    public function postVerify($locale,$id,$email){
        $alredyVerified = User::where(['email'=>$email])->where(['status'=>1])->first();
        if(!$alredyVerified){
            $user = User::where(['email'=>$email])->where(['status'=>0])->orWhere(['status'=>2])->first();
            if($user){
                $founduser = User::find(\Crypt::decrypt($id));
                $founduser->update(['status'=>1]);            
                if ($founduser) {
                    if($founduser->role=='2'){
                        return redirect(route('login'))->with('success',trans('keywords.Your account has been verified successfully. Please login with your details below to start shopping. Happy Shopping!'));
                    }elseif($founduser->role=='3'){
                        return redirect(route('login'))->with('success',trans('keywords.Your account has been verified successfully. Please login with your details below and fill-in your bank account details from Profile section. We will let you know when your store is ready to receive orders.'));
                    }
                } else {
                    return dd('Unable to verify given account');    
                }
            }else{
                return dd('Unable to verify given account');
            }
        }else{
            return redirect(route('login'))->with('success',trans('keywords.Account is already verified.'));
        }
    }
    public function storeSelection(){
        //$zip = $_COOKIE['zip'];
        
        $cats = _category::getAllCategories('1','1','1');
        $guid = Cookie::get('guid');
       
        $zip = Cookie::get('zip');
        $lat = Cookie::get('lat');
        $long = Cookie::get('long');
        $data = [
            'cats'=>$cats,
            "zip"=>$zip,
            "lat"=>$lat,
            "long"=>$long,
            "guid"=>$guid
        ];  
        return view('frontend.store_selection',$data);
    }
    
    public function cartCheckout($locale,$sid){
        if(Auth::check())
        {
            $id = Auth::user()->id;
        }
        else 
        {
            $arr = array();
            $arr['checkout-error'] = 'Click <a class="popup-text login-link" href="#nav-login-dialog" data-effect="mfp-move-from-top">here</a> to login to perform this operation. Thanks.';
            return redirect()->back()->withErrors(array($arr))->withInput();
            
            # Redirect for login / register while checkout
        }
        $cats = _category::getAllCategories('1','1','1');
        $user = Auth::user();
        //$ShippingDetails = ShippingDetails::where(['user_id'=>$id])->get();
        $ShippingDetails = DB::table('shipping_details as sd')
                           ->where('sd.user_id',$user->id)
                           ->leftJoin('_list as city', 'city.id', '=', 'sd.shipping_city')
                           ->leftJoin('_list as state', 'state.id', '=', 'sd.shipping_state')
                           ->select(
                                'sd.*',
                                'city.item_name as city',                                
                                'state.item_name as state'
                            )->get();
        $ShippingDetails = collect($ShippingDetails);
        $states = [""=>""] + _list::where(['list_name'=>'states'])->lists('item_name','id')->all();
        $cities = [""=>""] + _list::where(['list_name'=>'cities'])->lists('item_name','id')->all();;
        $slotsObj = new orders;
        //$allslots = $slots;
        //$filteredSlots = $slots;
        $allslots = $slotsObj->AllDaySlots;
        $filteredSlots = $slotsObj->AllDaySlots;

        $currentHour =  Functions::convertTimeToLocal(date('Y m d h:i:s a'),'Y m d h:i:s a');
        $currentHour = ltrim($currentHour->format('hA'),'0');
        $nextHour =  Functions::convertTimeToLocal(date('Y m d h:i:s a',strtotime('+1 hour')),'Y m d h:i:s a');
        $nextHour = ltrim($nextHour->format('hA'),'0');
        $hour = $currentHour.' - '.$nextHour;
        //$hour = "4PM - 5PM";
        
        $item = array_search($hour,$allslots);
        for($j=1;$j<10;$j++){
            if(isset($allslots[$j])){
                unset($allslots[$j]);
            }
        }
        
        if (!$item) $item = 21;
        else 
            $item = (($item + 3) < 10) ? 9 : ($item + 3);
        for($i=1;$i<=$item;$i++){
            if(isset($filteredSlots[$i])){
                unset($filteredSlots[$i]);
            }
        }

        $storeDetails = StoreDetails::where(['id'=>$sid])->first();
        //dd($slots);
        //dd(date('H i A'));
        $data = [
            'cats'=>$cats,
            'sid'=>$sid,
            'shipping_details'=>$ShippingDetails,
            "states"=>$states,
            'cities'=>$cities,
            'user_details'=>$user,
            'slots'=>$allslots,
            'filtered_slots'=>$filteredSlots,
            'current_slot'=>['current_hour'=>$currentHour,'next_hour'=>$nextHour],
            'storeDetails'=>$storeDetails,
        ];
        return view('frontend.cart_checkout',$data);
    }
    
    public function getProfile($locale,$id){
        $id = Crypt::decrypt($id);
        $ShippingDetails = ShippingDetails::where(['user_id'=>$id])->get();
        $states = [""=>""] + _list::where(['list_name'=>'states'])->lists('item_name','id')->all();
        $cities = [""=>""] + _list::where(['list_name'=>'cities'])->lists('item_name','id')->all();;
        $custDetails = CustomerDetails::where(['user_id'=>$id])->first();
        $data = [
            'user_details'=>Auth::user(),
            'cust_details'=>$custDetails,
            'shipping_details'=>$ShippingDetails,
            "states"=>$states,
            'cities'=>$cities,
        ];
        return view('frontend.profile',$data);
    }
    public function getTest(Request $request){
        $date = Carbon::now()->format('Y-m-d H:i:s');
        $date1 = Carbon::now(new \DateTimeZone('GMT-4'))->format('Y-m-d H:i:s');
        dump($date1);
        dd($date);
        $data = array();
        Emailer::SendEmail('test',$data);
        dd('a');
        \Stripe\Stripe::setApiKey(config('theme.STRIPE_API_KEY'));
        \Stripe\Stripe::setApiVersion(config('theme.STRIPE_API_VERSION'));
        
        $account = \Stripe\Account::retrieve('acct_19iOvYBkRegvnC1Z');
        //$account->decline_charge_on->cvc_failure = true;
        //$account->save();
        dd($account);
        /*\Stripe\Stripe::setApiKey(config('theme.STRIPE_API_KEY'));
        \Stripe\Stripe::setApiVersion(config('theme.STRIPE_API_VERSION'));
        $account = \Stripe\Account::retrieve("acct_19SnIBEswk9T4yFd");
        $bankobj = \Stripe\Token::create(array(
          "bank_account" => array(
            "account_number" => '000111111116',
            "country" => 'ca',
            "currency" => 'cad',
            "routing_number" => '11000-111',
            "metadata" => ['bank_name'=>'STRIPE CA TEST BANK'],
            )
        ));
        dd($account->external_accounts);
        $account->external_account = $bankobj;
        $account->save();
        dd('a');*/
        /*$products = _category::leftJoin('_categories as sub_cat', 'sub_cat.parent_id', '=', '_categories.id')
                    ->leftJoin('products as p', 'p.subcat_id', '=', 'sub_cat.id')
                    ->select([
                        '_categories.id as id',
                        '_categories.category_name as category_name',
                        '_categories.icon as icon',
                        '_categories.bg_image as bg_image',
                        '_categories.order as order',
                        'sub_cat.id as subcat_id',
                        'sub_cat.category_name as subcat_name',
                        'p.id as product_id',
                        'p.product_name as product_name',
                        'p.image as product_image',
                        'p.min_price as min_price',
                        'p.max_price as max_price',
                    ])
                    ->where('_categories.parent_id','0')
                    ->orderBy('_categories.order', 'asc')
                    ->get();
        $cats = array();
        foreach($products as $p){
            if(array_key_exists($p->category_name,$cats)){

                if(array_key_exists($p->category_name,$cats)){

                    $cats[$p->category_name]['sub_cats'][$p->subcat_name] = 
                    ['id'=>$p->subcat_id,'category_name'=>$p->subcat_name];
                    
                }else{
                    $cats[$p->category_name]['sub_cats']['products'] = 
                    ['id'=>$p->product_id,'product_name'=>$p->product_name,
                    'image'=>$p->product_image,'min_price'=>$p->min_price,'max_price'=>$p->max_price];
                }

            }else{
                $cats[$p->category_name] = ['id'=>$p->id,'order'=>$p->order,'icon'=>$p->icon,'category_name'=>$p->category_name,'bg_image'=>$p->bg_image];
                $cats[$p->category_name]['sub_cats'][$p->subcat_name] = ['id'=>$p->subcat_id,'category_name'=>$p->subcat_name];
            }
        }
        dd($cats);*/

        
        /*$projectObj = new Product;
        $products = Product::get(['id','image']);
        foreach($products as $product){
            $Image = Functions::UploadsPath(config('theme.PRODUCTS_UPLOAD')).$product->image;
            if(\File::exists($Image)){
                chmod($Image,0777);
                foreach($projectObj->sizes as $size){
                    $ImagePath = $Image;
                    $newName = Functions::GetImageName($Image,'-'.$size['width'].'x'.$size['height']);
                    Functions::ResizeImage($ImagePath,$size['width'],$size['height'],$newName);
                    chmod(Functions::GetImageName($Image,'-'.$size['width'].'x'.$size['height']),0777);
                }
            }
        }*/
        

        /*\Stripe\Stripe::setApiKey(config('theme.STRIPE_API_KEY'));
        \Stripe\Stripe::setApiVersion(config('theme.STRIPE_API_VERSION'));
        
        $account = \Stripe\Account::retrieve('acct_191DVrJfpoIPpYzM');
        $account->decline_charge_on->cvc_failure = true;
        $account->save();
        dd($account);*/
        /*try {
            $token = \Stripe\Token::create(array(
              "card" => array(
                "number" => '4000000000000101',
                "exp_month" => '12',
                "exp_year" => '2020',
                "cvc" => '123'
                )
              ));
            $charge = \Stripe\Charge::create(
                array(
                    "amount" => (5*100),
                    "currency" => "cad",
                    "source" => $token,
                    "application_fee" => (2*100),
                    "capture" => false
                    ),
                array("stripe_account" => 'acct_191DVrJfpoIPpYzM')
                );
        }catch(\Stripe\Error\Card $e) {
            $body = $e->getJsonBody();
            $error = $body['error'];
            dd($error);
            //return redirect()->back()->withErrors($error['message'])->withInput();
        }
        dd('a');*/
        /*try {
            $bankobj = \Stripe\Token::create(array(
              "bank_account" => array(
                "account_number" => "000123456789",
                "country" => 'ca',
                "currency" => 'cad',
                "routing_number" => "110000",
                "metadata" => ['bank_name'=>"STRIPE TEST BANK AU"],
              )
            ));

            $account = \Stripe\Account::create(array(
                "managed" => true,
                "country" => "CA",
                "email" => $store->User->email,
                "legal_entity"=>[
                    "type" => "individual",
                    "business_name" => $store->storename,
                    "address" => [
                        'line1'=>$store->add1,
                        'city'=>_list::getName($store->city),
                        'postal_code'=>$store->zip,
                        'state'=>'NB'
                    ]
                ],
              "business_name" => $store->storename,
              "external_account" => $bankobj,
            ));

        } catch (\Stripe\Error\InvalidRequest $e) {
            dd($e);
        }
        dd('a');*/
        //return view('welcome');
        //dd(Cookie::get('lat').' '.Cookie::get('long').' '.Cookie::get('zip'));
        //$user = User::find('7');
        //$user->update(['password'=>bcrypt('123456')]);
        
       /* $data = ['list_name'=>'states','item_name'=>'Toronto','friendly_name'=>str_slug('Toronto','_'),'status'=>'1','value_1'=>'28'];
            _list::create($data);
            dd('a');
        //$address = "Crystal Mall, Kalawad Road, Rajkot, Gujarat 360005"; // Google HQ
        $address = "100 Lynn, Williams St, Toronto, ON M6K 3N6"; // Google HQ
        $prepAddr = str_replace(' ','+',$address);
        $geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
        $output= json_decode($geocode);
        $latitude = $output->results[0]->geometry->location->lat;
        $longitude = $output->results[0]->geometry->location->lng;
        echo $latitude.','.$longitude;
        exit();*/
        //$data
        
        /*$states = [
            'Alberta','British Columbia','Manitoba','New Brunswick',
            'Northwest Trritories','Nova Scotia','Nunavut',
            'NewFoundland and Labrador','Ontario','Prince Edward Island','Quebec','Saskatchewan',
            'Yukon',
        ];
        foreach($states as $state){
            $data = ['list_name'=>'states','item_name'=>$state,'friendly_name'=>str_slug($state,'_'),'status'=>'1'];
            _list::create($data);
        }*/
        
        
        /*$cities = [
            'Airdrie',
            'Brooks',
            'Calgary',
            'Camrose',
            'Chestermere',
            'Cold Lake',
            'Edmonton',
            'Fort Saskatchewan',
            'Grande Prairie',
            'Lacombe',
            'Leduc',
            'Lethbridge',
            'Lloydminster',
            'Medicine Hat',
            'Red Deer',
            'Spruce Grove',
            'St. Albert',
            'Wetaskiwin',
        ];
        foreach($cities as $city){
            $data = ['list_name'=>'cities','item_name'=>$city,'friendly_name'=>str_slug($city,'_'),'status'=>'1','value_1'=>'1'];
            _list::create($data);    
        }*/
        
        /*$cities = [
            'Abbotsford','Armstrong','Burnaby','Campbell River',
            'Castlegar','Chilliwack','Colwood','Coquitlam',
            'Courtenay','Cranbrook','Dawson Creek','Duncan',
            'Enderby','Fernie','Fort St. John','Grand Forks',
            'Greenwood','Kamloops','Kelowna','Kimberley','Langford',
            'Langley','Maple Ridge','Merritt','Nanaimo',
            'Nelson','New Westminster','North Vancouver',
            'Parksville','Penticton','Pitt Meadows',
            'Port Alberni','Port Coquitlam','Port Moody',
            'Powell River','Prince George','Prince Rupert',
            'Quesnel','Revelstoke','Richmond','Rossland','Salmon Arm',
            'Surrey','Terrace','Trail','Vancouver','Vernon',
            'Victoria','West Kelowna','White Rock','Williams Lake',
        ];
        foreach($cities as $city){
            $data = ['list_name'=>'cities','item_name'=>$city,'friendly_name'=>str_slug($city,'_'),'status'=>'1','value_1'=>'2'];
            _list::create($data);    
        }*/
        
        /*$cities = [
            'Brandon','Dauphin','Flin Flon','Morden',
            'Portage la Prairie','Selkirk','Steinbach','Thompson',
            'Winkler','Winnipeg'
        ];
        foreach($cities as $city){
            $data = ['list_name'=>'cities','item_name'=>$city,'friendly_name'=>str_slug($city,'_'),'status'=>'1','value_1'=>'3'];
            _list::create($data);    
        }*/
        
        /*$cities = [
            'Bathurst','Campbellton','Dieppe','Edmundston',
            'Fredericton','Miramichi','Moncton','Saint John'
        ];
        foreach($cities as $city){
            $data = ['list_name'=>'cities','item_name'=>$city,'friendly_name'=>str_slug($city,'_'),'status'=>'1','value_1'=>'4'];
            _list::create($data);    
        }*/
        /*$cities = [
            'Yellowknife','Aklavik','Behchoko','Colville Lake','Deline',
            'Dettah','Enterprise','Fort Good Hope','Fort Liard',
            'Fort McPherson','Fort Providence','Fort Resolution',
            'Fort Simpson','Fort Smith','Gamèti','Hay River Reserve',
            'Hay River','Inuvik','Jean Marie River','Kakisa',
            'Lutselk\'e','Nahanni Butte','Norman Wells',
            'Paulatuk','Sachs Harbour','Sambaa K\'e','Tsiigehtchic','Tuktoyaktuk',
            'Tulita','Ulukhaktok','Wekweeti','Whatì','Wrigley',
        ];
        foreach($cities as $city){
            $data = ['list_name'=>'cities','item_name'=>$city,'friendly_name'=>str_slug($city,'_'),'status'=>'1','value_1'=>'5'];
            _list::create($data);    
        }*/
        /*$cities = [
            'Cape Breton','Halifax','Queens','Annapolis','Antigonish',
            'Colchester','Cumberland','Inverness','Kings',
            'Pictou','Richmond','Victoria',
            'Argyle','Barrington','Chester','Clare',
            'Digby','East Hants','Guysborough','Lunenburg',
            'Shelburne','St. Mary\'s','West Hants',
            'Yarmouth','Amherst','Annapolis Royal','Antigonish','Berwick',
            'Bridgewater','Clark\'s Harbour','Digby','Kentville','Lockeport',
            'Lunenburg','Mahone Bay','Middleton','Mulgrave','New Glasgow',
            'Oxford','Parrsboro','Pictou','Port Hawkesbury','Shelburne',
            'Stellarton','Stewiacke','Trenton','Truro','Westville',
            'Windsor','Wolfville','Yarmouth',
        ];
        foreach($cities as $city){
            $data = ['list_name'=>'cities','item_name'=>$city,'friendly_name'=>str_slug($city,'_'),'status'=>'1','value_1'=>'6'];
            _list::create($data);    
        }*/
        
        /*$cities = [
            'Amherst','Annapolis Royal','Antigonish','Berwick',
            'Bridgewater','Clark\'s Harbour','Digby','Kentville',
            'Lockeport','Lunenburg','Mahone Bay','Middleton',
            'Mulgrave','New Glasgow','Oxford','Parrsboro',
            'Pictou','Port Hawkesbury','Shelburne','Stellarton','Stewiacke','Trenton',
            'Truro','Westville','Windsor','Wolfville','Yarmouth'
        ];
        foreach($cities as $city){
            $data = ['list_name'=>'cities','item_name'=>$city,'friendly_name'=>str_slug($city,'_'),'status'=>'1','value_1'=>'7'];
            _list::create($data);    
        }*/
        
        /*$cities = [
            'Arctic Bay','Arviat','Baker Lake','Bathurst Inlet','Cambridge Bay',
            'Cape Dorset','Chesterfield Inlet','Clyde River','Coral Harbour',
            'Gjoa Haven','Grise Fiord','Hall Beach','Igloolik','Iqaluit','Kimmirut',
            'Kugaaruk','Kugluktuk','Nanisivik','Naujaat','Pangnirtung',
            'Pond Inlet','Qikiqtarjuaq','Rankin Inlet','Resolute',
            'Sanikiluaq','Taloyoak','Umingmaktok','Whale Cove'
        ];
        foreach($cities as $city){
            $data = ['list_name'=>'cities','item_name'=>$city,'friendly_name'=>str_slug($city,'_'),'status'=>'1','value_1'=>'7'];
            _list::create($data);    
        }*/
        /*$cities = [
            'Corner Brook','Mount Pearl','St. John\'s','Admirals Beach','Anchor Point',
            'Appleton','Aquaforte','Arnold\'s Cove','Avondale',
            'Badger','Baie Verte','Baine Harbour','Bauline','Bay Bulls','Bay de Verde',
            'Bay L\'Argent','Bay Roberts','Baytona','Beachside','Bellburns',
            'Belleoram','Birchy Bay','Bird Cove','Bishop\'s Cove',
            'Bishop\'s Falls','Bonavista','Botwood','Branch',
            'Brent\'s Cove','Brighton','Brigus','Bryant\'s Cove',
            'Buchans','Burgeo','Burin','Burlington',
            'Burnt Islands','Campbellton','Cape Broyle','Cape St. George',
            'Carbonear','Carmanville','Cartwright','Centreville-Wareham-Trinity',
            'Chance Cove','Change Islands','Channel-Port aux Basques','Chapel Arm',
            'Charlottetown (Labrador)','Clarenville','Clarke\'s Beach','Coachman\'s Cove',
            'Colinet','Colliers','Come By Chance','Comfort Cove-Newstead',
            'Conception Bay South','Conception Harbour','Conche','Cook\'s Harbour',
            'Cormack','Cottlesville','Cow Head','Cox\'s Cove',
            'Crow Head','Cupids','Daniel\'s Harbour','Deer Lake',
            'Dover','Duntara','Eastport','Elliston',
            'Embree','Englee','English Harbour East','Fermeuse',
            'Ferryland','Flatrock','Fleur de Lys','Flower\'s Cove',
            'Fogo Island','Forteau','Fortune','Fox Cove-Mortier',
            'Fox Harbour','Frenchman\'s Cove','Gallants','Gambo',
            'Gander','Garnish','Gaskiers-Point La Haye','Gaultois',
            'Gillams','Glenburnie-Birchy Head-Shoal Brook','Glenwood','Glovertown',
            'Goose Cove East','Grand Bank','Grand Falls-Windsor','Grand le Pierre',
            'Greenspond','Hampden','Hant\'s Harbour','Happy Adventure',
            'Happy Valley-Goose Bay','Harbour Breton','Harbour Grace','Harbour Main-Chapel\'s Cove-Lakeview',
            'Hare Bay','Hawke\'s Bay','Heart\'s Content','Heart\'s Delight-Islington',
            'Heart\'s Desire','Hermitage-Sandyville','Holyrood','Howley',
            'Hughes Brook','Humber Arm South','Indian Bay','Irishtown-Summerside',
            'Isle aux Morts','Jackson\'s Arm','Keels','King\'s Cove',
            'King\'s Point','Kippens','Labrador City','Lamaline',
            'L\'Anse-au-Clair','L\'Anse au Loup','Lark Harbour','LaScie',
            'Lawn','Leading Tickles','Lewin\'s Cove','Lewisporte',
            'Little Bay','Little Bay East','Little Bay Islands','Little Burnt Bay',
            'Logy Bay-Middle Cove-Outer Cove','Long Harbour-Mount Arlington Heights','Lord\'s Cove','Lourdes',
            'Lumsden','Lushes Bight-Beaumont-Beaumont North','Main Brook','Mary\'s Harbour',
            'Marystown','Massey Drive','McIvers','Meadows',
            'Middle Arm','Miles Cove','Millertown','Milltown-Head of Bay d\'Espoir',
            'Ming\'s Bight','Morrisville','Mount Carmel-Mitchells Brook-St. Catherines','Mount Moriah',
            'Musgrave Harbour','Musgravetown','New Perlican','New-Wes-Valley',
            'Nipper\'s Harbour','Norman\'s Cove-Long Cove','Norris Arm','Norris Point',
            'North River','North West River','Northern Arm','Old Perlican',
            'Pacquet','Paradise','Parkers Cove','Parson\'s Pond',
            'Pasadena','Peterview','Petty Harbour-Maddox Cove','Pilley\'s Island',
            'Pinware','Placentia','Point au Gaul','Point Lance',
            'Point Leamington','Point May','Point of Bay','Pool\'s Cove',
            'Port Anson','Port au Choix','Port au Port East','Port au Port West-Aguathuna-Felix Cove',
            'Port Blandford','Port Hope Simpson','Port Kirwan','Port Rexton',
            'Port Saunders','Portugal Cove South','Portugal Cove-St. Philip\'s','Pouch Cove',
            'Raleigh','Ramea','Red Bay','Red Harbour',
            'Reidville','Rencontre East','Renews-Cappahayden','River of Ponds',
            'Riverhead','Roberts Arm','Rocky Harbour','Roddickton-Bide Arm',
            'Rose Blanche-Harbour le Cou','Rushoon','Sally\'s Cove','Salmon Cove',
            'Salvage','Sandringham','Savage Cove-Sandy Cove','Seal Cove (Fortune Bay)',
            'Seal Cove (White Bay)','Small Point-Adam\'s Cove-Blackhead-Broad Cove','South Brook','South River',
            'Southern Harbour','Spaniard\'s Bay','Springdale','St. Alban\'s',
            'St. Anthony','St. Bernard\'s-Jacques Fontaine','St. Brendan\'s','St. Bride\'s',
            'St. George\'s','St. Jacques-Coomb\'s Cove','St. Joseph\'s','St. Lawrence',
            'St. Lewis','St. Lunaire-Griquet','St. Mary\'s','St. Pauls',
            'St. Shott\'s','St. Vincent\'s-St. Stephen\'s-Peter\'s River','Steady Brook','Stephenville',
            'Stephenville Crossing','Summerford','Sunnyside','Terra Nova',
            'Terrenceville','Tilt Cove','Torbay','Traytown',
            'Trepassey','Trinity','Trinity Bay North','Triton',
            'Trout River','Twillingate','Upper Island Cove','Victoria',
            'Wabana','Wabush','West St. Modeste','Westport',
            'Whitbourne','Whiteway','Winterland','Winterton',
            'Witless Bay','Woodstock','Woody Point','York Harbour',
        ];
        foreach($cities as $city){
            $data = ['list_name'=>'cities','item_name'=>$city,'friendly_name'=>str_slug($city,'_'),'status'=>'1','value_1'=>'8'];
            _list::create($data);    
        }*/
        
        /*$cities = [
            'Barrie','Belleville','Brampton','Brant',
            'Brantford','Brockville','Burlington','Cambridge',
            'Clarence-Rockland','Cornwall','Dryden',
            'Elliot Lake','Greater Sudbury',
            'Guelph','Haldimand County',
            'Hamilton','Kawartha Lakes','Kenora','Kingston',
            'Kitchener','London','Markham',
            'Mississauga','Niagara Falls','Norfolk County',
            'North Bay','Orillia','Oshawa','Ottawa','Owen Sound',
            'Pembroke','Peterborough',
            'Pickering','Port Colborne','Prince Edward County',
            'Quinte West','Sarnia',
            'Sault Ste. Marie','St. Catharines','St. Thomas','Stratford',
            'Temiskaming Shores','Thorold','Thunder Bay',
            'Timmins','Toronto','Vaughan',
            'Waterloo','Welland','Windsor','Woodstock'
        ];
        foreach($cities as $city){
            $data = ['list_name'=>'cities','item_name'=>$city,'friendly_name'=>str_slug($city,'_'),'status'=>'1','value_1'=>'9'];
            _list::create($data);    
        }*/
        
        /*$cities = [
            'Alberton','Borden-Carleton','Charlottetown','Summerside',
            'Cornwall','Georgetown','Kensington','Montague',
            'North Rustico','O\'Leary','Souris','Stratford',
            'Abrams Village','Afton','Alexandra','Annandale-Little Pond-Howe Bay',
            'Bedeque and Area','Belfast','Bonshaw','Brackley',
            'Breadalbane','Brudenell','Cardigan','Central Kings',
            'Clyde River','Crapaud','Darlington','Eastern Kings',
            'Ellerslie-Bideford','Grand Tracadie','Greenmount-Montrose','Hampshire',
            'Hazelbrook','Hunter River','Kingston','Kinkora',
            'Lady Slipper','Linkletter','Lorne Valley','Lot 11 and Area',
            'Lower Montague','Malpeque Bay','Meadowbank','Miltonvale Park',
            'Miminegash','Miscouche','Morell','Mount Stewart',
            'Murray Harbour','Murray River','New Haven-Riverdale','North Shore',
            'North Wiltshire','Northport','Pleasant Grove','Resort Municipality',
            'Sherbrooke','Souris West','St. Felix','St. Louis',
            'St. Nicholas','St. Peters Bay','Tignish','Tignish Shore',
            'Tyne Valley','Union Road','Valleyfield','Victoria',
            'Warren Grove','Wellington','West River','Winsloe South','York',
        ];
        foreach($cities as $city){
            $data = ['list_name'=>'cities','item_name'=>$city,'friendly_name'=>str_slug($city,'_'),'status'=>'1','value_1'=>'10'];
            _list::create($data);    
        }*/
        
        /*$cities = [
            'Acton Vale','Alma','Amos','Amqui','Asbestos','Baie-Comeau','Baie-D\'Urfé','Beaconsfield',
            'Baie-Saint-Paul','Barkmere','Beaconsfield','Beauceville',
            'Beauharnois','Beaupré','Bécancour','Bedford','Belleterre','Beloeil','Berthierville','Blainville',
            'Boisbriand','Bois-des-Filion','Bonaventure','Boucherville','Brome Lake','Bromont','Brossard',
            'Brownsburg-Chatham','Candiac','Cap-Chat','Cap-Santé',
            'Carignan','Carleton-sur-Mer','Causapscal','Chambly',
            'Chandler','Chapais','Charlemagne','Châteauguay',
            'Château-Richer','Chibougamau','Clermont','Coaticook',
            'Contrecoeur','Cookshire-Eaton','Côte Saint-Luc','Coteau-du-Lac',
            'Cowansville','Danville','Daveluyville','Dégelis',
            'Delson','Desbiens','Deux-Montagnes','Disraeli',
            'Dolbeau-Mistassini','Dollard-des-Ormeaux','Donnacona','Dorval',
            'Drummondville','Dunham','Duparquet','East Angus',
            'Estérel','Farnham','Fermont','Forestville',
            'Fossambault-sur-le-Lac','Gaspé','Gatineau','Gracefield',
            'Granby','Grande-Rivière','Hampstead','Hudson',
            'Huntingdon','Joliette','Kingsey Falls','Kirkland',
            'La Malbaie','La Pocatière','La Prairie','La Sarre',
            'La Tuque','Lac-Delage','Lachute','Lac-Mégantic',
            'Lac-Saint-Joseph','Lac-Sergent','L\'Ancienne-Lorette','L\'Assomption',
            'Laval','Lavaltrie','Lebel-sur-Quévillon','L\'Épiphanie',
            'Léry','Lévis','L\'Île-Cadieux','L\'Île-Dorval',
            'L\'Île-Perrot','Longueuil','Lorraine','Louiseville',
            'Macamic','Magog','Malartic','Maniwaki',
            'Marieville','Mascouche','Matagami','Matane',
            'Mercier','Métabetchouan–Lac-à-la-Croix','Métis-sur-Mer','Mirabel',
            'Mont-Joli','Mont-Laurier','Montmagny','Montreal',
            'Montreal West','Montréal-Est','Mont-Saint-Hilaire','Mont-Tremblant',
            'Mount Royal','Murdochville','Neuville','New Richmond',
            'Nicolet','Nicolet','Notre-Dame-de-l\'Île-Perrot','Notre-Dame-des-Prairies',
            'Otterburn Park','Paspébiac','Percé','Pincourt',
            'Plessisville','Pohénégamook','Pointe-Claire','Pont-Rouge',
            'Port-Cartier','Portneuf','Prévost','Princeville',
            'Québec','Repentigny','Richelieu','Richmond',
            'Rimouski','Rivière-du-Loup','Rivière-Rouge','Roberval',
            'Rosemère','Rouyn-Noranda','Saguenay','Saint-Augustin-de-Desmaures',
            'Saint-Basile','Saint-Basile-le-Grand','Saint-Bruno-de-Montarville','Saint-Césaire',
            'Saint-Colomban','Saint-Constant','Sainte-Adèle','Sainte-Agathe-des-Monts',
            'Sainte-Anne-de-Beaupré','Sainte-Anne-de-Bellevue','Sainte-Anne-des-Monts','Sainte-Anne-des-Plaines',
            'Sainte-Catherine','Sainte-Catherine-de-la-Jacques-Cartier','Sainte-Julie','Sainte-Marguerite-du-Lac-Masson',
            'Sainte-Marie','Sainte-Marthe-sur-le-Lac','Sainte-Thérèse','Saint-Eustache',
            'Saint-Félicien','Saint-Gabriel','Saint-Georges','Saint-Hyacinthe',
            'Saint-Jean-sur-Richelieu','Saint-Jérôme','Saint-Joseph-de-Beauce','Saint-Joseph-de-Sorel',
            'Saint-Lambert','Saint-Lazare','Saint-Lin-Laurentides','Saint-Marc-des-Carrières',
            'Saint-Ours','Saint-Pamphile','Saint-Pascal','Saint-Pie',
            'Saint-Raymond','Saint-Rémi','Saint-Sauveur','Saint-Tite',
            'Salaberry-de-Valleyfield','Schefferville','Scotstown','Senneterre',
            'Sept-Îles','Shawinigan','Sherbrooke','Sorel-Tracy',
            'Stanstead','Sutton','Témiscaming','Témiscouata-sur-le-Lac',
            'Terrebonne','Thetford Mines','Thurso','Trois-Pistoles',
            'Trois-Rivières','Valcourt','Val-d\'Or','Varennes',
            'Vaudreuil-Dorion','Victoriaville','Ville-Marie','Warwick',
            'Waterloo','Waterville','Westmount','Windsor',
        ];
        foreach($cities as $city){
            $data = ['list_name'=>'cities','item_name'=>$city,'friendly_name'=>str_slug($city,'_'),'status'=>'1','value_1'=>'11'];
            _list::create($data);    
        }*/
        
        
        /*$cities = [
            'Estevan','Flin Flon','Humboldt','Lloydminster',
            'Martensville','Meadow Lake','Melfort','Melville',
            'Moose Jaw','North Battleford','Prince Albert','Regina',
            'Saskatoon','Swift Current','Warman','Weyburn',
            'Yorkton'
        ];
        foreach($cities as $city){
            $data = ['list_name'=>'cities','item_name'=>$city,'friendly_name'=>str_slug($city,'_'),'status'=>'1','value_1'=>'12'];
            _list::create($data);    
        }*/
        /*$cities = [
            'Carmacks','Dawson','Faro','Haines Junction',
            'Mayo','Teslin','Watson Lake','Whitehorse'
        ];
        foreach($cities as $city){
            $data = ['list_name'=>'cities','item_name'=>$city,'friendly_name'=>str_slug($city,'_'),'status'=>'1','value_1'=>'13'];
            _list::create($data);    
        }*/
        /*
        $cats = ['Home & Office Needs','Grocery','Bakery & Sweets','Beauty & Wellness'];
        foreach($cats as $cat){
            $data = ['list_name'=>'categories','item_name'=>$cat,'friendly_name'=>str_slug($cat,'_'),'status'=>'1'];
            _list::create($data);    
        }
        */
        /*
        $products = [''];
        foreach($products as $product){
            $data = ['list_name'=>'categories','item_name'=>$cat,'friendly_name'=>str_slug($cat,'_'),'status'=>'1'];
            _list::create($data);    
        }
        */
        /*
        $cats = [
            ['name'=>'KG','friendly_name'=>"kilogram"],
            ['name'=>'Lbs','friendly_name'=>"Pound"],
            ['name'=>'L','friendly_name'=>"Liter"],
            ['name'=>'No','friendly_name'=>"Number"],
            ['name'=>'Ml','friendly_name'=>"Milliliter"],
            ['name'=>'Oz','friendly_name'=>"Ounce"],
            ['name'=>'Fl Oz','friendly_name'=>"Fluid Ounce"],
        ];
        foreach($cats as $cat){
            $data = ['list_name'=>'units','item_name'=>$cat['name'],'friendly_name'=>$cat['friendly_name'],'status'=>'1'];
            _list::create($data);    
        }
        $storeTypes = [
            'Grocery Store','Pharmacy Store','Convenience Store','Super Market Store',
            'Department Store','Organic Store','Speciality Store'
        ];
        foreach($storeTypes as $store){
            $data = [
                'list_name'=>'storetypes','item_name'=>$store,'friendly_name'=>str_slug($store,'_'),'status'=>'1'
            ];  
            _list::create($data);
        }*/
    }
    public function postShipping(Request $request,$id){
        $id = Crypt::decrypt($id);
        $ShippingDetails = ShippingDetails::where(['user_id'=>$id])->first();
        if($ShippingDetails){
            $ShippingDetails->update($request->except('_token'));
        }else{
            $inputs = $request->all();
            $inputs['user_id'] = $id;
            ShippingDetails::create($inputs);
        }
        return redirect()->back()->with('success',"Details saved!");
    }
    public function postProfile(Request $request,$locale="en",$id,$area,$type){
        $id = Crypt::decrypt($id);
        $User = User::where(['id'=>$id])->first();
        $CustDetails = CustomerDetails::where(['user_id'=>$id])->first();
        if($area=='personal'){
            $rules = [
            "firstname"=>'required',
            "lastname"=>'required',
            "password"=>'confirmed|min:6|max:15',
            ];
            if($request->has('subscribe')){
                $rules['zip'] = 'required';
            }
            $valid = Validator::make($request->all(),$rules);
            if($valid->fails()){
                $messages = $valid->errors();
                $messages->add($area,'');
                return redirect()->back()->withErrors($messages)->withInput();
            }else{
                if(trim($request->input('password'))!=''){
                    $inputs = $request->except(['_token','password_confirmation']);
                    $inputs['password'] = bcrypt($inputs['password']);
                }else{
                    $inputs = $request->except(['_token','password','password_confirmation']);
                }

                /*  log user data */
                $oldArr = [
                    'firstname'=>$User->firstname,
                    'lastname'=>$User->lastname,
                ];
                $newArr = $request->only(['firstname','lastname']);
                $logText = Functions::getDifferenceLogText($newArr,$oldArr);
                if($logText!=''){
                    $data = [
                    'user'=>$User,
                    'logText'=>$logText,
                    ];
                    Emailer::SendEmail('profile.changed',$data);
                }
                if($request->has('subscribe')){
                    $data['is_subscribed'] = '1';
                    $data['zip'] = $request->zip;
                    $CustDetails->update($data);
                }else{
                    $data['is_subscribed'] = '0';
                    $data['zip'] = '';
                    $CustDetails->update($data);
                }
                
                $User->update($inputs);
            }
        }else if($area='shipping'){
            $rules = [
            "shipping_phone"=>'required',
            "shipping_address"=>'required',
            "shipping_city"=>'required',
            "shipping_state"=>'required',
            "shipping_zip"=>'required',
            "shipping_email"=>'required|email'
            ];
            $valid = Validator::make($request->all(),$rules);
            if($valid->fails()){
                $messages = $valid->errors();
                $messages->add($area,'');
                $messages->add($request->input('counter'),'');
                //return redirect()->back()->withErrors($messages)->withInput();
                return redirect()->back()->withErrors($messages);
            }else{
                //$ShippingDetails = ShippingDetails::where(['user_id'=>$id])->first();
                $oldId = $request->input('id');
                $ShippingDetails = ShippingDetails::where(['id'=>$oldId])->first();
                if($ShippingDetails){
                    $ShippingDetails->update($request->except('_token','counter'));
                }else{
                    $inputs = $request->except(['counter']);
                    $inputs['user_id'] = $id;
                    ShippingDetails::create($inputs);
                }
            }
        }
        if($type=='front'){
            return redirect()->back()->with('success',"$area Details Updated.");
        }else if($type=='admin'){
            //return redirect()->route('admin.shoppers')->with('success',"$area Details Updated.");
            return redirect()->back()->with('success',"$area Details Updated.");
        }
    }
    public function getInvoice($id){
        $Order = orders::where(['order_number'=>$id])->with('OrderProducts')->first();
        $storeDetails = StoreDetails::where(['id'=>$Order->store_id])->first();
        $orderObj = new orders;
        $data = [
        'order'=>$Order,
        'storeDetails'=>$storeDetails,
        'obj'=>$orderObj
        ];
        //dd($data);
        return view('frontend.invoice',$data);
    }
    public function postContactUs(Request $request){
        $rules = [
            "name"=>'required',
            "email"=>'required|email',
            "message"=>'required'
        ];
        $this->validate($request,$rules);
        $data = array(
            'name'=>$request->input('name'),
            'email'=>$request->input('email'),
            'inquiry_message'=>$request->input('message')
        );
        Emailer::SendEmail('contact.us',$data);
        Emailer::SendEmail('customer.contact.us',$data);
        return redirect()->back()->with('success',trans('keywords.Inquiry sent successfully'));
    }
    public function postCancelOrder($locale="en",$id){
        $order = orders::where(['id'=>$id])->first();
        if($order){
            $datetime1 = $order->created_at;
            $now = Carbon::now();

            $minutes = Carbon::now()->diffInMinutes($datetime1);
            
            //$interval = $datetime1->diff($now);
            //dd($datetime1.' '.$now.' '.$minutes);
            
            //dd($datetime1);
            //dd($datetime1.' '.$now.' '.$minutes);

            if($minutes<=60){

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

                \Stripe\Stripe::setApiKey(config('theme.STRIPE_API_KEY'));
                \Stripe\Stripe::setApiVersion(config('theme.STRIPE_API_VERSION'));

                $bank = StoreBankDetails::where(['store_id'=>$order->store_id])->first();
                $refund = \Stripe\Refund::create(
                    array(
                        "charge" => $order->stripe_charge_id,
                        ),
                    array("stripe_account" => $bank->stripe_account_id)
                );

                $order->update(['status'=>'9']);

                return response()->json(array(
                    "status" => "success",
                    "message"=>"Data saved",
                ));
            }else{
                return response()->json(['error' => trans("keywords.Can't cancel order its been more then 1 hours since order is placed, you can only cancel order within 1 hour after placing order.")],403);
            }
        }else{
            return response()->json(['error' => 'Error msg'], 404);
        }
    }
    public function postRateOrder(Request $request){
        $rules = [
            "rating"=>'required|numeric|min:0.5',
        ];
        $this->validate($request,$rules);
        $data = $request->only(['rating','comments','order_id','store_id']);
        $rateId = $request->input('rate_id');
        if($rateId=='0'){
            Ratings::create($data);
        }else{
            $oldRate = Ratings::find($rateId);
            $oldRate->update($data);
        }
        return response()->json(array(
            "status" => "success",
            "message"=>"Data saved",
        ));
    }

    public function getRateOrder($locale,$rate_id){
        $inputs = Ratings::find($rate_id);
        $filteredArr = [
            'comments'=>["type"=>"textarea",'value'=>$inputs->comments],
            'rating'=>["type"=>"text",'value'=>$inputs->rating],
        ];
        return response()->json(array(
            "status" => "success",
            "inputs"=>$filteredArr,
        ));
    }
    public function postNotify(Request $request){
        $rules = [
            "email"=>'required|email|unique:non_service_users',
            "city"=>'required',
        ];
        $validate = $this->validate($request,$rules);

        NonServiceUser::create($request->all());
        return response()->json(array(
            "status" => "success",
            "message"=>"Data saved",
        ));
    }
}
