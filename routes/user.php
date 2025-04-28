

<?php

use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\OffersController;
use Illuminate\Support\Facades\Route;
Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'user'], function () {


    Route::post('invoices', [InvoiceController::class, 'store']);
    Route::get('invoices', [InvoiceController::class, 'index']);

    Route::post('invoice/offers', [OffersController::class, 'view']);
    Route::post('/offers', [OffersController::class, 'store']);


});
