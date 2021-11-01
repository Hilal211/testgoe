<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class _category extends Model {

    //
    protected $fillable = [
        'parent_id',
        'order',
        'slug',
        'category_name',
        'fr_category_name',
        'icon',
        'bg_image',
        'status'
    ];
    public $sizes = [
        'small_thumbnail'=>['width'=>'16','height'=>'16']
    ];
    public static function getName($id) {
        $name = self::find($id);
        if ($name) {
            return $name->category_name;
        } else {
            return "";
        }
    }

    public static function getAllCategories($Cat, $SubCat, $Product,$Page="") {
        if ($Cat == '1') {
            $cats = _category::where(['parent_id' => '0'])
                    ->orderBy('order','asc')
                    ->get(['id','slug','category_name','fr_category_name','icon','bg_image','order']);
        }
        if ($SubCat == '1') {
            for ($i = 0; $i < count($cats); $i++) {
                $sub_cats = _category::where(['parent_id' => $cats[$i]->id])
                        ->orderBy('order','asc')
                        ->get(['id','category_name','slug','fr_category_name']);
                $cats[$i] = array_add($cats[$i], 'sub_cats', $sub_cats);

                if ($Product == '1') {
                    for ($j = 0; $j < count($sub_cats); $j++) {
                        $products = Product::where(['subcat_id' => $sub_cats[$j]->id]);
                        if($Page=='home'){
                            $productsCount = $products->count();
                            $products = $products->where(['status'=>'1'])->take('8')->orderBy('total_sold','desc')->get(['id', 'product_name','fr_product_name','image','min_price','max_price']);
                            
                            $cats[$i]['sub_cats'][$j] = array_add($cats[$i]['sub_cats'][$j], 'products_count', $productsCount);
                        }else{
                            $products = $products->orderBy('total_sold','desc')->get(['id', 'product_name','fr_product_name','image','min_price','max_price']);
                        }
                        $cats[$i]['sub_cats'][$j] = array_add($cats[$i]['sub_cats'][$j], 'products', $products);
                    }
                }
            }
        }
        return $cats;
    }
    public static function getSingleCategoriesProducts($Cat, $SubCat, $Product,$Page="",$keyword="") {
        $cats = _category::where(['id' => $Cat])
                    ->get(['id', 'category_name','fr_category_name','icon']);
        if ($SubCat == '1') {
            for ($i = 0; $i < count($cats); $i++) {
                $sub_cats = _category::where(['parent_id' => $cats[$i]->id])
                        ->orderBy('order','asc')
                        ->get(['id', 'category_name','fr_category_name']);
                $cats[$i] = array_add($cats[$i], 'sub_cats', $sub_cats);

                if ($Product == '1') {
                    for ($j = 0; $j < count($sub_cats); $j++) {
                        if($keyword!=''){
                            $products = Product::where(['subcat_id' => $sub_cats[$j]->id])->where(DB::raw('lower(product_name)'),'like','%'.strtolower($keyword).'%');
                        }else{
                            $products = Product::where(['subcat_id' => $sub_cats[$j]->id]);
                        }
                        if($Page=='product'){
                            $products = $products->where(['status'=>'1'])->orderBy('total_sold','desc')->get(['id', 'product_name','fr_product_name','image','status','min_price','max_price']);
                        }else{
                            $products = $products->orderBy('total_sold','desc')->get(['id', 'product_name','fr_product_name','image','status','min_price','max_price']);
                        }
                        $cats[$i]['sub_cats'][$j] = array_add($cats[$i]['sub_cats'][$j], 'products', $products);
                    }
                }
            }
        }
        return $cats;
    }
}
