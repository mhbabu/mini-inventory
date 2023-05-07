<?php

Route::group(['module' => 'Stock', 'middleware' => ['web','auth'], 'namespace' => 'App\Modules\Stock\Controllers'], function() {

    Route::resource('stocks', 'StockController');

});
