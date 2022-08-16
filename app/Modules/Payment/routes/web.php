<?php

Route::group(['module' => 'Payment', 'middleware' => ['web'], 'namespace' => 'App\Modules\Payment\Controllers'], function() {

    Route::resource('Payment', 'Frontend\PaymentController');

    Route::post('payment-request', 'Frontend\PaymentController@paymentRequest')->name('payment.payment-request');

    Route::group(['prefix' => 'sslwireless'], function () {

        Route::post('payment/success/{refId?}', 'Frontend\PaymentController@sslResponseSuccess');
        Route::post('payment/fail/{refId?}', 'Frontend\PaymentController@sslResponseFail');
        Route::post('payment/cancel/{refId?}', 'Frontend\PaymentController@sslResponseCancel');
        Route::post('payment/ipn/{refId?}', 'Frontend\PaymentController@sslResponseIpn');
    });
});
