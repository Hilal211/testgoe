<?php

namespace App\Http\Controllers\Store;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\_list,
App\User,
Functions,
Emailer,
Crypt,
App\_category,
App\Product,
App\roles,
App\StoreBankDetails,
App\StoreDetails;

class ProfileController extends Controller
{
    //
    public function getStoreDetails($id){
    	$user = User::find(Crypt::decrypt($id));
    	$store_details = StoreDetails::where(['user_id'=>$user->id])->first();
    	$states = [""=>""] + _list::where(['list_name'=>'states'])->lists('item_name','id')->all();
        $cities = [""=>""] + _list::where(['list_name'=>'cities'])->lists('item_name','id')->all();;
        $types = [""=>""] + _list::where(['list_name'=>'storetypes'])->lists('item_name','id')->all();;
        $bank_details = StoreBankDetails::where(['store_id'=>$store_details->id])->first();
        list($lat,$long) = explode(',',$store_details->lat_long);

        $image = ($store_details->image!='' ? url(Functions::UploadsPath(config('theme.STORE_UPLOAD')).$store_details->image) : "");
        $store_details->image = $image;

        if($bank_details){
            $image = ($bank_details->document!='' ? url(Functions::UploadsPath(config('theme.DOCUMENT_UPLOAD')).$bank_details->document) : "");
            $bank_details->document = $image;
        }
        
        $data = [
            "states"=>$states,
            'cities'=>$cities,
            'types'=>$types,
            'user'=>$user,
            'store'=>$store_details,
            'bank_details'=>$bank_details,
            'lat'=>$lat,
            'long'=>$long,
        ];
        return view('store.profile',$data);
    }
    public function PostStoreDetails(Request $request,$step,$id){
        \Stripe\Stripe::setApiKey(config('theme.STRIPE_API_KEY'));
        \Stripe\Stripe::setApiVersion(config('theme.STRIPE_API_VERSION'));

        $rules = array();
        $type=$request->input('type');
        $user = User::find(Crypt::decrypt($id));;
        $store = StoreDetails::where(['user_id'=>$user->id])->first();
        $bank = StoreBankDetails::where(['store_id'=>$store->id])->first();
        if($step=='step-1'){
            $rules = [
                "username"=>'required|unique:users,username,'.$user->id,
                "email"=>'required|email|unique:users,email,'.$user->id,
                "password"=>'confirmed|min:6|max:255',
                "contactnumber"=>'required'
            ];
            if($type=='admin'){
                $rules = array_add($rules,'commision','required');
                $rules = array_add($rules,'commision_on','required');
            }
            $messages = [
                'username.required'=>'The Store Contact Person Name field is required.'
            ];
            $this->validate($request,$rules,$messages);
            if(trim($request->input('password'))!=''){
                $inputs = $request->only(['username','password','email']);
                $inputs['password'] = bcrypt($inputs['password']);
            }else{
                $inputs = $request->except(['_token','password','password_confirmation']);
            }

            if($type=='store'){
                /*  log user data */
                $oldArr = [
                    'username'=>$user->username,
                    'email'=>$user->email,
                    'contactnumber'=>$store->contactnumber,
                ];
                $newArr = $request->only(['username','email','contactnumber']);
                $logText = Functions::getDifferenceLogText($newArr,$oldArr,$condition="true");

                if($logText!=''){
                    $data = [
                    'user'=>$user,
                    'logText'=>$logText,
                    ];
                    Emailer::SendEmail('profile.changed',$data);
                }
            }

            $user->update($inputs);
            if($type=='admin'){
                $store->update($request->only(['contactnumber','commision','commision_on']));
            }else{
                $store->update($request->only(['contactnumber']));
            }

            /* update stripe account */
            if($bank){
                if($bank->stripe_account_id!=''){
                    $account = \Stripe\Account::retrieve($bank->stripe_account_id);
                    $account->email = $request->input('email');
                    $account->save();
                }
            }
        }else if($step=='step-2'){
            $storeObj = new StoreDetails;
            $rules = [
                "storename"=>'required',
                "add1"=>'required',
                "state"=>'required',
                "city"=>'required',
                "zip"=>'required',
                "storetype"=>'required',
            ];
            $messages = array();
            if($request->input('homedelievery')=='0'){
                $rules["state"]='in:11';
                $messages = ['state.in' => 'Currently we are serving only Montreal area.'];

                if($request->input('state')=='11'){
                    $rules["city"]='in:727';
                    $messages = ['city.in'=>'Currently we are serving only Montreal area.'];
                }
            }
            $this->validate($request,$rules,$messages);
            $inputs = $request->except(['_token']);

            $output = Functions::GetLatLong($inputs['add1'],$inputs['add2'],_list::getName($inputs['city']),_list::getName($inputs['state']),$inputs['zip']);
            if("ZERO_RESULTS" == $output->status){
                return redirect()->back()->withErrors(array('address'=>"Invalid Address."))->withInput();
            }else if("OVER_QUERY_LIMIT" == $output->status){
                return redirect()->back()->withErrors(array('address'=>"Something went wrong, Please try again after some time."))->withInput();
            }else if("OVER_DAILY_LIMIT" == $output->status){
                return redirect()->back()->withErrors(array('address'=>"Something went wrong, Please try again after some time."))->withInput();
            }else if("INVALID_REQUEST" == $output->status){
                return redirect()->back()->withErrors(array('address'=>"Something went wrong, Please try again after some time."))->withInput();
            }else{
                $latitude = $output->results[0]->geometry->location->lat;
                $longitude = $output->results[0]->geometry->location->lng;
                $lat_long = $latitude.','.$longitude;
            }
            $inputs['lat_long'] = $lat_long;
            if(!$request->has('profile_image') && $request->hasFile('profile_image')){
                if($store->image!=''){
                    $Image = Functions::UploadsPath(config('theme.STORE_UPLOAD')).$store->image;
                    if(\File::exists($Image)){
                        \File::delete($Image);
                        foreach($storeObj->sizes as $size){
                            \File::delete(Functions::GetImageName($Image,'-'.$size['width'].'x'.$size['height']));
                        }
                    }
                }
                $file = $request->file('profile_image');
                $image_data = Functions::UploadPic($file,config('theme.STORE_UPLOAD'));
                $inputs['image'] = $image_data['encrypted_name'];

                $Image = Functions::UploadsPath(config('theme.STORE_UPLOAD')).$image_data['encrypted_name'];
                $Image = public_path().'/'.$Image;
                $Image = str_replace('public/public','public',$Image);
                chmod($Image,0777);

                foreach($storeObj->sizes as $size){
                    $ImagePath = $Image;
                    $newName = Functions::GetImageName($Image,'-'.$size['width'].'x'.$size['height']);
                    Functions::ResizeImage($ImagePath,$size['width'],$size['height'],$newName);
                    chmod(Functions::GetImageName($Image,'-'.$size['width'].'x'.$size['height']),0777);
                }
            }
            if($type=='store'){
                /*  log user data */
                $oldArr = [
                    'storename'=>$store->storename,
                    'add1'=>$store->add1,
                    'add2'=>$store->add2,
                    'state'=>$store->StateName,
                    'city'=>$store->CityName,
                    'zip'=>$store->zip,
                    'storetype'=>_list::getName($store->storetype),
                    'homedelievery'=>($store->homedelievery=='1' ? "Yes" : "No"),
                ];
                $newArr = $request->only(['storename','add1','add2','state','city','zip','storetype','homedelievery']);
                $newArr['city'] = _list::getName($newArr['city']);
                $newArr['state'] = _list::getName($newArr['state']);
                $newArr['storetype'] = _list::getName($newArr['storetype']);
                $newArr['homedelievery'] = ($newArr['homedelievery']=='1' ? "Yes" : "No");
                $logText = Functions::getDifferenceLogText($newArr,$oldArr,$condition="true");
                
                if($logText!=''){
                    $data = [
                        'user'=>$user,
                        'logText'=>$logText,
                    ];
                    Emailer::SendEmail('profile.changed',$data);
                }
            }
            $store->update($inputs);

            /* update stripe account */
            if($bank){
                if($bank->stripe_account_id!=''){
                    $account = \Stripe\Account::retrieve($bank->stripe_account_id);
                    $account->business_name = $store->storename;
                    $account->legal_entity->address->line1 = $store->add1;
                    $account->legal_entity->address->city = _list::getName($store->city);
                    $account->legal_entity->address->postal_code = $store->zip;
                    $account->legal_entity->address->state = 'NB';
                    $account->save();
                }
            }
        }else if($step=='step-3'){
            if($store->is_virtual == 0){
                $rules = [
                    "gstnumber"=>'required',
                    "hstnumber"=>'required',
                    "legalentityname"=>'required',
                    "yearestablished"=>'required|date_format:Y'
                ];
                $this->validate($request,$rules);

                if($type=='store'){
                    /*  log user data */
                    $oldArr = [
                        'gstnumber'=>$store->storename,
                        'hstnumber'=>$store->add1,
                        'legalentityname'=>$store->add2,
                        'yearestablished'=>$store->StateName,
                    ];
                    $newArr = $request->only(['gstnumber','hstnumber','legalentityname','yearestablished']);
                    $logText = Functions::getDifferenceLogText($newArr,$oldArr,$condition="true");
                    
                    if($logText!=''){
                        $data = [
                            'user'=>$user,
                            'logText'=>$logText,
                        ];
                        Emailer::SendEmail('profile.changed',$data);
                    }
                }
                $store->update($request->except(['_token']));
            }elseif($store->is_virtual == 1){
                $rules = [
                    "gstnumber"=>'required',
                    "hstnumber"=>'required',
                    "legalentityname"=>'required',
                    "yearestablished"=>'required|date_format:Y',
                    "legal_add1"=>'required',
                    "legal_state"=>'required',
                    "legal_city"=>'required',
                    "legal_zip"=>'required',
                ];
                $this->validate($request,$rules);
                if($type=='store'){
                    /*  log user data */
                    $oldArr = [
                        'gstnumber'=>$store->gstnumber,
                        'hstnumber'=>$store->hstnumber,
                        'legalentityname'=>$store->legalentityname,
                        'yearestablished'=>$store->year,
                        'legal_add1'=>$store->legal_add1,
                        'legal_add2'=>$store->legal_add2,
                        'legal_state'=>$store->LegelStateName,
                        'legal_city'=>$store->LegelCityName,
                        'legal_zip'=>$store->legal_zip,
                    ];
                    $newArr = $request->only(['gstnumber','hstnumber','legalentityname','yearestablished','legal_add1','legal_add2','legal_state','legal_city','legal_zip']);
                    
                    $newArr['legal_state'] = _list::getName($newArr['legal_state']);
                    $newArr['legal_city'] = _list::getName($newArr['legal_city']);
                    
                    $logText = Functions::getDifferenceLogText($newArr,$oldArr,$condition="true");
                    if($logText!=''){
                        $data = [
                            'user'=>$user,
                            'logText'=>$logText,
                        ];
                        Emailer::SendEmail('profile.changed',$data);
                    }
                }
                $store->update($request->except(['_token']));
            }
        }else if($step=='account'){
            
            $rules = [
                "bank_name"=>'required',
                "account_holder_name"=>'required',
                "routing_number"=>'required',
                "account_number"=>'required',
                "dob"=>'required',
                "firstname"=>'required',
                "lastname"=>'required',
                "agreement"=>'required',
            ];
            if($request->input('id')=='' && $request->input('stripe_account_id')==''){
                $rules['document'] = 'required|image';
            }else{
                $rules['document'] = 'image';
            }
            $this->validate($request,$rules);
            if($request->input('id')=='' && $request->input('stripe_account_id')==''){
                $inputs = $request->except('stripe_account_id','firstname','lastname');
                $inputs['store_id'] = $store->id;

                $datetime = new \Carbon($request->input('dob'));
                $inputs['dob'] = $datetime->format('Y-m-d');

                $file = $request->file('document');
                if($file!=null){
                    $image_data = Functions::UploadPic($file,config('theme.DOCUMENT_UPLOAD'));
                    $inputs['document'] = $image_data['encrypted_name'];
                }
                $inputs['tos_acceptance_date'] = time();
                $inputs['tos_acceptance_ip'] = $_SERVER["REMOTE_ADDR"];
                StoreBankDetails::create($inputs);

                $user->update($request->only(['firstname','lastname']));
            }else{
                if($bank->document!=''){
                    $File = Functions::UploadsPath(config('theme.DOCUMENT_UPLOAD')).$bank->document;
                    if(\File::exists($File)){
                        \File::delete($File);
                    }
                }
                $inputs = $request->except('stripe_account_id','_token','firstname','lastname');
                $datetime = new \Carbon($request->input('dob'));
                $inputs['dob'] = $datetime->format('Y-m-d');

                $file = $request->file('document');
                if($file!=null){
                    $image_data = Functions::UploadPic($file,config('theme.DOCUMENT_UPLOAD'));
                    $inputs['document'] = $image_data['encrypted_name'];
                }

                $inputs['tos_acceptance_date'] = time();
                $inputs['tos_acceptance_ip'] = $_SERVER["REMOTE_ADDR"];
                $bank->update($inputs);

                $user->update($request->only(['firstname','lastname']));
            }
        }
        
        if($type=='store'){
            return redirect()->back()->with('success',"Store Details Updated.");
        }else{
            return redirect()->back()->with('success',"Store Details Updated.");
            //return redirect()->route('admin.store.list')->with('success',"Store Details Updated.");
        }
    }
}
