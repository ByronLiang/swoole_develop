<?php
/**
 * API路由，url前缀 /api.
 */
// ['middleware' => 'refresh.jwt_token'] => auth:api jwt 授权中间件
Route::group(['middleware' => 'auth:api'], function () {
    Route::resource('chats', 'ChatController');
    Route::resource('chat_records', 'ChatRecordsController');
    Route::get('pay_order', 'ChatController@finishedPay');
});

Route::group(['prefix' => 'supplier'], function () {
    \Modules\ClientAggregationUpload\Factory::routesHook();
});

Route::get('home', 'HomeController@index');

Route::group(['prefix' => 'auth'], function () {
    Route::post('register', 'AuthController@postRegister');
    Route::post('login', 'AuthController@postLogin');
    Route::get('oauth/{provider}', 'AuthController@getOauth');
    Route::post('jwt_login', 'AuthController@postJwtLogin');
});

Route::group([
    'prefix' => 'my',
    'middleware' => ['auth:api'],
], function () {
    Route::get('profile', 'ProfilesController@getProfile');
});

Route::group(['prefix' => 'supplier'], function () {
    Route::get('swoole', 'SupplierController@getSwooleObject');
    Route::post('captcha', 'SupplierController@postCaptcha');
});

// 微信公众号资源请求地址
\Modules\Wechat\Entities\Wechat::officialAccountHook();

// 微信公众号二维码
\Modules\Wechat\Entities\Wechat::officialAccountQrCode();
