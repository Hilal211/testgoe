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
    Crypt,
    Html,
    App\_list,
    App\_category,
    App\StoreBankDetails,
    Functions,
    Emailer,
    App\roles;

class StoreController extends Controller
{
    //
    public function getStore(){
    	$cats = [''=>''] + _category::where(['parent_id'=>'0'])->lists('category_name','id')->all();
        $data = [
            "cats"=>$cats,    		
    	];
    	return view('admin.store',$data);
    }

    public function postStoreList(){
        $roles = new roles();
                    
        $users = DB::table('store_details')
            ->leftJoin('users', 'store_details.user_id', '=', 'users.id')
            ->leftJoin('_list as type','store_details.storetype','=','type.id')
            ->leftJoin('store_bank_details as bank','bank.store_id','=','store_details.id')
            ->orderBy('user_id','desc')
            ->where(['is_virtual'=>'0'])
            ->select(
                'store_details.storename',
                'store_details.id',
                'users.id as user_id',
                'users.username',
                'users.email',
                'store_details.contactnumber',
                'store_details.homedelievery',
                'store_details.commision',
                'users.status',
                'bank.status as bank_status',
                'type.item_name'
            );
        return Datatables::of($users)
        		->editColumn('item_name', function ($model) {
                    $name = $model->item_name;
                    $id = $model->user_id;
                    $name .="<div class='action-controls'>";
                    $name .="   <a href='".route("admin.store.details",\Crypt::encrypt($id))."'><i class='fa fa-pencil'></i></a>"; 
                    $name .="   <a href='javascript:;' onclick='GetDelete(this)' data-id='".$id."'><i class='fa fa-remove'></i></a>";   
                    if($model->status=='0'){
                        $name .="   <a href='javascript:;' data-id='".$id."' onclick='ApproveStore(this)' class='show-tooltip' title='Approve'><i class='fa fa-check'></i></a>";
                    }
                    $name .="</div>";   
                    return $name;
                })
                ->editColumn('commision', function ($model) {
                    return $model->commision.' %';
                })
                ->editColumn('homedelievery', function ($model) {
                    return ($model->homedelievery=='1' ? "Yes" : "No");
                })
                ->editColumn('status', function ($model) {
                    if($model->status=='0'){
                        return '<span class="text-danger">Pending Approval</span>';
                    }if($model->status=='2'){
                        return '<span class="text-warning">Pending Verification</span>';
                    }if($model->status=='1'){
                        return '<span class="text-info">Verified</span>';
                    }
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
    
    public function GetStoreProfile($id){
        $user = User::find(Crypt::decrypt($id));
        $store_details = StoreDetails::where(['user_id'=>$user->id])->first();
        $states = _list::where(['list_name'=>'states'])->lists('item_name','id')->all();
        $cities = _list::where(['list_name'=>'cities'])->lists('item_name','id')->all();;
        $types = [""=>""] + _list::where(['list_name'=>'storetypes'])->lists('item_name','id')->all();
        $bank_details = StoreBankDetails::where(['store_id'=>$store_details->id])->first();
        list($lat,$long) = explode(',',$store_details->lat_long);

        $image = ($store_details->image!='' ? url(Functions::UploadsPath(config('theme.STORE_UPLOAD')).$store_details->image) : "");
        $store_details->image = $image;

        if($bank_details){
            $image = ($bank_details->document!='' ? url(Functions::UploadsPath(config('theme.DOCUMENT_UPLOAD')).$bank_details->document) : "");
            $bank_details->document = $image;
        }

        $commsion_type = [""=>"",'1'=>"Sub Total",'2'=>"Sub Total + Shipping Charges"];
        $data = [
            "states"=>$states,
            'cities'=>$cities,
            'types'=>$types,
            'user'=>$user,
            'store'=>$store_details,
            'bank_details'=>$bank_details,
            'lat'=>$lat,
            'long'=>$long,
            'com_type'=>$commsion_type,
        ];
        return view('admin.store_profile',$data);
    }
    public function deleteSingleStore($id){
        $user = User::find($id);
        $store_details = StoreDetails::where(['user_id'=>$user->id])->first();
        $store_bank_details = StoreBankDetails::where(['store_id'=>$store_details->id])->first();
        
        \Stripe\Stripe::setApiKey(config('theme.STRIPE_API_KEY'));
        \Stripe\Stripe::setApiVersion(config('theme.STRIPE_API_VERSION'));

        if($store_bank_details){
            if($store_bank_details->stripe_account_id!=''){
                $account = \Stripe\Account::retrieve($store_bank_details->stripe_account_id);
                $account->delete();
            }
            $store_bank_details->delete();
        }
        
        $user->delete();
        $store_details->delete();
        
        return response()->json(array(
            "status" => "success",
        ));
    }

    public function ApproveStore($id){
        $store = StoreDetails::where(['user_id'=>$id])->with('User')->first();
        $user = User::where(['id'=>$id])->first();
        $user->update(['status'=>'2']);
        $data = array(
            'user'=>$user
        );
        Emailer::SendEmail('verify.store',$data);
        return response()->json(array(
            "status" => "success",
        ));
    }
    public function getBank($id){
        $inputs = StoreBankDetails::where(['store_id'=>$id])->first();
        $filteredArr = [
            'id'=>["type"=>"text",'value'=>$inputs->store_id],
            'account_holder_name'=>["type"=>"text",'value'=>$inputs->account_holder_name],
            'bank_name'=>["type"=>"text",'value'=>$inputs->bank_name],
            'account_number'=>["type"=>"text",'value'=>$inputs->account_number],
            'routing_number'=>["type"=>"text",'value'=>$inputs->routing_number],
        ];
        return response()->json(array(
            "status" => "success",
            "inputs"=>$filteredArr,
        ));
    }
    public function ApproveBank(Request $request){
        $id = $request->input('id');
        $bank = StoreBankDetails::where(['store_id'=>$id])->first();
        $store = StoreDetails::where(['id'=>$id])->with('User')->first();

        //Functions::UploadsPath(config('theme.DOCUMENT_UPLOAD')).$bank->document
        
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
                "email" => $store->User->email,
                "legal_entity"=>[
                    "type" => "individual",
                    "first_name" => $store->User->firstname,
                    "last_name" => $store->User->lastname,
                    "verification"=>[
                        "document" => @$DocumentFile->id
                    ],
                    "dob" =>[
                        'day'=>$bank->dob->format('d'),
                        'month'=>$bank->dob->format('m'),
                        'year'=>$bank->dob->format('Y'),
                    ],
                    "address" => [
                        'line1'=>$store->add1.' '.$store->add2,
                        'city'=>_list::getName($store->city),
                        'postal_code'=>$store->zip,
                        'state'=>_list::getExtraValue($request->state)
                    ],
                ],
                "tos_acceptance"=>[
                    'date'=>$bank->tos_acceptance_date,
                    'ip'=>$bank->tos_acceptance_ip
                ],
              "business_name" => $store->storename,
              "external_account" => $bankobj,
            ));

            $data = array(
                'store'=>$store,
            );
            Emailer::SendEmail('store.stripe',$data);
        } catch (\Stripe\Error\InvalidRequest $e) {
            $body = $e->getJsonBody();
            $error = $body['error'];
            return response()->json(['error' => $error['message']],403);
        }
        
        $bank->update(['stripe_account_id'=>$account->id,'status'=>'1']);
        return response()->json(array(
            "status" => "success"
        ));
    }

}
