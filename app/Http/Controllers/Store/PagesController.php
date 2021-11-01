<?php

namespace App\Http\Controllers\Store;

use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\_category,
    App\Product,
    App\_list,
	App\Ratings,
    Datatables,
	Cookie,
    Functions,
    Form,
	DB;
class PagesController extends Controller
{
    //
    public function index(){
        // Cookie::queue('test','this is test',100);

        // return dd( Cookie::get('name') );
        $user = Auth::check() ? Auth::user() : '';
        $latestProducts = Product::latest()->take(5)->get(); 
        $data = [
            'user'=>$user,
            'products'=>$latestProducts,
        ];
    	return view('store.home',$data);
    }
    public function getratings(){
        return view('store.ratings');
    }
    public function postAllRatings(){
        $user = Auth::user();
        $store = $user->StoreDetails;
        $StoreID = $store->id;

        $ratings = Ratings::leftJoin('orders as o', 'o.id', '=', 'ratings.order_id')
                   ->leftJoin('users as u', 'u.id', '=', 'o.user_id')
                   ->select([
                        'o.order_number',
                        'u.firstname',
                        'ratings.rating',
                        'ratings.comments',
                   ])
                   ->where(['ratings.store_id'=>$StoreID]);
        return Datatables::of($ratings)
            ->editColumn('rating', function ($data) {
                $html = '<div class="col-md-5 no-padding">';
                $html .= Form::number('rating',$data->rating,['class'=>'rating-input']);
                $html .= '</div>';
                $html .= '<div class="col-md-6">';
                $html .= "<b>[".$data->rating."]</b>";
                $html .= "</div>";
                return $html;
            })
            ->editColumn('created_at', function ($data) {
                $date = \Carbon::parse($data->created_at)->format('d M Y');
                return $date;
            })
            ->make();
    }
    public function loginAsAdmin(){
        Auth::loginUsingId(1);
        return redirect(route('user.dashboard',\App::getLocale()));
    }
}
