<?php

Route::group(['prefix' => 'admin','module' => 'Invoice', 'middleware' => ['web','auth','admin'], 'namespace' => 'App\Modules\Invoice\Controllers\Backend'], function() {

  /*
   * newsletters Management Routes
   */
   Route::get('invoices/{id}/delete','InvoiceController@delete');
   Route::resource('invoices', 'InvoiceController', ['names' => [
       'index'     => 'admin.invoices.index',
       'create'    => 'admin.invoices.create',
       'store'     => 'admin.invoices.store',
       'edit'      => 'admin.invoices.edit',
       'update'    => 'admin.invoices.update'
   ]]);

});
