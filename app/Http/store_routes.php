<?php
if(version_compare(PHP_VERSION, '7.2.0', '>=')) {
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}
Route::group(['prefix' => 'store','namespace'=>'Store','middleware' => ['hasrole:store_owner']], function () {
		Route::get('/dashboard',['as'=>'store_owner.dashboard','uses'=>'PagesController@index']);
		
		
		Route::post('/order-data',['as'=>'store.order-data','uses'=>'OrderController@postOrders']);
		Route::post('/order-total',['as'=>'store.order-total','uses'=>'OrderController@postOrdersTotal']);
		Route::post('/change-order-status',['as'=>'store.order-status-change','uses'=>'OrderController@postOrdersStatus']);
		
		/*=============================================
		=            Store Products routes            =
		=============================================*/
		
		/*Route::get('/{id}/products',['as'=>'store_owner.products','uses'=>'ProductController@getStoreProducts']);*/
		Route::get('/{id}/products',['as'=>'store_owner.products','uses'=>'ProductController@getStoreProducts']);
		Route::post('/{storeid}/product/{pid}',['as'=>'get.product','uses'=>'ProductController@getStoreSingleProduct']);
		Route::post('/save-product',['as'=>'save.product','uses'=>'ProductController@PostSingleProduct']);
		Route::post('/{store_id}/get-subcats-view/{cat_id}',['as'=>'get.store.subcategory.view','uses'=>'ProductController@PostSubCatView']);

		Route::get('/request-product/{storeid}',['as'=>'store.new_product','uses'=>'ProductController@getRequestProduct']);
		Route::get('/request-product-data',['as'=>'store-requested-product-data','uses'=>'ProductController@postRequestProduct']);
		Route::post('/request-new-product',['as'=>'store.product-request','uses'=>'ProductController@PostProductRequest']);
		Route::post('/requested-product/{id}',['as'=>'single.product-request','uses'=>'ProductController@getSingleRequestedProduct']);


		Route::get('/settings/{storeid}',['as'=>'store.settings','uses'=>'SettingsController@index']);
		Route::post('/settings/{storeid}',['as'=>'save.store.settings','uses'=>'SettingsController@postSettings']);
		/*=====  End of Store Products routes  ======*/

		Route::get('/ratings',['as'=>'store.rating','uses'=>'PagesController@getratings']);
		Route::get('/rating-data',['as'=>'post.ratings','uses'=>'PagesController@postAllRatings']);

		Route::get('/login-as-admin',['as'=>'store.admin.login','uses'=>'PagesController@loginAsAdmin']);
		Route::post('/{store_id}/storehilalo',['as'=>'store.hilalo','uses'=>'ProductController@hilalStore']);
});
Route::group(['prefix' => 'store','namespace'=>'Store','middleware' => ['auth']], function () {
		/*============================================
		=            Store Details routes            =
		============================================*/
		Route::get('/store-profile/{id}',['as'=>'store.profile','uses'=>'ProfileController@getStoreDetails']);
		Route::post('/store-profile/{step}/{id}',['as'=>'save.store.profile','uses'=>'ProfileController@PostStoreDetails']);
		
		/*=====  End of Store Details routes  ======*/

		/*----------  Orders Routes  ----------*/
		Route::get('/orders',['as'=>'store.orders','uses'=>'OrderController@getAllOrders']);
    	Route::get('/order-data',['as'=>'post.order-list','uses'=>'OrderController@postAllOrders']);

    	Route::delete('/request-product/{id}',['as'=>'single.product-request','uses'=>'ProductController@DeleteSingleRequestedProduct']);
});