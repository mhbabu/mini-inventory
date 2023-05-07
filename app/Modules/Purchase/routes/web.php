<?php

Route::group(['module' => 'Purchase', 'middleware' => ['web','auth'], 'namespace' => 'App\Modules\Purchase\Controllers'], function() {

    /*
    * Premium Model Test Routes
    */
    Route::post('purchase/product/auto-suggest', 'PurchaseController@productAutoSuggest');
    Route::post('purchase/product/add-cart', 'PurchaseController@productAddCart')->name('purchase.product.add.cart');
    Route::post('purchase/product/cart/edit-delete', 'PurchaseController@productCartEditDelete')->name('purchase.product.cart.editDelete');

    /*
    * Purchase Products Routes
    */
    Route::resource('purchases', 'PurchaseController');

    /*
    * Company wise Supplier Route
    */
    Route::get('company-wise-suppliers','PurchaseController@getCompanySupplier');

});
