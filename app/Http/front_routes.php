<?php
Route::group(['namespace' => 'FrontEnd','middleware' => ['guest']], function() {
	Route::get('/buyer-register',["as"=>'frontend.buyer-register',"uses"=>'PagesController@getBuyerRegister']);
	Route::get('/404',["as"=>'frontend.404',"uses"=>'PagesController@get404']);
	Route::get('/store-owner',["as"=>'frontend.store_owner',"uses"=>'PagesController@getStoreOwner']);
	
	Route::post('/store_owner/{step}',["as"=>'post.store_owner',"uses"=>'PagesController@postStoreOwner']);
	Route::post('/buyer-register',["as"=>'frontend.buyer-register',"uses"=>'PagesController@postBuyerRegister']);
	Route::get('/verify/{id}/{email}',["as"=>'verify.user',"uses"=>'PagesController@postVerify']);
	Route::post('/get_notified',["as"=>'post.email.notification',"uses"=>'PagesController@postNotify']);
});

Route::group(['namespace' => 'FrontEnd'], function() {
	Route::get('/',["as"=>'frontend.home',"uses"=>'PagesController@index']);

	Route::get('/products/{cat}/{subcat?}',["as"=>'frontend.generic.cat',"uses"=>'PagesController@NormalProductView']);

	Route::get('/about',["as"=>'frontend.about',"uses"=>'PagesController@getAbout']);
	Route::get('/how-it-works',["as"=>'frontend.how-it-works',"uses"=>'PagesController@getHowItWorks']);
	
	Route::get('/terms',["as"=>'frontend.terms',"uses"=>'PagesController@getTerms']);
	Route::get('/faq',["as"=>'frontend.faq',"uses"=>'PagesController@getFAQ']);
	Route::get('/privacy',["as"=>'frontend.privacy',"uses"=>'PagesController@getPrivacy']);
	Route::get('/contact-us',["as"=>'frontend.contact',"uses"=>'PagesController@getContact']);
	
	Route::get('/test',["as"=>'frontend.test',"uses"=>'PagesController@getTest']);
	
	    # Cart Route
	Route::get('/cart/list',["as"=>'frontend.cart',"uses"=>'CartController@index']);
	Route::post('/cart/add',["as"=>'frontend.cart.add',"uses"=>'CartController@addCartItem']);
	Route::put('/cart/update',["as"=>'frontend.cart.update',"uses"=>'CartController@updateCartItem']);
	Route::post('/cart/copy/{type}',["as"=>'frontend.cart.copy',"uses"=>'CartController@cartCopy']);
	Route::delete('/cart/delete',["as"=>'frontend.cart.delete',"uses"=>'CartController@deleteCartItem']);
	
	Route::get('/cart/store-selection',["as"=>'get.frontend.store-selection',"uses"=>'PagesController@storeSelection']);
	Route::post('/cart/store-selection',["as"=>'frontend.store-selection',"uses"=>'CartController@storeSelection']);
	Route::post('/cart/get-shipping',["as"=>'frontend.shipping',"uses"=>'CartController@getShipping']);
	Route::post('/cart/apply-coupon',["as"=>'frontend.apply.coupon',"uses"=>'CartController@applyCoupon']);
	Route::post('/cart/{sid}/products',["as"=>'frontend.store-with-products',"uses"=>'CartController@storeSelectionWithProduct']);
	Route::post('/cart/hilal',["as"=>'frontend.send',"uses"=>'CartController@hilalSend']);

	Route::post('/cart/{sid}/store_product_details',["as"=>'frontend.store-products',"uses"=>'CartController@ProductsWithStores']);
	Route::post('/cart/{sid}/related_products/{product_id}',["as"=>'frontend.related.store-products',"uses"=>'CartController@RelatedProductsWithStores']);
	Route::post('/cart/{sid}/get_ratings',["as"=>'frontend.get-store-ratings',"uses"=>'CartController@getStoreRatings']);

	Route::post('/product-search',["as"=>'frontend.search',"uses"=>'CartController@ProductSearch']);
	Route::post('/contact-us',["as"=>'frontend.contact-us',"uses"=>'PagesController@postContactUs']);
});
Route::group(['namespace'=>'FrontEnd','middleware' => ['hasrole:customer']], function (){
	Route::get('/cart/{sid}/checkout',["as"=>'frontend.cart-checkout',"uses"=>'PagesController@cartCheckout']);
	Route::post('/cart/{sid}/checkout',["as"=>'frontend.cart-checkout-final',"uses"=>'CartController@checkoutFinal']);
	
	Route::get('/customer/dashboard',["as"=>'customer.dashboard',"uses"=>'PagesController@index']);
	Route::get('/my-orders/{id}',["as"=>'pages.order',"uses"=>'PagesController@getOrders']);
	Route::get('/settings/{id}',["as"=>'pages.settings',"uses"=>'PagesController@getSettings']);
	Route::get('/my-profile/{id}',["as"=>'pages.profile',"uses"=>'PagesController@getProfile']);
	Route::post('/shipping-details/{id}',["as"=>'front.user-shipping-details',"uses"=>'PagesController@postShipping']);
	Route::get('/invoice/{id}',["as"=>'front.invoice',"uses"=>'PagesController@getInvoice']);

	Route::post('/customer/cancel-order/{id}',["as"=>'front.cancel-order',"uses"=>'PagesController@postCancelOrder']);
	Route::post('/customer/rate_order/',["as"=>'front.rate-order',"uses"=>'PagesController@postRateOrder']);
	Route::post('/customer/get_rate/{rate_id}',["as"=>'front.get-rate-order',"uses"=>'PagesController@getRateOrder']);

});
Route::group(['namespace'=>'FrontEnd','middleware' => ['auth']], function (){
	Route::post('/my-profile/{id}/{area}/{type}',["as"=>'front.profile',"uses"=>'PagesController@postProfile']);
});

Route::post('/create-cookies',['as'=>'create.cookies','uses'=>'CommanController@createCookies']);
Route::post('/delete-cookies',['as'=>'delete.cookies','uses'=>'CommanController@deleteCookies']);

Route::post('/get-cities',['as'=>'get.cities','uses'=>'CommanController@getCities']);
Route::get('/dashboard',['as'=>'user.dashboard','uses'=>'UserAuthController@getDashboard']);
Route::get('/delete-modal',['as'=>'get.delete.modal','uses'=>'CommanController@getDeleteModal']);

Route::group(['middleware' => ['guest']], function() {
	Route::get('/login',['as'=>'login','uses'=>'UserAuthController@getLogin']);
	Route::get('/forgot-password',['as'=>'pages.forgot-password','uses'=>'UserAuthController@getForgotPassword']);
	Route::post('/login',['as'=>'post.login','uses'=>'UserAuthController@postLogin']);
});

Route::post('/subscribe',['as'=>'post.subscription','uses'=>'CommanController@postSubscribe']);

Route::group(['middleware' => ['guest']], function () {
	Route::get('/update-product-sold',['as'=>'product-sold','uses'=>'CommanController@ProductSold']);
	Route::post('/forgot-password',['as'=>'post.forgot','uses'=>'UserAuthController@postForgotPassword']);
});

Route::group(['prefix' => 'store','namespace'=>'Store','middleware' => ['auth']], function () {
	Route::post('/invoice/{id}',['as'=>'store.invoice','uses'=>'InvoiceController@getInvoice']);
});