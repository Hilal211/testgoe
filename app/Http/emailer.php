<?php
namespace App\Http;
use Collective\Html\HtmlFacade;
use App\Http\Request;
use Mailgun;
use Mail;

class Emailer {
	public static function SendEmail($type,$data){
		if(env('APP_ENV')=='local'){
			return true;
		}
		/* OK satus $response->http_response_code=='200' */
		switch ($type) {
        case "customer.order":
            return self::send_customer_neworder($data);
        case "customer.order.rejected":
            return self::send_customer_order_rejected($data);
        case "customer.order.refunded":
            return self::send_customer_order_refunded($data);
        case "customer.order.shipped":
            return self::send_customer_order_shipped($data);
        case "store.order":
            return self::send_store_neworder($data);
        case "verify.shopper":
            return self::send_shopper_verify($data);
        case "verify.store":
            return self::send_store_verify($data);
        case "forgot.password":
            return self::send_user_password($data);
        case "contact.us":
            return self::send_admin_contactus($data);
        case "customer.contact.us":
            return self::send_customer_contactus($data);
        case "store.stripe":
            return self::send_store_stripe_account($data);
        case "store.welcome":
            return self::send_store_welcome($data);
        case "admin.new_shopper":
            return self::send_admin_shopper_registration($data);
        case "admin.new_store":
            return self::send_admin_store_registration($data);
        case "admin.new_order":
            return self::send_admin_neworder($data);
        case "profile.changed":
            return self::send_admin_profile_changed($data);
        case "store.new_product_request":
            return self::send_store_product_request($data);
        case "admin.new_product_request":
            return self::send_admin_product_request($data);
        case "admin.newsletter":
            return self::send_admin_newsletter($data);
        case "test":
            return self::send_test($data);
        }
	}

	public static function send_customer_neworder($data){
		$response = Mailgun::send('emails.customer_new_order-'.\App::getLocale(), $data, function($message) use ($data) {
            $message->to($data['user']->email,$data['user']->username)->subject(trans('keywords.Your order').' #'.$data['orderNumber'].' '.trans('keywords.has been placed successfully'));
        });
        return $response;
	}
	public static function send_customer_order_rejected($data){
		$response = Mailgun::send('emails.customer_order_rejected-'.\App::getLocale(), $data, function($message) use ($data) {
            $message->to($data['user']->email,$data['user']->username)->subject(trans('keywords.Rejection of your order'));
        });
        return $response;
	}
	public static function send_customer_order_refunded($data){
		$response = Mailgun::send('emails.customer_order_refunded-'.\App::getLocale(), $data, function($message) use ($data) {
            $message->to($data['user']->email,$data['user']->username)->subject(trans('keywords.Refund for your recent purchase'));
        });
        return $response;
	}
	public static function send_customer_order_shipped($data){
		$response = Mailgun::send('emails.customer_order_shipped-'.\App::getLocale(), $data, function($message) use ($data) {
            $message->to($data['user']->email,$data['user']->username)->subject(trans('keywords.Order en route'));
        });
        return $response;
	}
	public static function send_store_neworder($data){
		$response = Mailgun::send('emails.store_new_order-'.\App::getLocale(), $data, function($message) use ($data) {
            $message->to($data['store_user']->User->email,$data['store_user']->User->username)->subject(trans('keywords.New order #').$data['orderNumber'].' '.trans('keywords.has arrived').' | '.$data['order']->CityName.', '.$data['order']->StateName.'');
        });
        return $response;
	}
	public static function send_shopper_verify($data){
		$response = Mailgun::send('emails.buyer_verification-'.\App::getLocale(), $data, function($message) use ($data) {
			$message->to($data['user']->email,$data['user']->username)->subject(trans('keywords.Welcome to Goecolo! Please verify your account.'));
		});
		return $response;
	}
	public static function send_store_verify($data){
		$response = Mailgun::send('emails.store_verification-'.\App::getLocale(), $data, function($message) use ($data) {
			$message->to($data['user']->email,$data['user']->username)->subject(trans('keywords.Welcome to Goecolo! Please verify your account.'));
		});
		return $response;
	}
	public static function send_user_password($data){
		$response = Mailgun::send('emails.forgot-password-'.\App::getLocale(), $data, function($message) use ($data) {
			$message->to($data['user']->email,$data['user']->username)->subject(trans('keywords.Your new password'));
		});
		return $response;
	}
	public static function send_admin_contactus($data){
		$response = Mailgun::send('emails.contact_us-'.\App::getLocale(), $data, function($message) use ($data) {
			$message->from($data['email'], $data['name']);
			$message->to(config('theme.ADMIN_EMAIL'),config('theme.ADMIN_NAME'))->subject(trans('keywords.Inquiry Received'));
		});
		return $response;
	}
	public static function send_customer_contactus($data){
		$response = Mailgun::send('emails.guest_contact_us-'.\App::getLocale(), $data, function($message) use ($data) {
			$message->to($data['email'], $data['name']);
			$message->subject(trans('keywords.Inquiry Received'));
		});
		return $response;
	}
	public static function send_store_stripe_account($data){
		$response = Mailgun::send('emails.stripe_account-'.\App::getLocale(), $data, function($message) use ($data) {
			$message->to($data['store']->User->email,$data['store']->User->username)->subject(trans('keywords.Your payment details are set for Goecolo!'));
		});
		return $response;
	}
	public static function send_store_welcome($data){
		$response = Mailgun::send('emails.store_welcome-'.\App::getLocale(), $data, function($message) use ($data) {
			$message->to($data['user']->email,$data['user']->username)->subject(trans('keywords.Welcome to Goecolo!'));
		});
		return $response;
	}

	public static function send_admin_store_registration($data){
		$response = Mailgun::send('emails.admin_new_store-'.\App::getLocale(), $data, function($message) use ($data) {
			$message->to(config('theme.ADMIN_EMAIL'),config('theme.ADMIN_NAME'))->subject(trans('keywords.A new store has just registered.'));
		});
		return $response;
	}
	public static function send_admin_shopper_registration($data){
		$response = Mailgun::send('emails.admin_new_shopper-'.\App::getLocale(), $data, function($message) use ($data) {
			$message->to(config('theme.ADMIN_EMAIL'),config('theme.ADMIN_NAME'))->subject(trans('keywords.A new Shopper has just registered'));
		});
		return $response;
	}
	public static function send_admin_neworder($data){
		$response = Mailgun::send('emails.admin_new_order-'.\App::getLocale(), $data, function($message) use ($data) {
            $message->to(config('theme.ADMIN_EMAIL'),config('theme.ADMIN_NAME'))->subject(trans('keywords.New order #').$data['orderNumber'].' '.trans('keywords.has arrived').' | '.$data['order']->CityName.', '.$data['order']->StateName.'');

        });
        return $response;
	}

	public static function send_admin_profile_changed($data){
		$response = Mailgun::send('emails.admin_profile_changed-'.\App::getLocale(), $data, function($message) use ($data) {
			$message->to(config('theme.ADMIN_EMAIL'),config('theme.ADMIN_NAME'))->subject(trans('keywords.User Profile Changed'));
		});
		return $response;
	}
	public static function send_store_product_request($data){
		$response = Mailgun::send('emails.store_product_request-'.\App::getLocale(), $data, function($message) use ($data) {
			$message->to($data['user']->email,$data['user']->username)->subject(trans('keywords.We received your product request'));
		});
		return $response;
	}
	public static function send_admin_product_request($data){
		$response = Mailgun::send('emails.admin_product_request-'.\App::getLocale(), $data, function($message) use ($data) {
			$message->to(config('theme.ADMIN_EMAIL'),config('theme.ADMIN_NAME'))->subject(trans('keywords.New Product Request'));
		});
		return $response;
	}
	public static function send_test($data){
		$response = Mailgun::send('emails.test', $data, function($message) use ($data) {
			$message->to('testwts01@outlook.com','Goecolo')->subject('Test from mailgun!!');
		});
		return $response;
	}
	public static function send_admin_newsletter($data){
		$response = Mail::send([], $data, function($message) use($data){
			$message->to($data['user']->email)
			->subject($data['subject'])
			->setBody($data['body'], 'text/html');
		});
		return $response;
	}
}