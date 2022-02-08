<?php
use App\Http\Middleware\VerifyCsrfToken;

Route::group(['namespace' => 'Botble\Eurobank\Http\Controllers', 'middleware' => ['web', 'core']], function () {

    Route::any('eurobank/payment/callback', [
        'as'   => 'eurobank.payment.callback',
        'uses' => 'EurobankController@paymentCallback',
    ])->withoutMiddleware(VerifyCsrfToken::class);


    Route::get('eurobank/payment/redirect', [
        'as'   => 'eurobank.payment.redirect',
        'uses' => 'EurobankController@paymentredirect',
    ]);//->withoutMiddleware(VerifyCsrfToken::class);
});
