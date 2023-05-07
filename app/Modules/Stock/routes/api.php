<?php

Route::group(['module' => 'Stock', 'middleware' => ['api'], 'namespace' => 'App\Modules\Stock\Controllers'], function() {

    Route::resource('Stock', 'StockController');

});
