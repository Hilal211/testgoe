<?php

namespace App\Http\Controllers\Store;

use Illuminate\Http\Request;
use Input;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\_category,

    App\Product,
    App\User,
    App\_list,
    App\StoreProducts,
    App\StoreDetails,
    App\ProductRequest,
    App\StoreBankDetails,
    App\StoreShippingSettings,
    App\StoreProductPriceLogs,
    Datatables,
    Functions,
    Auth,
    Html,
    Emailer,
    Validator,
    Carbon\Carbon,
    DB;

class ProductController extends Controller
{
    //
    /*public function getStoreProducts($id){
        $storeProducts = DB::select('select * from (select @store_id:='.$id.' p) parm , _store_products');
        $units = [''=>''] + _list::where(['list_name'=>'units'])->lists('friendly_name','id')->all();
        $productsTree = array();
        foreach($storeProducts as $product)
        {
            $productsTree[$product->category_name][$product->sub_category_name][] = $product;
        }
        $data = [
            'categories'=>$productsTree,
            'units'=>$units,
        ];
    	return view('store.products',$data);
    }*/
    public function getStoreSingleProduct($storeid, $Pid)
    {
        if ($storeid == '0')
            $storeid = '';
        $product = StoreProducts::where(['store_id' => $storeid, 'product_id' => $Pid])->first();
        $productDetails = Product::where(['id' => $Pid])->first();
        //dd($productDetails->toArray());
        $Unit = _list::getName($productDetails->unit);
        if ($product) {
            $filteredArr = [
                'price' => ["type" => "text", 'value' => $product->price],
                'id' => ["type" => "text", 'value' => $product->id],
                'product_id' => ["type" => "text", 'value' => $product->product_id],
                'store_id' => ["type" => "text", 'value' => $storeid],
                'unit' => ["type" => "label", 'value' => 'Per ' . $Unit],
                'status' => ["type" => "checkbox", 'checkedValue' => explode(',', $product->status)],
                'inventory' => ["type" => "text", 'value' => $product->inventory],
            ];
        } else {
            $filteredArr = [
                'price' => ["type" => "text", 'value' => ''],
                'id' => ["type" => "text", 'value' => '0'],
                'product_id' => ["type" => "text", 'value' => $Pid],
                'store_id' => ["type" => "text", 'value' => $storeid],
                'unit' => ["type" => "label", 'value' => 'Per ' . $Unit],
                'status' => ["type" => "checkbox", 'checkedValue' => '1'],
                'inventory' => ["type" => "text", 'value' => '0'],
            ];
        }
        return response()->json(array(
            "status" => "success",
            "inputs" => $filteredArr,
        ));
    }
    public function PostSingleProduct(Request $request)
    {
        $this->validate($request, [
            "price" => 'required|numeric|min:0',
            "inventory" => 'required|numeric|min:0',
        ]);

        $price = $request->input('price');
        $ActualProduct = Product::where(['id' => $request->input('product_id')])->first();

        /*if($SingleStorePrice==1){
            $data['min_price'] = $price;
            $data['max_price'] = $price;
            $ActualProduct->update($data);
        }else{
            /* both are zero so min & max is equal to price
            if($ActualProduct->min_price=='0' && $ActualProduct->max_price=='0'){
                $data['min_price'] = $price;
                $data['max_price'] = $price;
                $ActualProduct->update($data);
            }
            /* if price is less then min set that to min
            elseif($price<$ActualProduct->min_price){
                $data['min_price'] = $price;
                $ActualProduct->update($data);
            }
            /* if price is max then max set that to max
            elseif($price>$ActualProduct->max_price){
                $data['max_price'] = $price;
                $ActualProduct->update($data);
            }
        }*/

        $product = "";
        $OldProduct = StoreProducts::where(['id' => $request->input('id')])->first();
        if ($OldProduct) {
            $oldPrice = $OldProduct->price;
            $newPrice = $request->price;

            StoreProductPriceLogs::create([
                'store_id' => $request->store_id,
                'product_id' => $request->product_id,
                'old_price' => $oldPrice,
                'new_price' => $newPrice,
            ]);

            $OldProduct->update($request->except(['_token']));
            $product = $OldProduct;
            if (!$request->has('status')) {
                $OldProduct->update(['status' => '0']);
            }
            if ($OldProduct->price == '0' || $OldProduct->inventory == '0') {
                $OldProduct->update(['status' => '0']);
            }
        } else {
            $inputs = $request->all();
            $newProduct =  StoreProducts::create($inputs);
            $product = $newProduct;
            if ($product->price == '0' || $product->inventory == '0') {
                $product->update(['status' => '0']);
            }

            $oldPrice = 0;
            $newPrice = $request->price;

            StoreProductPriceLogs::create([
                'store_id' => $request->store_id,
                'product_id' => $request->product_id,
                'old_price' => $oldPrice,
                'new_price' => $newPrice,
            ]);
        }
        $MinProductPrice = StoreProducts::where(['product_id' => $request->input('product_id')])->min('price');
        $MaxProductPrice = StoreProducts::where(['product_id' => $request->input('product_id')])->max('price');
        $data['min_price'] = $MinProductPrice;
        $data['max_price'] = $MaxProductPrice;
        $ActualProduct->update($data);

        /*$StoreId = $request->input('store_id');
        $StoreProduct = DB::select('select * from (select @store_id:='.$StoreId.' p) parm , _store_products where id = '.$product->product_id.'');
        $data = ['product'=>$StoreProduct[0]];
        $ProductHTML = view('store.single-items.products.product',$data)->render(); */
        $today = Carbon::now();
        $lastSixMonths = $today->subMonths(6);

        $log = StoreProductPriceLogs::where(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), '<', $lastSixMonths->format('Y-m-d'))->delete();
        return response()->json(array(
            "status" => "success",
            "message" => 'Data Saved',
        ));
    }
    public function getStoreProducts($id)
    {
        $cats = _category::getAllCategories('1', '1', '0');
        $units = ['' => ''] + _list::where(['list_name' => 'units'])->lists('friendly_name', 'id')->all();
        $bank = StoreBankDetails::where(['store_id' => $id])->first();
        $store_details = StoreDetails::where(['id' => $id])->first();
        /*$filteredArr = array();
        foreach($cats as $cat){
            $storeProducts = DB::select('select count(*) as total from (select @store_id:='.$id.' p) parm , _store_products where cat_id='.$cat->id.' and store_id!=""');
            $storeProducts = collect($storeProducts);
            //dd($storeProducts[0]->total);
            $cat['total_products'] = $storeProducts[0]->total;
        }*/
        $NoChargesSet = false;
        if ($store_details->homedelievery == '1') {
            $settings = StoreShippingSettings::where(['store_id' => $id, 'charge_amount' => '0'])->count();
            if ($settings == '8') {
                $NoChargesSet = true;
            }
        }
        $data = [
            'cats' => $cats,
            'units' => $units,
            'store_id' => $id,
            'store_bank' => $bank,
            'no_charge_set' => $NoChargesSet,
        ];
        //dd($data);
        return view('store.products', $data);
    }
    public function PostSubCatView(Request $request, $store_id, $cat_id)
    {
        $keyword = $request->keyword;
        if ($keyword == '') {
            $storeProducts = DB::select('select * from (select @store_id:=' . $store_id . ' p) parm , _store_products where cat_id=' . $cat_id . ' order by id asc');
        } else {
            $storeProducts = DB::select('select * from (select @store_id:=' . $store_id . ' p) parm , _store_products where cat_id=' . $cat_id . ' and lower(product_name) like "' . "%" . $keyword . "%" . '" order by id asc');
        }
        $bank = StoreBankDetails::where(['store_id' => $store_id])->first();

        $productsTree = array();
        foreach ($storeProducts as $product) {
            $productsTree[$product->category_name][$product->sub_category_name][] = $product;
        }
        /*$category = _category::getSingleCategoriesProducts($cat_id,'1','1');*/
        $data = ['cats' => $productsTree, 'store_bank' => $bank];
        $data['cat_id'] = $cat_id;
        $data['keyword'] = $keyword;
        //dd($productsTree);
        $HTML = view('common.dynamic.subcats-from-cat', $data)->render();
        return response()->json(array(
            "status" => "success",
            "SubCategoryHtml" => $HTML,
        ));
    }

    public function getRequestProduct()
    {
        $catsoptions = ['' => ''] + _category::where(['parent_id' => '0'])->lists('category_name', 'id')->all() + ['new' => 'Add New'];
        $units = ['' => ''] + _list::where(['list_name' => 'units'])->lists('friendly_name', 'id')->all();
        $data = [
            'catsoptions' => $catsoptions,
            'units' => $units,
        ];
        return view('store.request_product', $data);
    }

    public function postRequestProduct(Request $request)
    {
        $PRobj = new ProductRequest;
        $StoreID = Auth::user()->StoreDetails->id;
        $products = DB::table('product_requests as pr')
            ->leftJoin('_categories as cat', 'cat.id', '=', 'pr.category')
            ->leftJoin('_categories as sub_cat', 'sub_cat.id', '=', 'pr.sub_category')
            ->leftJoin('_list as li', 'li.id', '=', 'pr.unit')
            ->where('pr.store_id', $StoreID)
            ->select(
                'pr.id',
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
                return Html::link("#", $data->product_name, ['onclick' => 'viewProduct(this)', 'data-id' => $id]);
            })
            ->editColumn('status', function ($data) use ($PRobj) {
                $id = $data->id;
                $status = "<span class='" . $PRobj->Status[$data->status]['class'] . "'>" . $PRobj->Status[$data->status]['title'] . '</span>';
                $links = "";
                if ($data->status == '1') {
                    $links .= "<div class='action-controls'>";
                    $links .= "   <a href='javascript:;' onclick='SetEdit(this)' data-id='" . $id . "'><i class='fa fa-pencil'></i></a>";
                    $links .= "   <a href='javascript:;' onclick='GetDelete(this)' data-id='" . $id . "'><i class='fa fa-remove'></i></a>";
                    $links .= "</div>";
                }
                //$links = Functions::GetActionLinks($data->id);
                return $status . $links;
            })
            ->removeColumn('id')
            ->make();
    }

    public function PostProductRequest(Request $request)
    {
        $projectObj = new Product;
        $rules = [
            "product_name" => 'required',
            "category" => 'required',
            "sub_category" => 'required',
            "unit" => 'required',
            "new_category" => 'required_if:category,new',
            "new_sub_category" => 'required_if:sub_category,new',
        ];
        //$validate = $this->validate($request,$rules);
        $DBProduct = ProductRequest::where(['id' => $request->input('id')])->first();
        $oldProduct = Product::where(DB::raw('lower(product_name)'), '=', strtolower($request->product_name))->count();
        $valid = Validator::make($request->all(), $rules);
        $messages = $valid->errors();
        if ($oldProduct > 0) {
            $messages->add('product_name', 'The Product name already exists.');
        }
        if ($valid->fails()) {
            return response()->json($messages, 422);
        }
        if ($oldProduct > 0) {
            return response()->json($messages, 422);
        }

        $OldProduct = $DBProduct;
        if ($OldProduct) {
            $rules["image"] = 'required';
            $inputs = $request->except(['_token', 'image']);
            $inputs['category'] = ($inputs['category'] == 'new' ? $inputs['new_category'] : $inputs['category']);
            $inputs['sub_category'] = ($inputs['sub_category'] == 'new' ? $inputs['new_sub_category'] : $inputs['sub_category']);
            unset($inputs['new_sub_category']);
            unset($inputs['new_category']);
            $OldProduct->update($inputs);
            $file = $request->file('image');
            if ($file != null) {
                if ($OldProduct->image != '') {
                    $Image = Functions::UploadsPath(config('theme.PRODUCTS_UPLOAD')) . $OldProduct->image;
                    if (\File::exists($Image)) {
                        \File::delete($Image);
                        foreach ($projectObj->sizes as $size) {
                            \File::delete(Functions::GetImageName($Image, '-' . $size['width'] . 'x' . $size['height']));
                        }
                    }
                }
                $image_data = Functions::UploadPic($file, config('theme.PRODUCTS_UPLOAD'));
                $OldProduct->update(['image' => $image_data['encrypted_name']]);
            }
            $newProduct = $OldProduct;
        } else {
            $inputs = $request->all();
            $inputs['category'] = ($inputs['category'] == 'new' ? $inputs['new_category'] : $inputs['category']);
            $inputs['sub_category'] = ($inputs['sub_category'] == 'new' ? $inputs['new_sub_category'] : $inputs['sub_category']);
            unset($inputs['new_sub_category']);
            unset($inputs['new_category']);
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                if ($file->isValid()) {
                    $image_data = Functions::UploadPic($file, config('theme.PRODUCTS_UPLOAD'));
                    $inputs['image'] = $image_data['encrypted_name'];
                }
            }
            $inputs['status'] = '1';
            $newProduct =  ProductRequest::create($inputs);

            $store_details = StoreDetails::where(['id' => $inputs['store_id']])->first();
            $user = User::where(['id' => $store_details->user_id])->first();
            $data = array(
                'store' => $store_details,
                'user' => $user
            );
            Emailer::SendEmail('store.new_product_request', $data);
            Emailer::SendEmail('admin.new_product_request', $data);
        }
        if ($newProduct->image != '') {
            $Image = Functions::UploadsPath(config('theme.PRODUCTS_UPLOAD')) . $newProduct->image;
            $Image = public_path() . '/' . $Image;
            $Image = str_replace('public/public', 'public', $Image);
            chmod($Image, 0777);
            chmod($Image, 0777);

            foreach ($projectObj->sizes as $size) {
                $ImagePath = $Image;
                $newName = Functions::GetImageName($Image, '-' . $size['width'] . 'x' . $size['height']);
                Functions::ResizeImage($ImagePath, $size['width'], $size['height'], $newName);
                // $img = \Image::make($Image)->resize(
                //     $size['width'],
                //     $size['height']
                // );
                //$img->save(Functions::GetImageName($Image,'-'.$size['width'].'x'.$size['height']));
                chmod(Functions::GetImageName($Image, '-' . $size['width'] . 'x' . $size['height']), 0777);
            }
        }
    }

    public function getSingleRequestedProduct($id)
    {
        $inputs = ProductRequest::find($id);
        $image = ($inputs->image != '' ? url(Functions::UploadsPath(config('theme.PRODUCTS_UPLOAD')) . $inputs->image) : "");
        $oldcat = $inputs->category;
        $oldsubcat = $inputs->sub_category;

        $cat = _category::where(['id' => $inputs->category])->first();
        $subcat = _category::where(['id' => $inputs->sub_category])->first();
        $inputs->category = ($cat ? $cat->id : 'new');
        $inputs->sub_category = ($subcat ? $subcat->id : 'new');
        $filteredArr = [
            'id' => ["type" => "text", 'value' => $inputs->id],
            'category' => ["type" => "select", 'value' => $inputs->category],
            'sub_category' => ["type" => "select", 'value' => $inputs->sub_category, 'wait' => '1'],
            'product_name' => ["type" => "text", 'value' => $inputs->product_name],
            'description' => ["type" => "textarea", 'value' => $inputs->description],
            'unit' => ["type" => "select", 'value' => $inputs->unit],
            'status' => ["type" => "checkbox", 'checkedValue' => explode(',', $inputs->status)],
            'image' => ["type" => "image", 'file' => $image],
        ];
        if ($inputs->category == 'new') {
            $filteredArr['new_category'] = ["type" => "text", 'value' => $oldcat];
        }
        if ($inputs->sub_category == 'new') {
            $filteredArr['new_sub_category'] = ["type" => "text", 'value' => $oldsubcat, 'wait' => '1'];
        }
        return response()->json(array(
            "status" => "success",
            "inputs" => $filteredArr,
        ));
    }

    public function DeleteSingleRequestedProduct($id)
    {
        $projectObj = new Product;
        $product = ProductRequest::find($id);
        if ($product->image != '') {
            $Image = Functions::UploadsPath(config('theme.PRODUCTS_UPLOAD')) . $product->image;
            if (\File::exists($Image)) {
                \File::delete($Image);
                foreach ($projectObj->sizes as $size) {
                    \File::delete(Functions::GetImageName($Image, '-' . $size['width'] . 'x' . $size['height']));
                }
            }
        }
        $product->delete();
        return response()->json(array(
            "status" => "success",
        ));
    }

    public function addUpdateProduct(Request $request, $idS)
    {
        $file = $request->file('file');
        $filef = $file->path();
        $id = [];
        $price = [];
        $discounted_price = [];
        $inventory=[];
        $filea = fopen($filef, 'r');
        $message="Not found this product id:";
        $error=0;
        while (($line = fgetcsv($filea)) !== false) {
            array_push($id, $line[0]);
            array_push($price, $line[1]);
            array_push($discounted_price, $line[2]);
            array_push($inventory, $line[3]);
        }
        fclose($filea);

        for ($i = 0; $i < count($id); $i++) {
            $checkReference = StoreProducts::where('product_id', $id[$i])->where('store_id', '=', $idS)->first();
            if ($checkReference) {
                $quotationLines = StoreProducts::where('product_id', $id[$i])->where('store_id', '=', $idS)->first();
            } else {
                $quotationLines = new StoreProducts();
            }
            // $checkProduct = Product::where('id', $id[$i])->first();
            // if ($checkProduct) {
                $quotationLines->product_id = $id[$i];
                $quotationLines->store_id = $idS;
                $quotationLines->price = $price[$i];
                $quotationLines->discounted_price = $discounted_price[$i];
                if($inventory[$i]){
                    $quotationLines->inventory = $inventory[$i];
                }
            // }
            // else{
            //     if($id[$i]!="id"){
            //     $message=$message . $id[$i] . " ";
            //     $error=1;
            //     }
            // }
            if (!$quotationLines->save()) {
                return response()->json(array(
                    "status" => "failed",
                ));
            }
            $quotationLines->save();
        }
        if ($error==1) {
            return response()->json(array(
                    "status" => "success",
                    "message"=>$message,
                ));
        }
        else{
            return response()->json(array(
                "status" => "success",
                "message"=>"success",
            ));
        }

        // print_r($checkReference);
    }
}
