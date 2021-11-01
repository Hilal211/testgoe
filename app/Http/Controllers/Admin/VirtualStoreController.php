<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\_list;
use App\StoreDetails;
use App\User;
use App\Settings;
use App\StoreBankDetails;
use App\VirtualStoreBankInfo;
use Functions;
use Datatables;
use DB;
use Auth;
use Html;

class VirtualStoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $states = [""=>""] + _list::where(['list_name'=>'states'])->lists('item_name','id')->all();
        $cities = [""=>""] + _list::where(['list_name'=>'cities'])->lists('item_name','id')->all();
        $data = [
            "states"=>$states,
            'cities'=>$cities,
        ];
        return view('admin.virtual_store', $data);
    }

    public function getVirtualStores(Request $request){
        $users = DB::table('store_details')
                ->leftJoin('_list as type','store_details.storetype','=','type.id')
                ->leftJoin('store_bank_details as bank','bank.store_id','=','store_details.id')
                ->where(['is_virtual'=>1])
                ->select(
                    'store_details.storename',
                    'store_details.id',
                    'store_details.user_id',
                    'store_details.homedelievery',
                    'bank.status as bank_status',
                    'type.item_name'
                );
        return Datatables::of($users)
                ->editColumn('storename', function ($model) {
                    return Html::link(route('admin.virtual.store.login',['id'=>$model->user_id]),$model->storename,[]);
                })
                ->editColumn('homedelievery', function ($model) {
                    return ($model->homedelievery=='1' ? "Yes" : "No");
                })
                ->editColumn('bank_status', function ($model) {
                    if($model->bank_status=='0'){
                        $id = $model->id;
                        return Html::link("#",'Pending Approval',['onclick'=>'showBank(this)','data-id'=>$id]);
                    }elseif($model->bank_status=='1'){
                        return '<span class="text-success">Ready</span>';
                    }else{
                        return '<span class="text-danger">Pending Account Info</span>';
                    }
                })
                ->removeColumn('id')
                ->removeColumn('user_id')
                ->make();
    }

    public function loginAsVirtualStore($id){
        $user = User::find($id);
        if($user){
            $loginUser = [
                'email' => $user->email,
                'password' => $user->username,
                'status' => '1',
            ];
            Auth::logout();
            if (Auth::attempt($loginUser)) {
                return redirect(route('user.dashboard',\App::getLocale()));
            }
        }else{
            return redirect()->back()->withErrors('No store found')->withInput();  
        }
        dd($id);
    }

    public function getBankDetails(Request $request){
        $bank = StoreBankDetails::where(['store_id'=>0])->first();
        $info = VirtualStoreBankInfo::first();
        if($bank){
            $doc = ($bank->document!='' ? url(Functions::UploadsPath(config('theme.DOCUMENT_UPLOAD')).$bank->document) : "");
            $filteredArr = [
                'email'=>["type"=>"text",'value'=>$info->email],
                'firstname'=>["type"=>"text",'value'=>$info->firstname],
                'lastname'=>["type"=>"text",'value'=>$info->lastname],
                'add1'=>["type"=>"text",'value'=>$info->add1],
                'add2'=>["type"=>"text",'value'=>$info->add2],
                'zip'=>["type"=>"text",'value'=>$info->zip],
                'business_name'=>["type"=>"text",'value'=>$info->business_name],

                'state'=>["type"=>"select",'value'=>$info->state],
                'city'=>["type"=>"select",'value'=>$info->city,'wait'=>'1'],
                'agreement'=>["type"=>"checkbox",'checkedValue'=>explode(',','1')],
                
                'dob'=>["type"=>"text",'value'=>$bank->dob->format('d M Y')],
                'account_holder_name'=>["type"=>"text",'value'=>$bank->account_holder_name],
                'bank_name'=>["type"=>"text",'value'=>$bank->bank_name],
                'account_number'=>["type"=>"text",'value'=>$bank->account_number],
                'routing_number'=>["type"=>"text",'value'=>$bank->routing_number],
                'document'=>["type"=>"file",'value'=>$doc],

            ];
        }else{
            $filteredArr = array();
        }
        return response()->json(array(
            "status" => "success",
            "inputs"=>$filteredArr,
        ));
    }

    public function postBankDetails(Request $request){
        $rules = [
            "bank_name"=>'required',
            "account_holder_name"=>'required',
            "routing_number"=>'required',
            "account_number"=>'required',
            "dob"=>'required',
            "agreement"=>'required',
            "firstname"=>'required',
            "lastname"=>'required',
            "email"=>'required|email',
            "state"=>'required',
            "city"=>'required',
            "zip"=>'required',
            "business_name"=>'required',
            "add1"=>'required',
            "document"=>'required|image',
        ];
        $this->validate($request,$rules);
        $inputs = $request->all();
        $inputs['store_id'] = '0';
        $datetime = new \Carbon($request->input('dob'));
        $inputs['dob'] = $datetime->format('Y-m-d');

        $file = $request->file('document');
        if($file!=null){
            $image_data = Functions::UploadPic($file,config('theme.DOCUMENT_UPLOAD'));
            $inputs['document'] = $image_data['encrypted_name'];
        }
        $inputs['tos_acceptance_date'] = time();
        $inputs['tos_acceptance_ip'] = $_SERVER["REMOTE_ADDR"];
        DB::beginTransaction();
        $bank = StoreBankDetails::create($inputs);

        // virtual store info
        $bankInfo = [
            'email'=>$request->email,
            'business_name'=>$request->business_name,
            'firstname'=>$request->firstname,
            'lastname'=>$request->lastname,
            'add1'=>$request->add1,
            'add2'=>$request->add2,
            'city'=>$request->city,
            'state'=>$request->state,
            'zip'=>$request->zip,
        ];
        VirtualStoreBankInfo::create($bankInfo);

        $id = 0;
        $bank = StoreBankDetails::where(['store_id'=>$id])->first();
        
        \Stripe\Stripe::setApiKey(config('theme.STRIPE_API_KEY'));
        \Stripe\Stripe::setApiVersion(config('theme.STRIPE_API_VERSION'));

        try {
                $bankobj = \Stripe\Token::create(array(
                  "bank_account" => array(
                    "account_number" => $bank->account_number,
                    "country" => 'ca',
                    "currency" => 'cad',
                    "routing_number" => $bank->routing_number,
                    "metadata" => ['bank_name'=>$bank->bank_name],
                  )
                ));
                $file = public_path('assets/uploads/'.config('theme.DOCUMENT_UPLOAD').'/'.$bank->document);
                if(file_exists($file)){
                $fp = fopen($file, 'r');
                    $DocumentFile = \Stripe\FileUpload::create(array(
                      'purpose' => 'identity_document',
                      'file' => $fp
                    ));
                }else{
                    $DocumentFile = "";
                }

            $account = \Stripe\Account::create(array(
                "managed" => true,
                "country" => "CA",
                "email" => $request->email,
                "legal_entity"=>[
                    "type" => "individual",
                    "first_name" => $request->firstname,
                    "last_name" => $request->lastname,
                    "verification"=>[
                        "document" => @$DocumentFile->id
                    ],
                    "dob" =>[
                        'day'=>$bank->dob->format('d'),
                        'month'=>$bank->dob->format('m'),
                        'year'=>$bank->dob->format('Y'),
                    ],
                    "address" => [
                        'line1'=>$request->add1.' '.$request->add2,
                        'city'=>_list::getName($request->city),
                        'postal_code'=>$request->zip,
                        'state'=>_list::getExtraValue($request->state)
                    ],
                ],
                "tos_acceptance"=>[
                    'date'=>$bank->tos_acceptance_date,
                    'ip'=>$bank->tos_acceptance_ip
                ],
              "business_name" => $request->business_name,
              "external_account" => $bankobj,
            ));

        } catch (\Stripe\Error\InvalidRequest $e) {
            $Image = Functions::UploadsPath(config('theme.DOCUMENT_UPLOAD')).$image_data['encrypted_name'];
            if(\File::exists($Image)){
                \File::delete($Image);
            }
            DB::rollBack();
            $body = $e->getJsonBody();
            $error = $body['error'];
            return response()->json(['error' => $error['message']],403);
        }
        $bank->update(['stripe_account_id'=>$account->id,'status'=>'1']);
        DB::commit();
        return response()->json(array(
            "status" => "success"
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        return view('admin.create_virtual_store',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $rules = [
            "virtualname"=>'required',
            "contactnumber"=>'required',
            "address_1"=>'required',
            "state"=>'required',
            "city"=>'required',
            "zip"=>'required',
            "home_delivery"=>'required',
            "storetype"=>'required',
            "legal_entity_name"=>'required',
            "year"=>'required|date_format:Y',
            //"legal_address_1"=>'required',
            "legal_state"=>'required',
            "legal_city"=>'required',
            "legal_zip"=>'required',
            "lat_long"=>'required',
        ];
        
        $storeBankDetails = StoreBankDetails::where(['store_id'=>0])->first();
        if(!$storeBankDetails){
            return redirect()->back()->withErrors('Create virtual store bank account first')->withInput();
        }

        $add1 = $request->input('address_1');
        $add2 = $request->input('address_2');
        $state = $request->input('state');
        $city = $request->input('city');
        $zip = $request->input('zip');

        $output = Functions::GetLatLong($add1,$add2,_list::getName($city),_list::getName($state),$zip);
        if("ZERO_RESULTS" == $output->status){
            $request->merge(['lat_long' => ""]);
        }else if("OVER_QUERY_LIMIT" == $output->status){
            $request->merge(['lat_long' => ""]);
        }
        else{
            $latitude = $output->results[0]->geometry->location->lat;
            $longitude = $output->results[0]->geometry->location->lng;
            $lat_long = $latitude.','.$longitude;
            $request->merge(['lat_long' => $lat_long]);
        }

        $this->validate($request,$rules);
        $store = new StoreDetails;
        $user = new User;
        $settings = Settings::where(['slug'=>'store_default_commision'])->first();
        $store->contactnumber = $request->contactnumber;
        $store->storename = $request->virtualname;
        $store->add1 = $request->address_1;
        $store->add2 = $request->address_2;
        $store->city = $request->city;
        $store->state = $request->state;
        $store->lat_long = $request->lat_long;
        $store->zip = $request->zip;
        $store->storetype = $request->storetype;
        $store->homedelievery = $request->home_delivery;
        $store->legalentityname = $request->legal_entity_name;
        $store->yearestablished = $request->year;
        
        $store->legal_add1 = $request->legal_address_1;
        $store->legal_add2 = $request->legal_address_2;
        $store->legal_state = $request->legal_state;
        $store->legal_city = $request->legal_city;
        $store->legal_zip = $request->legal_zip;
        $store->is_virtual = 1;
        $store->gstnumber = $request->gst;
        $store->hstnumber = $request->hst;

        $store->commision = $settings->value;
        $store->commision_on = 1;

        $randomUsername = Functions::GetRandomUsername();
        $user->username = $randomUsername;
        $user->email = $randomUsername.'@gmail.com';
        $user->status = '1';
        $user->password = bcrypt($randomUsername);

        if(!$request->has('profile_image') && $request->hasFile('profile_image')){
            $file = $request->file('profile_image');
            $image_data = Functions::UploadPic($file,config('theme.STORE_UPLOAD'));
            $store->image = $image_data['encrypted_name'];
            $Image = Functions::UploadsPath(config('theme.STORE_UPLOAD')).$image_data['encrypted_name'];
            $Image = public_path().'/'.$Image;
            $Image = str_replace('public/public','public',$Image);
            chmod($Image,0777);
            $storeObj = new StoreDetails;
            foreach($storeObj->sizes as $size){
                $ImagePath = $Image;
                $newName = Functions::GetImageName($Image,'-'.$size['width'].'x'.$size['height']);
                Functions::ResizeImage($ImagePath,$size['width'],$size['height'],$newName);
                chmod(Functions::GetImageName($Image,'-'.$size['width'].'x'.$size['height']),0777);
            }
        }

        $user->role="3";
        $user->save();
        $store->user_id = $user->id;
        $store->save();

        $newBank = $storeBankDetails->replicate();
        $newBank->store_id = $store->id;
        $newBank->save();
        return redirect(route('admin.virtual-stores.index'))->with('success',"Virtual store created");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
