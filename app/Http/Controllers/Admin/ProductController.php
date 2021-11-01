<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\_category,
    App\Product,
	App\_list,
    App\ProductRequest,
    App\RelatedProducts,
    App\StoreProductPriceLogs,
	Datatables,
    Functions,
    Validator,
    Html,
	DB;

class ProductController extends Controller
{
    //
    public function postSave(Request $request){
        $projectObj = new Product;
        /*dd($request->file('image'));*/
    	$messsages = array(
            'product_name.required'=>'Product name is required',
    		'fr_product_name.required'=>'French Product name is required',
            'cat_id.required'=>'Category is required',
            'subcat_id.required'=>'Sub Category is required',
    		'recycle_fee.required'=>'Recycling Fee is required',
    		'unit'=>'Unit is required',
		);
        $id = $request->input('id');
        $rules = [
            //"product_name"=>'required|unique:products,product_name,'.$request->input('id'),
            //"fr_product_name"=>'required|unique:products,product_name,'.$request->input('id'),
            "product_name"=>'required',
            "fr_product_name"=>'required',
            "cat_id"=>'required',
            "subcat_id"=>'required',
            "recycle_fee"=>'required',
            "unit"=>'required',
        ];
        if($request->input('id')=='0'){
            $rules['image'] = 'required|image'; 
        }
        $DBProduct = Product::where(['id'=>$request->input('id')])->first();
        $oldProduct = $oldProduct1 = 0;
    	//$this->validate($request,$rules,$messsages);
        if(!$DBProduct){
            if($request->product_name!=''){
                $oldProduct = Product::where(DB::raw('lower(product_name)'),'=',strtolower($request->product_name))->count();
            }
            if($request->fr_product_name !=''){
                $oldProduct1 = Product::where(DB::raw('lower(fr_product_name)'),'=',strtolower($request->fr_product_name))->count();
            }
        }else{
            if($request->product_name!=''){
                $oldProduct = Product::where(DB::raw('lower(product_name)'),'=',strtolower($request->product_name))->where('id','!=',$id)->count();
            }
            if($request->fr_product_name !=''){
                $oldProduct1 = Product::where(DB::raw('lower(fr_product_name)'),'=',strtolower($request->fr_product_name))->where('id','!=',$id)->count();
            }
        }
        $valid = Validator::make($request->all(),$rules,$messsages);
        $messages = $valid->errors();
        //\Log::info(json_encode($messages));
        if($oldProduct > 0){
            $messages->add('product_name','The Product name already exists.');
        }
        if($oldProduct1 > 0){
            $messages->add('fr_product_name','The French Product name already exists.');
        }
        if($valid->fails()){
            return response()->json($messages, 422);
        }
        if($oldProduct > 0 || $oldProduct1 > 0){
            return response()->json($messages, 422);            
        }

        $OldProduct = $DBProduct;
        if ($OldProduct) {
            $OldProduct->update($request->except(['_token','image','related_products']));
            if (!$request->has('status')) {
                $OldProduct->update(['status'=>'0']);
            }
            if (!$request->has('is_taxable')) {
                $OldProduct->update(['is_taxable'=>'0']);
            }
            $file = $request->file('image');
            //$OldProduct = $OldProduct->first();
            if($file!=null){
                if($OldProduct->image!=''){
                    $Image = Functions::UploadsPath(config('theme.PRODUCTS_UPLOAD')).$OldProduct->image;
                    if(\File::exists($Image)){
                        \File::delete($Image);
                        foreach($projectObj->sizes as $size){
                            \File::delete(Functions::GetImageName($Image,'-'.$size['width'].'x'.$size['height']));
                        }
                    }
                }
                $image_data = Functions::UploadPic($file,config('theme.PRODUCTS_UPLOAD'));
                $OldProduct->update(['image'=>$image_data['encrypted_name']]);
            }
            $product = $OldProduct;
            //RelatedProducts::where(['product_id'=>$product->id])->delete();
        }else{
            $inputs = $request->all();
            $file = $request->file('image');
            \Log::error($file);
            if($file->isValid()){
                $image_data = Functions::UploadPic($file,config('theme.PRODUCTS_UPLOAD'));
                $inputs['image']=$image_data['encrypted_name'];
            }
            //dd('a');
            $newProduct =  Product::create($inputs);
            $product = $newProduct;
        }
        $Image = Functions::UploadsPath(config('theme.PRODUCTS_UPLOAD')).$product->image;
        $Image = public_path().'/'.$Image;
        $Image = str_replace('public/public','public',$Image);
        chmod($Image,0777);

        foreach($projectObj->sizes as $size){
            $ImagePath = $Image;
            $newName = Functions::GetImageName($Image,'-'.$size['width'].'x'.$size['height']);
            Functions::ResizeImage($ImagePath,$size['width'],$size['height'],$newName);
            /*$img = \Image::make($Image)->resize(
                $size['width'],
                $size['height']
            );
            $img->save(Functions::GetImageName($Image,'-'.$size['width'].'x'.$size['height']));*/
            chmod(Functions::GetImageName($Image,'-'.$size['width'].'x'.$size['height']),0777);
        }

        /*foreach($request->related_products as $id){
            RelatedProducts::create([
                'product_id'=>$product->id,
                'related_product_id'=>$id
            ]);
        }*/
        if($request->related_products){
            $product->relatedproducts()->sync($request->related_products);
        }

    	return response()->json(array(
            "status" => "success",
            "message"=>'Data Saved',
        ));
    }
    public function postGetSingleProduct(Request $request,$id){
        $inputs = Product::find($id);
        $image = ($inputs->image!='' ? url(Functions::UploadsPath(config('theme.PRODUCTS_UPLOAD')).$inputs->image) : "");
        $relatedProducts = RelatedProducts::where(['product_id'=>$id])->get()->pluck(['related_product_id']);
        $filteredArr = [
            'id'=>["type"=>"text",'value'=>$inputs->id],
            'cat_id'=>["type"=>"select",'value'=>$inputs->cat_id],
            'subcat_id'=>["type"=>"select",'value'=>$inputs->subcat_id,'wait'=>'1'],
            'product_name'=>["type"=>"text",'value'=>$inputs->product_name],
            'fr_product_name'=>["type"=>"text",'value'=>$inputs->fr_product_name],
            'description'=>["type"=>"textarea",'value'=>$inputs->description],
            'fr_description'=>["type"=>"textarea",'value'=>$inputs->fr_description],
            'unit'=>["type"=>"select",'value'=>$inputs->unit],
            'status'=>["type"=>"checkbox",'checkedValue'=>explode(',',$inputs->status)],
            'recycle_fee'=>["type"=>"text",'value'=>$inputs->recycle_fee],
            'is_taxable'=>["type"=>"checkbox",'checkedValue'=>explode(',',$inputs->is_taxable)],
            'image'=>["type"=>"image",'file'=>$image],
            'related_products'=>["type"=>"multi-select",'selectedValue'=>$relatedProducts],
        ];
        return response()->json(array(
            "status" => "success",
            "inputs"=>$filteredArr,
        ));
    }
    public function deleteProduct(Request $request,$id){
        $projectObj = new Product;
        $product = Product::find($id);
        RelatedProducts::where(['product_id'=>$id])->delete();
        if($product->image!=''){
            $Image = Functions::UploadsPath(config('theme.PRODUCTS_UPLOAD')).$product->image;
            if(\File::exists($Image)){
                \File::delete($Image);
                foreach($projectObj->sizes as $size){
                    \File::delete(Functions::GetImageName($Image,'-'.$size['width'].'x'.$size['height']));
                }
            }
        }
        $product->delete();
        return response()->json(array(
            "status" => "success",
        ));
    }

    public function ProductRequest(){
        $cats = _category::getAllCategories('1','1','0');
        $catsoptions = _category::where(['parent_id'=>'0'])->lists('category_name','id')->all();
        $units = [''=>''] + _list::where(['list_name'=>'units'])->lists('friendly_name','id')->all();
        
        $csses = [
            config('theme.ASSETS').config('theme.ADMIN_PATH').'plugins/fontawesome-iconpicker/dist/css/fontawesome-iconpicker.min.css',
            //config('theme.ASSETS').config('theme.ADMIN_PATH').'plugins/iconpicker/css/jquery.fonticonpicker.css'
        ];
        $jsses = [
            config('theme.ASSETS').config('theme.ADMIN_PATH').'plugins/fontawesome-iconpicker/dist/js/fontawesome-iconpicker.js',
            //config('theme.ASSETS').config('theme.ADMIN_PATH').'plugins/iconpicker/jquery.fonticonpicker.js',
        ];
        $data = [
            'cats'=>$cats,
            'catsoptions'=>$catsoptions,
            'units'=>$units,
            'csses'=>$csses,
            'jsses'=>$jsses,
        ];
        return view('admin.product_request',$data);
    }

    public function PostProductRequest(){
        $PRobj = new ProductRequest;
        $products = DB::table('product_requests as pr')
                    ->leftJoin('store_details as sd', 'sd.id', '=', 'pr.store_id')
                    ->leftJoin('_categories as cat', 'cat.id', '=', 'pr.category')
                    ->leftJoin('_categories as sub_cat', 'sub_cat.id', '=', 'pr.sub_category')
                    ->leftJoin('_list as li', 'li.id', '=', 'pr.unit')
                    ->select(
                        'pr.id',
                        'sd.storename',
                        'pr.product_name',
                        'pr.description',
                        DB::raw('IF(cat.category_name IS NULL, pr.category,cat.category_name) as category'),
                        DB::raw('IF(sub_cat.category_name IS NULL, pr.sub_category,sub_cat.category_name) as sub_category'),
                        'li.friendly_name as unit',
                        'pr.status'
                    )->get();
        $products = collect($products);
        return Datatables::of($products)
        ->editColumn('product_name', function ($data) {
            $id = $data->id;
            return Html::link("#",$data->product_name,['onclick'=>'viewProduct(this)','data-id'=>$id]);
        })
        ->editColumn('status', function ($data) use($PRobj) {
            $status = "<span class='".$PRobj->Status[$data->status]['class']."'>".$PRobj->Status[$data->status]['title'].'</span>';
            $id = $data->id;
            if($data->status=='1'){
                $status .="<div class='action-controls abcd'>";
                $status .="   <a href='javascript:;' class='text-success show-tooltip' title='Approve' data-id='".$id."' data-status='2' onclick='ChangeStatus(this)'><i class='fa fa-check'></i></a>"; 
                $status .="   <a href='javascript:;' class='text-danger show-tooltip' title='Reject' data-id='".$id."' data-status='3' onclick='ChangeStatus(this)'><i class='fa fa-ban'></i></a>";
                $status .="   <a href='javascript:;' onclick='GetDelete(this)' data-id='".$id."'><i class='fa fa-remove'></i></a>";    
                $status .="</div>";
            }
            if($data->status=='3'){
                $status .="<div class='action-controls'>";  
                $status .="   <a href='javascript:;' onclick='GetDelete(this)' data-id='".$id."'><i class='fa fa-remove'></i></a>";   
                $status .="</div>";
            }
            return $status;
        })
        ->removeColumn('id')
        ->make();
    }

    public function PostProductRequestStatus(Request $request){
        $Product = ProductRequest::where(['id'=>$request->input('id')])->first();
        $status = $request->input('status');
        if($status=='2'){
            $oldcat = $Product->category;
            $oldsubcat = $Product->sub_category;
            $parentId = $Product->category;

            $productArr = [
                'cat_id'=>$oldcat,
                'subcat_id'=>$oldsubcat,
                'product_name'=>$Product->product_name,
                'description'=>$Product->description,
                'unit'=>$Product->unit,
                'image'=>$Product->image,
                'status'=>'1',
            ];

            //$oldProduct = Product::where(DB::raw('lower(product_name)'),'=',strtolower($Product->product_name))->count();
            //$oldProduct1 = Product::where(DB::raw('lower(fr_product_name)'),'=',strtolower($request->fr_product_name))->count();

            $cat = _category::where(['id'=>$oldcat])->first();
            $subcat = _category::where(['id'=>$oldsubcat])->first();
            if(!$cat){
                $data = [
                    'order'=>_category::max('order')+1,
                    'slug'=>str_slug($oldcat,'_'),
                    'category_name'=>$oldcat,
                ];
                $newCat = _category::create($data);
                $parentId = $newCat->id;
                $productArr['cat_id'] = $parentId;
            }
            if(!$subcat){
                $data = [
                    'order'=>'0',
                    'parent_id'=>$parentId,
                    'slug'=>str_slug($oldsubcat,'_'),
                    'category_name'=>$oldsubcat,
                ];
                $newSubCat = _category::create($data); 
                $productArr['subcat_id'] = $newSubCat->id;
            }
            Product::create($productArr);
        }
        $Product->update($request->only(['status']));
        return response()->json(array(
            "status" => "success",
        ));
    }
    public function getPriceLogs(){
        $csses = [
            config('theme.ASSETS').config('theme.ADMIN_CSS').'plugins/datatables/extensions/Buttons/buttons.dataTables.min.css',
        ];
        $jsses = [
            config('theme.ASSETS').config('theme.ADMIN_CSS').'plugins/datatables/extensions/Buttons/dataTables.buttons.min.js',
            config('theme.ASSETS').config('theme.ADMIN_CSS').'plugins/datatables/extensions/Buttons/buttons.flash.min.js',
            config('theme.ASSETS').config('theme.ADMIN_CSS').'plugins/datatables/extensions/Buttons/jszip.min.js',
            config('theme.ASSETS').config('theme.ADMIN_CSS').'plugins/datatables/extensions/Buttons/buttons.html5.min.js',
            config('theme.ASSETS').config('theme.ADMIN_CSS').'plugins/datatables/extensions/Buttons/buttons.print.min.js',
        ];
        $data = [
            'csses'=>$csses,
            'jsses'=>$jsses
        ];
        return view('admin.price-logs', $data);
    }
    public function getPriceLogsData(){
        $log =  StoreProductPriceLogs::
                leftJoin('store_details', 'store_details.id', '=', 'store_product_price_logs.store_id')
                ->leftJoin('products', 'products.id', '=', 'store_product_price_logs.product_id')
                ->select([
                    'store_details.storename','products.product_name',
                    'store_product_price_logs.store_id','store_product_price_logs.product_id',
                    'store_product_price_logs.old_price','store_product_price_logs.new_price',
                    'store_product_price_logs.created_at'
                ]);
        return  Datatables::of($log)
                ->editColumn('created_at', function ($data) {
                    $date = \Carbon::parse($data->created_at)->format('d M Y');
                    return $date;
               })
               ->removeColumn('store_id')
               ->removeColumn('product_id')
               ->make();
    }
}
