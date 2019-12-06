<?php
/**
 * 后台路由，url前缀 /api/admin.
 */
Route::group([], function () {
    // Route::post('auth/login', 'AuthController@postLogin');
    Route::post('auth/login', 'AuthController@postJwtLogin');
    Route::get('auth/login', 'AuthController@getLogin');
    Route::group(['middleware' => 'refresh.jwt_token'], function () {
        Route::get('my/profile', 'MyController@getProfile');
        Route::put('my/profile', 'MyController@putProfile');
        Route::get('my/logout', 'MyController@getLogout');

        Route::resources([
            'users' => 'UsersController',
        ]);
    });
    // 原web session
    // Route::group(['middleware' => 'auth:admin'], function () {
    // });
    Route::group(['prefix' => 'supplier'], function () {
        \Modules\ClientAggregationUpload\Factory::routesHook();
    });
});
