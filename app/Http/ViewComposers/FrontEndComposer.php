<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Product;
use App\_list;

class FrontEndComposer
{
    public $newArr = array();

    /**
     * Create a new profile composer.
     *
     * @return void
     */
    public function __construct()
    {
        // Dependencies automatically resolved by service container...

        $Products = Product::where(['status'=>1])->get(['id as id','product_name as value_1','fr_product_name as value_2','image as image','min_price','max_price']);
        
        foreach($Products as $product){

            $product['image'] = url(\Functions::UploadsPath(config('theme.PRODUCTS_UPLOAD')).\Functions::GetImageName($product->image,'-32x32'));
            if(\LaravelLocalization::getCurrentLocale()=='en'){
                $product['value'] = $product['value_1'];
            }else{
                
                $product['value'] = $product['value_2'];
            }
            if($product->min_price!='0' && $product->max_price!='0'){
                $product['value'] .=' ['.\Functions::GetPrice($product->min_price).' to '.\Functions::GetPrice($product->max_price).']';
            }
            $this->newArr[] = $product;
        }
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        //dd(\Auth::user());
        $select = trans('keywords.Select');
        $zips = [""=>""] + _list::where(['list_name'=>'newsletter_zip'])->lists('item_name','friendly_name')->all();
        $view->with(['ProductArr'=>json_encode($this->newArr),"select_trans"=>$select,"zips"=>$zips]);
    }
}