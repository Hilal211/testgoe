<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\User;
use App\CartItems;
use Mailgun;
use Emailer;
use Auth;
use Cookie;

class UserAuthController extends Controller
{
    //
    public function getLogin(){
    	return view('frontend.login');
    }
    public function logout(){
        Auth::logout();
        return redirect(route("login"));
    }

    public function postLogin(Request $request){
        $valid = Validator::make($request->all(),[
            "email"=>'required|email',
            "password"=>'required'
        ]);
        if($valid->fails()){
            return redirect(route('login'))->withErrors($valid)->withInput();
        }
        $user = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'status' => '1',
        ];
        if (Auth::attempt($user)) {
            $user = Auth::user();
            if($user->hasRole('customer')){
                $userId = Cookie::get('guid');
                $loggedUserId = Auth::user()->id;

                $userOldCart = CartItems::where('user_id',$loggedUserId)->get();
                foreach($userOldCart as $value){
                	$pid = $value->product_id;
                	$sameProduct = CartItems::where(['user_id'=>$userId,'product_id'=>$pid])->first();
                	if($sameProduct){
                		$value->update(['qty'=>$value->qty+$sameProduct->qty]);
                		$sameProduct->delete();
                	}
                }
                CartItems::where('user_id', $userId)->update(['user_id' => $loggedUserId,'user_type'=>1]);
            }
            $storeId = $request->input('pay_store_id');
            if($storeId=='0'){
                return redirect(route('user.dashboard',\App::getLocale()));
            }else{
                return redirect(route('frontend.cart-checkout',[\App::getLocale(),$storeId]));
            }
        } else {
            $user = [
                'email' => $request->input('email'),
                'password' => $request->input('password'),
            ];
            if(Auth::attempt($user)){
                Auth::logout();
                return redirect(route('login'))->withErrors(array(trans('keywords.Your account is not verified please verify it.')))->withInput();
            }else{
                return redirect(route('login'))->withErrors(array(trans('keywords.Invalid email or password.')))->withInput();
            }
        }
    }

    public function getDashboard(){
        $user = Auth::user();
        if($user->hasRole('admin')){
            return redirect(route('admin.dashboard'));
        }elseif($user->hasRole('customer')){
            return redirect()->back();
            //return redirect(route('customer.dashboard'));
        }elseif($user->hasRole('store_owner')){
            return redirect(route('store_owner.dashboard'));
        }
    }
    public function getForgotPassword(){
        return view('frontend.forgot');
    }
    public function postForgotPassword(Request $request){
        $valid = Validator::make($request->all(),[
            "email"=>'required|email',
        ]);
        if($valid->fails()){
            return redirect(route('pages.forgot-password'))->withErrors($valid)->withInput();
        } else {
            $user = User::where(['email'=>$request->get('email')])->first();
            if($user){
                $random_password = str_random(8);//'123456';
                $user->update(['password'=>bcrypt($random_password)]);
                $data = array(
                    'user'=>$user,
                    'password'=>$random_password
                );
                Emailer::SendEmail('forgot.password',$data);
                return redirect(route('login'))->with('success',trans('keywords.Your password has been sent by email. Please login with your details below.'));
            }else{
                return redirect(route('pages.forgot-password'))->withErrors(["Invalid email address."])->withInput();
            }
        }
    }
}
