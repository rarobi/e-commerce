<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin','module' => 'Order', 'middleware' => ['web','auth','admin'], 'namespace' => 'App\Modules\Order\Controllers\Backend'], function() {

  /*
   * order Management Routes
   */
   Route::get('orders/{id}/delete','OrderController@delete');
   Route::resource('orders', 'OrderController')->names([
     'index'     => 'admin.orders.index',
     'edit'      => 'admin.orders.edit',
     'update'    => 'admin.orders.update',
     'show'      => 'admin.orders.show'
    ])->only(['index', 'edit','update','show']);

});
