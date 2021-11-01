<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use DB;
use Datatables;
use App\Http\Controllers\Controller;
use App\StoreDetails,
App\User,
App\Product,
App\orders,
App\NonServiceUser,
App\Ratings,
Form,
Html,
Emailer,
Functions,
App\_list,
App\NewsletterSubscription,
App\CustomerDetails,
App\roles;
use Illuminate\Filesystem\Filesystem;

class PagesController extends Controller
{
    //
    public function index(){
        $StoreCount = StoreDetails::where(['is_virtual'=>'0'])->count();
        $CustomerCount = User::Rolebased('2')->count();
        $ProductCount = Product::count();
        $ProductCount = Product::count();
        $OrderCount = orders::count();

        $stores = StoreDetails::orderBy('id', 'desc')->where(['is_virtual'=>0])->get(['id','add1','add2','city','state','zip','storename','lat_long']);
        $pinArr = array();
        foreach($stores as $store){
            list($lat,$long) = explode(',',$store->lat_long);
            $add = [$store->add1,$store->add2,$store->cityname,$store->statename,$store->zip];
            $pinArr[] = [
                $store->storename.' <br>'.implode(', ',array_filter($add)),
                $lat,
                $long
            ];
        }
        $data = [
            'stores'=>$StoreCount,
            'customers'=>$CustomerCount,
            'product'=>$ProductCount,
            'order'=>$OrderCount,
            'pins'=>json_encode($pinArr),
        ];
        //dd($data);
        return view('admin.home',$data);
    }

    public function getLogin(){
    	return view('admin.login');
    }

    public function getShoppers(){
        return view('admin.customers');
    }

    public function getSettings(){
        return view('admin.settings');
    }
    public function getMap(){
        return view('admin.map');
    }

    public function getRequestedUser(){
        return view('admin.requested_users');
    }

    public function postRequestedUser(){
        $users = NonServiceUser::leftJoin('_list','_list.id', '=', 'non_service_users.city')
                  ->orderBy('non_service_users.id', 'desc')
                  ->select([
                        'non_service_users.email',
                        '_list.item_name as city',
                        'non_service_users.user_type',
                    ]);

        return Datatables::of($users)
            ->editColumn('user_type', function ($data) {
                return ($data->user_type=='1' ? "Customer" : "Store");
            })
            ->make();
    }

    public function getProfile(){
        $data = [
            'user'=>Auth::user()
        ];
        return view('admin.profile',$data);
    }

    public function postProfile(Request $request){
        $User = Auth::user();
        $rules = [
            "email"=>'required|email|unique:users,email,'.$User->id,
            "password"=>'confirmed|min:5|max:15',
        ];
        $this->validate($request,$rules);
        if(trim($request->input('password'))!=''){
            $inputs = $request->only(['password','email']);
            $inputs['password'] = bcrypt($inputs['password']);
        }else{
            $inputs = $request->except(['_token','password','password_confirmation']);
        }
        $User->update($inputs);
        return redirect()->back()->with('success',"Profile Details Updated.");
    }
    public function getratings(){
        return view('admin.ratings');
    }
    public function postAllRatings(){
        $ratings = Ratings::leftJoin('orders as o', 'o.id', '=', 'ratings.order_id')
                   ->leftJoin('users as u', 'u.id', '=', 'o.user_id')
                   ->leftJoin('store_details as s', 's.id', '=', 'o.store_id')

                   ->leftJoin('_list as store_city', 'store_city.id', '=', 's.city')
                   ->leftJoin('_list as store_state', 'store_state.id', '=', 's.state')

                   ->select([
                        'o.order_number',
                        's.storename',
                        's.add1 as store_add1',
                        's.add2 as store_add2',
                        's.zip as store_zip',
                        'store_city.item_name as store_city',
                        'store_state.item_name as store_state',
                        'u.firstname',
                        'ratings.rating',
                        'ratings.comments',
                        'ratings.created_at',
                   ]);
        return Datatables::of($ratings)
            ->editColumn('storename', function ($data) {
                $add = [$data->store_add1,$data->store_add2,$data->store_city.' '.$data->store_state,$data->store_zip];
                $address = Html::link("#",$data->storename,['title'=>implode(', ',array_filter($add)),'class'=>'show-tooltip','data-placement'=>'top']);
                return $address;
            })
            ->editColumn('rating', function ($data) {
                $html = '<div class="col-md-5 no-padding">';
                $html .= Form::number('rating',$data->rating,['class'=>'rating-input']);
                $html .= '</div>';
                $html .= '<div class="col-md-6">';
                $html .= "<b>[".$data->rating."]</b>";
                $html .= "</div>";
                return $html;
            })
            ->editColumn('created_at', function ($data) {
                $date = \Carbon::parse($data->created_at)->format('d M Y');
                return $date;
            })
            ->removeColumn('store_add1')
            ->removeColumn('store_add2')
            ->removeColumn('store_city')
            ->removeColumn('store_state')
            ->removeColumn('store_zip')
            ->make();
    }
    public function getNewsletter(){
        $csses = [
            config('theme.ASSETS').config('theme.ADMIN_PATH').'plugins/summernote/summernote.css',
        ];
        $jsses = [
            config('theme.ASSETS').config('theme.ADMIN_PATH').'plugins/summernote/summernote.js',
        ];
        $customers = User::Rolebased('2')->lists('username','id')->all();
        $stores = StoreDetails::where(['is_virtual'=>0])->lists('storename','user_id')->all();
        $zips = _list::where(['list_name'=>'newsletter_zip'])->lists('item_name','friendly_name')->all();
        $data['customers'] = $customers;
        $data['stores'] = $stores;
        $data['zips'] = $zips;
        $data['csses'] = $csses;
        $data['jsses'] = $jsses;
        return view('admin.newsletter',$data);
    }
    public function postNewsletter(Request $request){
        //dd($request->all());
        $rules = [
            'to'=>'required',
            'subject'=>'required',
            'body'=>'required',
            'cust_type'=>'required_if:to,==,1',
            'to_stores'=>'required_if:to,==,2',
            'guest_zips'=>'required_if:to,==,3',
        ];
        if($request->to=='1' && $request->cust_type==1){
            $rules['zips'] = 'required';
        }
        if($request->to=='1' && $request->cust_type==2){
            $rules['to_customers'] = 'required';
        }
        $messages = [
            'cust_type.required_if'=>'The Customer Type field is required when type is customer.',
            'to_stores.required_if'=>'Individual stores field is required when type is store.',
            'guest_zips.required_if'=>'Guest zip field is required when type is guest.',

            'to_customers.required'=>'Individual customers field is required.',
            'zips.required'=>'Customer zip field is required.'
        ];
        $this->validate($request,$rules,$messages);
        $toType = $request->to;
        $toEmails = array();
        if($toType == '1'){
            // customer
            $custType = $request->cust_type;
            if($custType == '1'){
                // distance based
                $cust = CustomerDetails::where(['is_subscribed'=>1])->with('user')->whereIn(DB::raw('left(zip,3)'),$request->zips)->get();
                foreach($cust as $user){
                    $data = array(
                        'user'=>$user->user,
                        'subject'=>$request->subject,
                        'body'=>$request->body,
                    );
                    Emailer::SendEmail('admin.newsletter',$data);    
                }
            } else if($custType == '2'){
                // individual customers
                $toEmails = User::whereIn('id',$request->to_customers)->get();
                foreach($toEmails as $user){
                    $data = array(
                        'user'=>$user,
                        'subject'=>$request->subject,
                        'body'=>$request->body,
                    );
                    Emailer::SendEmail('admin.newsletter',$data);    
                }
            }
        } else if($toType == '2'){
            // store
            $toEmails = User::whereIn('id',$request->to_stores)->get();
            foreach($toEmails as $user){
                $data = array(
                    'user'=>$user,
                    'subject'=>$request->subject,
                    'body'=>$request->body,
                );
                Emailer::SendEmail('admin.newsletter',$data);    
            }
        } else if($toType == '3'){
            // guest
            $guest = NewsletterSubscription::where(['type'=>2])->whereIn(DB::raw('left(zip,3)'),$request->guest_zips)->get();
            foreach($guest as $user){
                $data = array(
                    'user'=>$user,
                    'subject'=>$request->subject,
                    'body'=>$request->body,
                );
                Emailer::SendEmail('admin.newsletter',$data);    
            }
        }
        //$file = new Filesystem;
        //$file->cleanDirectory(Functions::UploadsPath(config('theme.NEWSLETTER_UPLOAD')));
        return redirect()->back()->with('newsletter_success',"Newsletter sent successfully");
    }
    public function postNewsletterImage(Request $request){
        $file = $request->file('file');
        $image_data = Functions::UploadPic($file,config('theme.NEWSLETTER_UPLOAD'));
        $image = $image_data['encrypted_name'];
        $fileURL = ($image!='' ? url(Functions::UploadsPath(config('theme.NEWSLETTER_UPLOAD')).$image) : "");
        return $fileURL;
    }
    public function getNewsletterSubscriptions(){
        return view('admin.newsletter-subscription');
    }
    public function postSubscriptions(Request $request){
        $userType = $request->type;
        if($userType==0){
            $guest = DB::table('newsletter_subscriptions')->select(['email','zip','type']);

            $union = DB::table('customer_details')
                    ->leftJoin('users', 'users.id', '=', 'customer_details.user_id')
                    ->select(['users.email', 'zip', 'is_subscribed as type'])
                    ->union($guest);

            $query = DB::table(DB::raw("({$union->toSql()}) as x"))
                    ->select(['email', 'zip', 'type']);

                    return Datatables::of($query)
                           ->editColumn('type', function ($data) {
                                return ($data->type == 1 ? "Customer" : "Guest");
                           })
                           ->make();
            /*$users = DB::select("select 
                                    `newsletter_subscriptions`.`email`, 
                                    `newsletter_subscriptions`.`zip`, 
                                    `newsletter_subscriptions`.`type` as `type`
                                from 
                                    `newsletter_subscriptions` 
                                where (`type` = '2')
                                UNION
                                select 
                                    users.email as email,
                                    customer_details.zip,
                                    customer_details.is_subscribed as type
                                from 
                                    customer_details
                                left join users ON
                                    users.id = customer_details.user_id
                                where 
                                    customer_details.is_subscribed = 1"
                            );
            return Datatables::of($users)
                   ->editColumn('type', function ($data) {
                    return ($data->type == 1 ? "Customer" : "Guest");
                   })
                   ->removeColumn('user_id')
                   ->make();*/
        }
        elseif($userType==1){
            $users = CustomerDetails::leftJoin('users', 'users.id', '=', 'customer_details.user_id')
                    ->where(['is_subscribed'=>1])
                    ->select(['customer_details.user_id','users.email as email','customer_details.zip as zip','is_subscribed as type']);
            return Datatables::of($users)
                   ->editColumn('type', function ($data) {
                    return "Customer";
                   })
                   ->removeColumn('user_id')
                   ->make();
        } elseif($userType==2){
            $users = NewsletterSubscription::where(['type'=>2])
                    ->select(['newsletter_subscriptions.email','newsletter_subscriptions.zip','newsletter_subscriptions.type as type']);
            return Datatables::of($users)
                   ->editColumn('type', function ($data) {
                    return "Guest";
                   })
                   ->make();
        }
        //$guest = NewsletterSubscription::where(['type'=>2])->get();

    }
}
