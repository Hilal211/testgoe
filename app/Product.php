<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\_category;
use App\RelatedProducts;

class Product extends Model {

    //
    protected $fillable = [
        'cat_id', 'subcat_id', 'product_name','fr_product_name','description','fr_description', 'unit','recycle_fee', 'image', 'status','is_taxable','min_price','max_price','total_sold'
    ];
    public $sizes = [
        'small_thumbnail'=>['width'=>'16','height'=>'16'],
        'normal_thumbnail'=>['width'=>'32','height'=>'32']
    ];

    public function getCategoryNameAttribute() {
        if ($this->cat_id != '') {
            return _category::getName($this->cat_id);
        } else {
            return "";
        }
    }

    public function getsubCategoryNameAttribute() {
        if ($this->subcat_id != '') {
            return _category::getName($this->subcat_id);
        } else {
            return "";
        }
    }

    public function getStatusNameAttribute() {
        if ($this->status == '1') {
            return 'Active';
        } else {
            return 'Inactive';
        }
    }

    public function getProductNameWithPriceAttribute(){
        $Name = (\App::getLocale()=='en' ? $this->product_name : $this->fr_product_name);
        if($this->min_price!='0' && $this->max_price!='0'){
            $Name .=' ['.\Functions::GetPrice($this->min_price).' to '.\Functions::GetPrice($this->max_price).']';
        }
        return $Name;
    }

    public function relatedproducts(){
        return $this->belongsToMany('App\RelatedProducts','related_products', 'product_id','related_product_id')->withTimestamps();
    }
    public function relatedProductData(){
        return $this->hasMany(RelatedProducts::class,'product_id','id');
    }
}
