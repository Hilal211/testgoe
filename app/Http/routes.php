<?php
Route::group(['middleware' => ['guest']], function() {
	
	Route::get('/command/{command}',function($command){
		$exitCode = Artisan::call($command);
		//$exitCode = Artisan::call('migrate');
		$output = Artisan::output();
		dd($output);
	});
});

Route::post('/get-subs',['as'=>'get.subcategory','uses'=>'CommanController@getSubCategories']);
Route::get('/logout',['as'=>'pages.logout','uses'=>'UserAuthController@logout']);

Route::post('/product-request-details',['as'=>'admin.product-request-details','uses'=>'CommanController@PostProductRequestDetails']);

Route::group(['middleware' => ['auth']], function (){
	Route::post('/refund',['as'=>'post.refund','uses'=>'CommanController@postRefundOrder']);
});