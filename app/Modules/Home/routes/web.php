<?php

Route::group(['module' => 'Home', 'middleware' => ['web'], 'namespace' => 'App\Modules\Home\Controllers\Frontend'], function() {

    Route::get('/', 'HomeController@index');
    Route::get('/faqs', 'HomeController@faqs');
    Route::get('/wish-list', 'HomeController@wishList');
});
