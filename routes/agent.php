<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OffersController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\ContractController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\ReferralController;


Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'agent'], function () {


    Route::post('/offers', [OffersController::class, 'store']);
    Route::get('offers', [OffersController::class, 'list']);
    Route::post('/send-offers-email', [OffersController::class, 'sendOffersEmail']);

    Route::post('/send/client/portal', [OffersController::class, 'sendClientPortal']);

    Route::post('invoice/offers', [OffersController::class, 'view']);

    //invoices

    Route::post('invoices', [InvoiceController::class, 'store']);
    Route::get('invoices', [InvoiceController::class, 'index']);
    Route::get('invoices/{id}', [InvoiceController::class, 'show']);


    Route::get('list/invoices', [InvoiceController::class, 'agentList']);

    //contracts
    Route::get('contracts', [ContractController::class, 'agentContractList']);
    Route::post('contracts', [ContractController::class, 'store']);

    Route::get('client/list', [ContractController::class, 'clientList']);
    Route::get('contracts/list', [ContractController::class, 'contractList']);
    Route::get('clients', [ProfileController::class, 'listClients']);
    Route::get('documents/{id}', [ProfileController::class, 'listDocuments']);


    Route::post('/user', [ClientController::class, 'userCreate']);
    Route::get('/user', [ClientController::class, 'userList']);

    Route::get('dashboard/stats', [ContractController::class, 'agentData']);

//DS
    Route::get('/referral-url', [ReferralController::class, 'getReferralUrl']);
});
