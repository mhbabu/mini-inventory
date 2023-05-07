<?php

Route::group(['module' => 'Company', 'middleware' => ['web','auth'], 'namespace' => 'App\Modules\Company\Controllers'], function() {

    Route::get('companies/{id}/delete','CompanyController@delete');
    Route::resource('companies', 'CompanyController');
});
