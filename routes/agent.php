<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OffersController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\ContractController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\GoalsController;
use App\Http\Controllers\ReferralController;

//middlewares
use App\Http\Middleware\InvoiceMiddleware;


Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'agent'], function () {

    Route::middleware(InvoiceMiddleware::class)->group(function () {

    });

    Route::post('/offers', [OffersController::class, 'store']);
    Route::get('offers', [OffersController::class, 'list']);
    Route::post('/offer/selected', [OffersController::class, 'selectedOffer']);
    Route::post('/send-offers-email', [OffersController::class, 'sendOffersEmail']);
    Route::post('client/search', [OffersController::class, 'clientSearch']);
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

    Route::post('contracts/status', [ContractController::class, 'contractStatus']);

    Route::get('clients', [ProfileController::class, 'listClients']);
    Route::get('documents/{id}', [ProfileController::class, 'listDocuments']);


    Route::post('/user', [ClientController::class, 'userCreate']);
    Route::get('/user', [ClientController::class, 'userList']);
    Route::post('user/edit/{id}', [ClientController::class, 'update']);
    Route::post('user/enable/{id}', [ClientController::class, 'enable']);
    Route::post('user/disable/{id}', [ClientController::class, 'disable']);
    Route::delete('user/delete/{id}', [ClientController::class, 'delete']);
    Route::get('dashboard/stats', [ContractController::class, 'agentData']);
    Route::get('/user/detail/{id}', [ClientController::class, 'detail']);
//DS
    Route::get('/referral-url', [ReferralController::class, 'getReferralUrl']);
    Route::get('/referral/users', [ReferralController::class, 'refferedUsers']);
    Route::post('/commission/user', [ReferralController::class, 'commissionUser']);
    Route::get('goals', [GoalsController::class, 'agentGoals']);
});
