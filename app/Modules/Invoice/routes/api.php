<?php

Route::group(['module' => 'Invoice', 'middleware' => ['api'], 'namespace' => 'App\Modules\Invoice\Controllers'], function() {

    Route::resource('Invoice', 'InvoiceController');

});
