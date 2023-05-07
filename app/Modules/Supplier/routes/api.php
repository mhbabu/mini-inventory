<?php

Route::group(['module' => 'Supplier', 'middleware' => ['api'], 'namespace' => 'App\Modules\Supplier\Controllers'], function() {

    Route::resource('Supplier', 'SupplierController');

});
