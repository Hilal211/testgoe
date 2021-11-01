<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\orders;
use App\Product;
use App\User,
    App\roles,
    App\Ratings,
    App\NonServiceUser,
    App\ProductRequest,
    App\StoreDetails;

class AdminCounterComposer
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
        $roles = new roles();
        $orders = orders::count();
        $products = Product::count();
        $customers = User::Rolebased($roles->rolesIDS['customer'])->count();
        $stores = StoreDetails::where(['is_virtual'=>'0'])->count();

        $virtualStores = StoreDetails::where(['is_virtual'=>'1'])->count();

        $requestCount = ProductRequest::count();
        $requestedUserCount = NonServiceUser::count();

        $pickupsCount = orders::where(['status'=>"6",'shipped_by'=>'1'])->count();
        $RatingsCount = Ratings::count();

        $this->counters = [
            'orders'=>$orders,
            'products'=>$products,
            'customers'=>$customers,
            'stores'=>$stores,
            'virtualStores'=>$virtualStores,
            'requestCount'=>$requestCount,
            'serviceRequestCount'=>$requestedUserCount,
            'pickupsCount'=>$pickupsCount,
            'ratingsCount'=>$RatingsCount
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