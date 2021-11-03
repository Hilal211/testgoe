<?php
Route::group(['prefix' => 'admin','namespace'=>'Admin','middleware' => ['hasrole:admin']], function () {
    Route::get('/dashboard',['as'=>'admin.dashboard','uses'=>'PagesController@index']);
    Route::get('/settings','PagesController@getSettings');

    /*----------  Customers Routes  ----------*/
    Route::get('/shoppers',['as'=>'admin.shoppers','uses'=>'PagesController@getShoppers']);
    Route::get('/customer-data',['as'=>'post.customer-list','uses'=>'CustomerController@postCustomerList']);
    Route::get('/shopper/{id}',['as'=>"admin.customer.details",'uses'=>'CustomerController@getShopper']);
    Route::delete('/shopper/{id}',['as'=>'admin.shopper.delete','uses'=>'CustomerController@deleteShopper']);

    /*====================================
    =            Store Routes            =
    ====================================*/
    Route::get('/stores',['as'=>'admin.store.list','uses'=>'StoreController@getStore']);
    Route::get('/store-data',['as'=>'post.store-list','uses'=>'StoreController@postStoreList']);
    Route::get('/store-profile/{id}',['as'=>'admin.store.details','uses'=>'StoreController@GetStoreProfile']);
    Route::delete('/store/{id}',['as'=>'admin.store.delete','uses'=>'StoreController@deleteSingleStore']);
    Route::post('/approve-store/{id}',['as'=>'admin.store.approve','uses'=>'StoreController@ApproveStore']);
    Route::post('/store-bank/{id}',['as'=>'admin.store.bank','uses'=>'StoreController@getBank']);
    Route::post('/approve-store-bank',['as'=>'approve.bank','uses'=>'StoreController@ApproveBank']);
    /*=====  End of Store Routes  ======*/
    

    /*=========================================
    =            Categories routes            =
    =========================================*/
    Route::delete('/categories/{id}',['as'=>'category.delete','uses'=>'CategoryController@deletecategory']);
    Route::post('/save/subcategories',['as'=>'category.save','uses'=>'CategoryController@postCategory']);
    Route::post('/save/categories',['as'=>'main.category.save','uses'=>'CategoryController@postMainCategory']);
    Route::post('/category/{id}',['as'=>'get.category','uses'=>'CategoryController@postGetCategory']);
    Route::post('/update/category-order',['as'=>'change.category.order','uses'=>'CategoryController@postCategoryOrder']);
    Route::post('/delete-category-image/{id}/{type}',['as'=>'remove.image','uses'=>'CategoryController@postRemoveImage']);
    /*=====  End of Categories routes  ======*/
    


    /*=============================================
    =            Product routes            =
    =============================================*/
    Route::post('/products/{id}',['as'=>'get.product','uses'=>'ProductController@postGetSingleProduct']);
    Route::delete('/products/{id}',['as'=>'delete.product','uses'=>'ProductController@deleteProduct']);
    Route::post('/product',['as'=>'save.product','uses'=>'ProductController@postSave']);
    Route::get('/product-price-logs',['as'=>'admin.price.logs','uses'=>'ProductController@getPriceLogs']);
    Route::get('/price-log-data',['as'=>'admin.price-log-data','uses'=>'ProductController@getPriceLogsData']);
    /*=====  End of Categories Routes  ======*/
    
    /*----------  Orders Routes  ----------*/
    Route::get('/orders',['as'=>'admin.orders','uses'=>'OrderController@getOrders']);
    Route::get('/order-data',['as'=>'post.order-list','uses'=>'OrderController@postOrders']);

    /*----------  Categories  ----------*/
    Route::get('/cats-subcats',['as'=>'admin.cats-subcats','uses'=>'CategoryController@getCatsSubCats']);
    Route::post('/get-subcats-view/{cat_id}',['as'=>'get.subcategory.view','uses'=>'CategoryController@getSubCat']);

    /*----------  Settings  ----------*/
    Route::get('/settings',['as'=>'admin.settings','uses'=>'SettingsController@index']);
    Route::post('/settings/{slug}',['as'=>'save.settings','uses'=>'SettingsController@postArea']);
    Route::post('/settings',['as'=>'save.shipping','uses'=>'SettingsController@postSlugArea']);
    Route::post('/save/storetype',['as'=>'storetype.save','uses'=>'SettingsController@postStoretype']);
    Route::post('/save/unit',['as'=>'unit.save','uses'=>'SettingsController@postUnit']);
    Route::post('/_list/{id}',['as'=>'get._list.item','uses'=>'SettingsController@getListItem']);
    Route::delete('/list/{id}',['as'=>'delete._list.item','uses'=>'SettingsController@deleteListItem']);
    

    /*----------  Product Request  ----------*/
    Route::get('/product-request',['as'=>'admin.product-request','uses'=>'ProductController@ProductRequest']);
    Route::get('/requested-product-data',['as'=>'admin.product-data','uses'=>'ProductController@PostProductRequest']);
    Route::post('/product-request-action',['as'=>'admin.product-request-action','uses'=>'ProductController@PostProductRequestStatus']);
    
    
    Route::get('/map',['as'=>'admin.map','uses'=>'PagesController@getMap']);

    /*----------  Pickups Pages  ----------*/
    Route::get('/pickups',['as'=>'admin.pickups','uses'=>'OrderController@getPickups']);
    Route::post('/pickups-data',['as'=>'admin.order-data','uses'=>'OrderController@postPickups']);
    Route::post('/change-status',['as'=>'admin.order-status-change','uses'=>'OrderController@changeStatus']);
    /*----------  limited access notification page  ----------*/
    Route::get('/requested-users',['as'=>'admin.requested.users','uses'=>'PagesController@getRequestedUser']);
    Route::get('/requested-users-data',['as'=>'admin.requested-data','uses'=>'PagesController@postRequestedUser']);

    /*----------  tax management pages  ----------*/    
    Route::get('/tax-management',['as'=>'admin.get.tax','uses'=>'TaxController@index']);
    Route::post('/tax-management',['as'=>'admin.save.tax','uses'=>'TaxController@postTax']);
    
    /*----------  announcementt pages  ----------*/ 
    Route::get('/announcement',['as'=>'admin.get.announcement','uses'=>'AnnouncementController@index']);
    Route::get('/announcement-data',['as'=>'get.announcement','uses'=>'AnnouncementController@getAnnouncements']);
    Route::post('/save-announcement',['as'=>'save.announcement','uses'=>'AnnouncementController@saveAnnouncements']);
    Route::get('/announcement/{id}/edit',['as'=>'get.announcement','uses'=>'AnnouncementController@edit']);
        /*----------  statOrder pages  ----------*/ 
        Route::get('/statorder',['as'=>'admin.get.statorder','uses'=>'OrderController@index']);

    /*----------  admin profile route  ----------*/
    Route::get('/profile',['as'=>'pages.admin_profile','uses'=>'PagesController@getProfile']);
    Route::post('/profile',['as'=>'save.profile','uses'=>'PagesController@postProfile']);
    
    Route::get('/ratings',['as'=>'admin.ratings','uses'=>'PagesController@getratings']);
    Route::get('/admin-rating-data',['as'=>'admin.post.ratings','uses'=>'PagesController@postAllRatings']);

    Route::get('/newsletter',['as'=>'admin.newsletter','uses'=>'PagesController@getNewsletter']);
    Route::get('/newsletter-subscriptions',['as'=>'admin.newsletter.subscriptions','uses'=>'PagesController@getNewsletterSubscriptions']);
    Route::post('/newsletter',['as'=>'send.newsletter','uses'=>'PagesController@postNewsletter']);
    Route::post('/newsletter-photo',['as'=>'save.newsletter.photo','uses'=>'PagesController@postNewsletterImage']);
    Route::get('/newsletter-subscriptions-data',['as'=>'get.subscriptions-list','uses'=>'PagesController@postSubscriptions']);
    
    /*----------  admin coupon route  ----------*/
    Route::resource('coupons','CouponContoller');
    Route::get('/coupon-data',['as'=>'post.coupon-list','uses'=>'CouponContoller@getCoupons']);

    /*----------  admin coupon route  ----------*/
    Route::resource('virtual-stores','VirtualStoreController');
    Route::get('/virtual-store-data',['as'=>'post.virtual-store-list','uses'=>'VirtualStoreController@getVirtualStores']);
    Route::post('/save-virtual-store-bank',['as'=>'post.save.virtual.bank','uses'=>'VirtualStoreController@postBankDetails']);
    Route::get('/get-virtual-store-bank',['as'=>'get.virtual.bank','uses'=>'VirtualStoreController@getBankDetails']);
    Route::get('/login-as-virtual-store/{id}',['as'=>'admin.virtual.store.login','uses'=>'VirtualStoreController@loginAsVirtualStore']);
});
Route::group(['prefix' => 'admin','namespace'=>'FrontEnd','middleware' => ['hasrole:admin']], function () {
        /*----------  announcementt pages  ----------*/ 
        Route::get('/statistics',['as'=>'admin.get.statistics','uses'=>'StatisticsController@index']);
        Route::get('/statistics-data',['as'=>'get.statistics','uses'=>'StatisticsController@getStatistics']);

    });
