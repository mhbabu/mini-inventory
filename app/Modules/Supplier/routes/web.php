<?php

use Illuminate\Support\Facades\Route;

Route::group(['module' => 'Supplier', 'middleware' => ['web','auth'], 'namespace' => 'App\Modules\Supplier\Controllers'], function() {
    Route::get('suppliers/{id}/delete','SupplierController@delete');
    Route::resource('suppliers', 'SupplierController');
});
