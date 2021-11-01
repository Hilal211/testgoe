<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\orders;
use App\ProductRequest;
use App\Ratings;

class StoreCounterComposer
{
    public $counters = [];

    /**
     * Create a new profile composer.
     *
     * @return void
     */
    public function __construct()
    {
        // Dependencies automatically resolved by service container...
        $user = \Auth::user();
        $store = $user->StoreDetails;
        $orders = orders::where(['store_id'=>$store->id])->count();
        $requestCount = ProductRequest::where(['store_id'=>$store->id])->where(['status'=>1])->count();
        $ratingCount = Ratings::where(['store_id'=>$store->id])->count();
        $this->counters = [
            'orders'=>$orders,
            'requestCount'=>$requestCount,
            'ratingCount'=>$ratingCount,
        ];
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $select = trans('keywords.Select');
        $view->with(['counters'=>$this->counters,"select_trans"=>$select]);
    }
}