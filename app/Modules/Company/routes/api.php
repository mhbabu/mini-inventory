<?php

Route::group(['module' => 'Company', 'middleware' => ['api'], 'namespace' => 'App\Modules\Company\Controllers'], function() {

    Route::resource('Company', 'CompanyController');

});
