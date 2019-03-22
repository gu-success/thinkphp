<?php

Route::get('api/:version/banner/:id', 'api/:version.Banner/getBanner')->pattern(['id' => '[\w.\-]+']);

Route::group('api/:version/theme/',function (){
    Route::get('', 'api/:version.Theme/getSimpleList');
    Route::get(':id', 'api/:version.Theme/getComplexOne')->pattern(['id' => '[\w.\-]+']);
});

Route::group('api/:version/product/',function (){
    Route::get('recent', 'api/:version.Product/getRecent');
    Route::get('by_category', 'api/:version.Product/getCategoryProduct');
    Route::get(':id', 'api/:version.Product/getOne')->pattern(['id' => '[\w.\-]+']);
});

Route::get('api/:version/category/all', 'api/:version.Category/getAllCategories');

Route::post('api/:version/token/user', 'api/:version.Token/getToken');

Route::post('api/:version/address', 'api/:version.Address/createOrUpdateAddress');

Route::post('api/:version/order', 'api/:version.Order/placeOrder');
Route::get('api/:version/order/by_user', 'api/:version.Order/getSummaryByUser');
Route::get('api/:version/order/:id', 'api/:version.Order/getDetail')->pattern(['id' => '[\w.\-]+']);

Route::post('api/:version/pay/pre_order', 'api/:version.Pay/getPreOrder');

Route::post('api/:version/pay/notify', 'api/:version.Pay/receiveNotify');
