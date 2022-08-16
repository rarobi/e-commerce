<?php

Route::group(['module' => 'Payment', 'middleware' => ['api'], 'namespace' => 'App\Modules\Payment\Controllers'], function() {

    Route::resource('Payment', 'PaymentController');

});
