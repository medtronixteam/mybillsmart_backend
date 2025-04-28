

<?php

use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\OffersController;
use Illuminate\Support\Facades\Route;
Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'user'], function () {

    Route::post('invoices', [InvoiceController::class, 'store']);
    Route::get('invoices', [InvoiceController::class, 'index']);
    Route::post('invoice/offers', [OffersController::class, 'view']);
    Route::post('/offers', [OffersController::class, 'store']);


    //client management
    Route::post('/user', [ClientController::class, 'userCreate']);
    Route::get('/user', [ClientController::class, 'userList']);
    Route::post('user/edit/{id}', [ClientController::class, 'update']);
    Route::post('user/enable/{id}', [ClientController::class, 'enable']);
    Route::post('user/disable/{id}', [ClientController::class, 'disable']);
    Route::delete('user/delete/{id}', [ClientController::class, 'delete']);
});
