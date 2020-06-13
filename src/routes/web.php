<?php

Route::group(['namespace' => 'KomjIT\SimplePay\Http\Controllers', 'prefix' => 'komjit/simplepay', 'middleware' => ['web']], function () {

    Route::get('index', 'PaymentController@index');

});
