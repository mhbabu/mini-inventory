<?php

use Illuminate\Support\Facades\Route;

Route::group(['module' => 'Dashboard', 'middleware' => ['web','auth'], 'namespace' => 'App\Modules\Dashboard\Controllers'], function() {

    Route::resource('dashboard', 'DashboardController');

});
