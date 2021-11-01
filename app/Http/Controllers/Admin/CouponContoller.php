<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\_list;
use App\StoreDetails;
use App\Coupons;
use Carbon\Carbon;
use Functions;
use Validator;
use Datatables;
use DB;

class CouponContoller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $zips = _list::where(['list_name'=>'newsletter_zip'])->lists('item_name','friendly_name')->all();
        $stores = StoreDetails::lists('storename','id')->all();
        $data['zips'] = $zips;
        $data['stores'] = $stores;
        return view('admin.coupons', $data);
    }

    public function getCoupons(Request $request){
        $couponObj = new Coupons();
        $coupons = Coupons::leftJoin('orders', 'orders.coupon_code', '=', 'coupons.code')->select([
            'coupons.id',
            'type',
            'code',
            'start_date',
            'end_date',
            DB::raw('count(orders.id) as used_count'),
            'value_type',
            'value',
        ])->groupBy('coupons.code')->orderBy('id','desc');
        return Datatables::of($coupons)
               ->editColumn('type', function ($data) use($couponObj){
                    $type = $data->type;
                    return $couponObj->types[$type];
               })
               ->editColumn('start_date', function ($data) {
                    $date = \Carbon::parse($data->start_date)->format('d M Y');
                    return $date;
               })
               ->editColumn('end_date', function ($data) {
                    $date = \Carbon::parse($data->end_date)->format('d M Y');
                    return $date;
               })
               ->editColumn('value', function ($data) {
                    $name = ($data->type == '1' ? $data->value.' $' : $data->value.' %');
                    $id = $data->id;
                    
                    $name .="<div class='action-controls'>";
                    $name .="   <a href='javascript:;' onclick='showEdit(this)' data-id='".$id."'><i class='fa fa-pencil'></i></a>"; 
                    //$name .="   <a href='javascript:;' onclick='GetDelete(this)' data-id='".$id."'><i class='fa fa-remove'></i></a>";
                    
                    $name .="</div>";   
                    return $name;
                })
               ->removeColumn('id')
               ->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'type'=>'required',
            'value_type'=>'required',
            'value'=>'required|numeric',
            'min_order_amount'=>'required|numeric',
            'coupon_limit'=>'required|numeric',
            'zips'=>'required_if:type,==,1',
            'stores'=>'required_if:type,==,2',
        ];
        if($request->id == 0){
            $rules['code'] = 'required|min:4|max:20'; 
            $rules['start_date'] = 'required'; 
            $rules['end_date'] = 'required'; 
        }
        if($request->hasFile('image')){
            $rules['image'] = 'image'; 
        }
        $messages = [
            'zips.required_if'=>'The Postal / Zip Code field is required, when type is Postal / Zip Code.',
            'stores.required_if'=>'The Stores field is required, when type is Store.',
        ];        
        //$this->validate($request,$rules,$messages);
        $id = $request->input('id');
        $DBCoupon = Coupons::where(['id'=>$id])->first();

        if(!$DBCoupon){
            $oldCoupon = Coupons::where(DB::raw('lower(code)'),'=',strtolower($request->code))->count();
        }else{
            $oldCoupon = Coupons::where(DB::raw('lower(code)'),'=',strtolower($request->code))->where('id','!=',$id)->count();            
        }
        
        $valid = Validator::make($request->all(),$rules);
        $messages = $valid->errors();
        if($oldCoupon > 0){
            $messages->add('code','The Coupon code is already taken.');
        }
        if(($request->min_order_amount!='' && $request->value!='') && ($request->min_order_amount <= $request->value)){
            $messages->add('min_order_amount','The minimum order amount should not be less/equal to coupon value.');
        }
        if($valid->fails()){
            return response()->json($messages, 422);
        }
        if($oldCoupon > 0){
            return response()->json($messages, 422);            
        }
        if($request->min_order_amount <= $request->value){
            return response()->json($messages, 422);
        }

        if(!$DBCoupon){
            $inputs = $request->all();
            $file = $request->file('image');
            if($request->hasFile('image')){
                if($file->isValid()){
                    $image_data = Functions::UploadPic($file,config('theme.COUPON_UPLOAD'));
                    $inputs['image']=$image_data['encrypted_name'];
                }
            }
            if($request->type==1){
                $inputs['zip_codes'] = implode(',',$request->zips);
            }elseif($request->type==2){
                $inputs['store_codes'] = implode(',',$request->stores);
            }
            $startDate = Carbon::parse($inputs['start_date']);
            $inputs['start_date'] = $startDate->format('Y-m-d');
            $endDate = Carbon::parse($inputs['end_date']);
            $inputs['end_date'] = $endDate->format('Y-m-d');
            $inputs['status'] = 1;
            $coupon = Coupons::create($inputs);

            $Image = Functions::UploadsPath(config('theme.COUPON_UPLOAD')).$coupon->image;
            $Image = public_path().'/'.$Image;
            $Image = str_replace('public/public','public',$Image);
            chmod($Image,0777);
        }else{
            $inputs = $request->except('_token','image');
            if($request->type==1){
                $inputs['zip_codes'] = implode(',',$request->zips);
            }elseif($request->type==2){
                $inputs['store_codes'] = implode(',',$request->stores);
            }else{
                $inputs['store_codes'] = '';
                $inputs['zip_codes'] = '';
            }
            if ($request->has('is_inactive')) {
                $inputs['status'] = 0;
            }
            /*$startDate = Carbon::parse($inputs['start_date']);
            $inputs['start_date'] = $startDate->format('Y-m-d');
            $endDate = Carbon::parse($inputs['end_date']);
            $inputs['end_date'] = $endDate->format('Y-m-d');*/
            $DBCoupon->update($inputs);

            $file = $request->file('image');
            if($file!=null){
                if($DBCoupon->image!=''){
                    $Image = Functions::UploadsPath(config('theme.COUPON_UPLOAD')).$DBCoupon->image;
                    if(\File::exists($Image)){
                        \File::delete($Image);
                    }
                }
                $image_data = Functions::UploadPic($file,config('theme.COUPON_UPLOAD'));
                $DBCoupon->update(['image'=>$image_data['encrypted_name']]);

                $Image = Functions::UploadsPath(config('theme.COUPON_UPLOAD')).$DBCoupon->image;
                $Image = public_path().'/'.$Image;
                $Image = str_replace('public/public','public',$Image);
                chmod($Image,0777);
            }
        }
        return response()->json(array(
            "status" => "success",
            "message"=>'Data Saved',
        ));
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
        $inputs = Coupons::find($id);
        $image = ($inputs->image!='' ? url(Functions::UploadsPath(config('theme.COUPON_UPLOAD')).$inputs->image) : "");
        $startDate = \Carbon::parse($inputs->start_date)->format('d M Y');
        $endDate = \Carbon::parse($inputs->end_date)->format('d M Y');
        $filteredArr = [
            'id'=>["type"=>"text",'value'=>$inputs->id],
            
            'code'=>["type"=>"text",'value'=>$inputs->code],
            'value'=>["type"=>"text",'value'=>$inputs->value],
            'min_order_amount'=>["type"=>"text",'value'=>$inputs->min_order_amount],
            'coupon_limit'=>["type"=>"text",'value'=>$inputs->coupon_limit],
            'type'=>["type"=>"radio",'selectedValue'=>$inputs->type],
            'value_type'=>["type"=>"radio",'selectedValue'=>$inputs->value_type],
            'zip_select'=>["type"=>"multi-select",'selectedValue'=>explode(',',$inputs->zip_codes)],
            'store_select'=>["type"=>"multi-select",'selectedValue'=>explode(',',$inputs->store_codes)],
            'amount_condition'=>["type"=>"select",'value'=>$inputs->amount_condition],
            'amount'=>["type"=>"text",'value'=>$inputs->amount],
            'start_date'=>["type"=>"date",'value'=>$startDate],
            'end_date'=>["type"=>"date",'value'=>$endDate],
            'is_inactive'=>["type"=>"checkbox",'checkedValue'=>explode(',',$inputs->status)],

            'image'=>["type"=>"image",'file'=>$image],
        ];
        return response()->json(array(
            "status" => "success",
            "inputs"=>$filteredArr,
        ));
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
