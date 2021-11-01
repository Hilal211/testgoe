<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Product;
use Cookie;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        //$newArr = "";

        $guid = Cookie::get('guid');
        $zip = Cookie::get('zip');
        $timezone = Cookie::get('timezone');
        
        //$user = \Auth::user();
        //dd($user);
        // $storeCount = 0;
        // if($user->hasrole('admin')){
        //     $storeCount = StoreDetails::count();
        // }
        /*$Title = "";
        if(\Auth::check() && \Auth::user()->hasrole('store')){
            $Title = "Goecolo Admin | STOREADMIN";
        }elseif(\Auth::check() && \Auth::user()->hasrole('admin')){
            $Title = "Goecolo Admin | SUPPERADMIN";
        }elseif(\Auth::check() && \Auth::user()->hasrole('customer')){
            $Title = "Goecolo";
        }else{
            $Title = "Goecolo";
        }*/
        //['ProductArr'=>json_encode($newArr),
        view()->share(["zip"=>$zip,'timezone'=>$timezone,"guid"=>$guid]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
