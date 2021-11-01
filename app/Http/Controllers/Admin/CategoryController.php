<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\_category,
    App\_list;
use Functions;
use App\Product;

class CategoryController extends Controller
{
    //    
    public function postCategory(Request $request){
    	$messsages = array(
            'category_name.required'=>'The Category is required',
    		'fr_category_name.required'=>'The French Category is required',
		);
    	$this->validate($request,[
            "category_name"=>'required|unique:_categories,category_name,'.$request->input('id'),
            "fr_category_name"=>'required|unique:_categories,fr_category_name,'.$request->input('id'),
        ],$messsages);


        $OldCategory = _category::where(['id'=>$request->input('id')]);
        if ($OldCategory->first()) {
        	$inputs = $request->except(['_token']);
        	$inputs['slug'] = str_slug($inputs['category_name'],'_');
        	$OldCategory->update($inputs);
            $category = $OldCategory->first();
        }else{
        	$inputs = $request->all();
        	$inputs['slug'] = str_slug($inputs['category_name'],'_');
        	$newcategory = _category::create($inputs);
            $category = $newcategory;
        }
        
    	return response()->json(array(
            "status" => "success",
            "message"=>'Data Saved',
        ));
    }
    public function postMainCategory(Request $request){
        $catObj = new _category;
        $messsages = array(
            'category_name.required'=>'The Category is required',
            'fr_category_name.required'=>'The French Category is required',
            'bg_image.required'=>'Category background image is required',
            'bg_image.image'=>'Category background can only be image',
        );
        $rules = [
            "category_name"=>'required|unique:_categories,category_name,'.$request->input('id'),
            "fr_category_name"=>'required|unique:_categories,fr_category_name,'.$request->input('id')
        ];

        /*$CatName = $request->input('category_name');
        $Already = DB::select("SELECT * FROM `_categories` where LOWER(category_name)='$CatName'");
        dd($Already);*/

        $inputs = $request->all();
        if($inputs['id']=='0'){
            $rules['bg_image'] = 'image'; 
            $rules['icon'] = 'image';
        }
        $this->validate($request,$rules,$messsages);
       
        $OldCategory = _category::where(['id'=>$request->input('id')]);
        $OldCategory = $OldCategory->first();
        if ($OldCategory) {
            $inputs = $request->except(['_token','bg_image','icon']);
            $inputs['slug'] = str_slug($inputs['category_name'],'_');
            $OldCategory->update($inputs);

            $file = $request->file('bg_image');
            if($file!=null){
                if($OldCategory->bg_image!=''){
                    $Image = Functions::UploadsPath(config('theme.CATEGORY_UPLOAD')).$OldCategory->bg_image;
                    \File::delete($Image);
                }
                $data = Functions::UploadPic($file,config('theme.CATEGORY_UPLOAD'));
                $OldCategory->update(['bg_image'=>$data['encrypted_name']]);
                $Image = Functions::UploadsPath(config('theme.CATEGORY_UPLOAD')).$data['encrypted_name'];
                $Image = public_path().'/'.$Image;
                $Image = str_replace('public/public','public',$Image);
                chmod($Image,0777);
            }


            $file = $request->file('icon');
            if($file!=null){
                if($OldCategory->icon!=''){
                    $Image = Functions::UploadsPath(config('theme.CATEGORY_UPLOAD')).$OldCategory->icon;
                    foreach($catObj->sizes as $size){
                        \File::delete(Functions::GetImageName($Image,'-'.$size['width'].'x'.$size['height']));
                    }
                    \File::delete($Image);
                }
                $data = Functions::UploadPic($file,config('theme.CATEGORY_UPLOAD'));
                $OldCategory->update(['icon'=>$data['encrypted_name']]);
                $Image = Functions::UploadsPath(config('theme.CATEGORY_UPLOAD')).$data['encrypted_name'];
                $Image = public_path().'/'.$Image;
                $Image = str_replace('public/public','public',$Image);
                chmod($Image,0777);
                foreach($catObj->sizes as $size){
                    $ImagePath = $Image;
                    $newName = Functions::GetImageName($Image,'-'.$size['width'].'x'.$size['height']);
                    Functions::ResizeImage($ImagePath,$size['width'],$size['height'],$newName);
                    chmod(Functions::GetImageName($Image,'-'.$size['width'].'x'.$size['height']),0777);
                }
            }

            $category = $OldCategory;
        }else{
            $inputs = $request->all();
            $inputs['slug'] = str_slug($inputs['category_name'],'_');
            $newcategory = _category::create($inputs);
            $category = $newcategory;
            $file = $request->file('bg_image');
            if($file!=null){
                $data = Functions::UploadPic($file,config('theme.CATEGORY_UPLOAD'));
                $category->update(['bg_image'=>$data['encrypted_name']]);
                $Image = Functions::UploadsPath(config('theme.CATEGORY_UPLOAD')).$data['encrypted_name'];
                $Image = public_path().'/'.$Image;
                $Image = str_replace('public/public','public',$Image);
                chmod($Image,0777);
            }

            $file = $request->file('icon');
            if($file!=null){
                $data = Functions::UploadPic($file,config('theme.CATEGORY_UPLOAD'));
                $category->update(['icon'=>$data['encrypted_name']]);
                $Image = Functions::UploadsPath(config('theme.CATEGORY_UPLOAD')).$data['encrypted_name'];
                $Image = public_path().'/'.$Image;
                $Image = str_replace('public/public','public',$Image);
                chmod($Image,0777);
                foreach($catObj->sizes as $size){
                    $ImagePath = $Image;
                    $newName = Functions::GetImageName($Image,'-'.$size['width'].'x'.$size['height']);
                    Functions::ResizeImage($ImagePath,$size['width'],$size['height'],$newName);
                    chmod(Functions::GetImageName($Image,'-'.$size['width'].'x'.$size['height']),0777);
                }
            }
        }
        //$category = _category::getSingleCategoriesProducts($category->parent_id,'1','1');
        //dd($category);
        $subCats = _category::where(['parent_id'=>$category->id])->get();
        $category = array_add($category, 'sub_cats',$subCats);
        $data = ['cat'=>$category];
        $HTML = view('common.dynamic.cats',$data)->render();
        
        $catsoptions = _category::where(['parent_id'=>'0'])->lists('category_name','id')->all();

        //$data = ['cat'=>$category];
        //$HTML = view('admin.single-items.category-item-part1',$data)->render();
        //$HTML .= view('admin.single-items.category-item-part2',$data)->render();
        return response()->json(array(
            "status" => "success",
            "message"=>'Data Saved',
            "CategoryHtml"=>$HTML,
            'catsoptions'=>$catsoptions,
        ));
    }
    public function deletecategory(Request $request,$id){
        $category = _category::find($id);
        $parent_id = "";
        if($category->parent_id=='0'){
            $type='cat';
            $Image = Functions::UploadsPath(config('theme.CATEGORY_UPLOAD')).$category->bg_image;
            if(\File::exists($Image)){
                \File::delete($Image);
            }
            $sub_category = _category::where(['parent_id'=>$id]);
            $sub_category->delete();
            $products = Product::where(['cat_id'=>$id]);
            $products->delete();
        }else{
            $type='sub_cat';
            $parent_id=$category->parent_id;
        }
        $category->delete();
        return response()->json(array(
            "status" => "success",
            "type" => $type,
            "parent" => $parent_id,
        ));
    }
    public function postGetCategory($id){
        $inputs = _category::find($id);
        $image = ($inputs->bg_image!='' ? url(Functions::UploadsPath(config('theme.CATEGORY_UPLOAD')).$inputs->bg_image) : "");
        $icon_image = ($inputs->icon!='' ? url(Functions::UploadsPath(config('theme.CATEGORY_UPLOAD')).$inputs->icon) : "");
        $filteredArr = [
            'id'=>["type"=>"text",'value'=>$inputs->id],
            'category_name'=>["type"=>"text",'value'=>$inputs->category_name],
            'fr_category_name'=>["type"=>"text",'value'=>$inputs->fr_category_name],
            'icon'=>["type"=>"image",'file'=>$icon_image],
            'image'=>["type"=>"image",'file'=>$image],
        ];
        return response()->json(array(
            "status" => "success",
            "inputs"=>$filteredArr,
        ));
    }
    public function postCategoryOrder(Request $request){
        //dd($request->all());
        $i=1;
        foreach($request->input('main_category') as $item){
            echo $item.' -> '.$i.'<Br>';
            //$i++;
            $cat = _category::where(['id'=>$item])->first();
            $cat->update(['order'=>$i]);
            //$cat->reset();
            $i++;
        }
    }
    public function getCatsSubCats(){
        $cats = _category::getAllCategories('1','1','0');
        $catsoptions = _category::where(['parent_id'=>'0'])->lists('category_name','id')->all();
        $units = [''=>''] + _list::where(['list_name'=>'units'])->lists('friendly_name','id')->all();
        $Products = Product::where(['status'=>'1'])->lists('product_name','id')->all();
        
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
            'products'=>$Products,
        ];
        return view('admin.cats-subcats',$data);
    }
    public function getSubCat(Request $request,$cat_id){
        $keyword = $request->keyword;
        $category = _category::getSingleCategoriesProducts($cat_id,'1','1',"",$keyword);
        $data = ['cats'=>$category];
        $data['cat_id'] = $cat_id;
        $data['keyword'] = $keyword;
        $HTML = view('common.dynamic.subcats-from-cat',$data)->render();
        return response()->json(array(
            "status" => "success",
            "SubCategoryHtml"=>$HTML,
        ));
    }
    public function postRemoveImage($id,$type){
        $catObj = new _category;
        $OldCategory = _category::where(['id'=>$id])->first();
        if($type=='bg'){
            $Image = Functions::UploadsPath(config('theme.CATEGORY_UPLOAD')).$OldCategory->bg_image;
            \File::delete($Image);
            $OldCategory->update(['bg_image'=>'']);
        }elseif($type=='icon'){
            $Image = Functions::UploadsPath(config('theme.CATEGORY_UPLOAD')).$OldCategory->icon;
            foreach($catObj->sizes as $size){
                \File::delete(Functions::GetImageName($Image,'-'.$size['width'].'x'.$size['height']));
            }
            \File::delete($Image);
            $OldCategory->update(['icon'=>'']);
        }
        return response()->json(array(
            "status" => "success"
        ));
    }
}
