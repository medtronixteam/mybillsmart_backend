<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ContractController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\ClientController;

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'client'], function () {


    Route::post('/documents', [ProfileController::class, 'store']);
    Route::get('contracts/list', [ContractController::class, 'clientContracts']);
    Route::get('dashboard/stats', [ClientController::class, 'clientData']);

    //invoices
    Route::post('invoices', [InvoiceController::class, 'storClient']);
    Route::get('invoices', [InvoiceController::class, 'clientInvoices_list']);
        Route::put('invoices', [InvoiceController::class, 'updateInvoice']);
});
