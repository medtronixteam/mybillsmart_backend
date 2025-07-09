<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\OffersController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\ContractController;
use App\Http\Controllers\Api\GoalsController;
use App\Http\Controllers\Api\SupervisorController;
use App\Http\Controllers\Api\PlanContoller;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PaymentIntentController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\ZapierHookController;
use App\Http\Middleware\InvoiceMiddleware;

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'group'], function () {



    Route::post('/user', [LoginController::class, 'register']);

    Route::post('user/profile', [ProfileController::class, 'update']);
    Route::get('/users/list', [ProfileController::class, 'groupUserList']);
    Route::get('/user/detail/{id}', [ProfileController::class, 'detail']);
    Route::post('user/edit/{id}', [SupervisorController::class, 'update']);

    Route::post('/user/enable/{id}', [ProfileController::class, 'enable']);
    Route::post('/user/disable/{id}', [ProfileController::class, 'disable']);
    Route::delete('/user/delete/{id}', [ProfileController::class, 'delete']);
    //offers
    Route::post('client/search', [OffersController::class, 'clientSearch']);
    Route::post('invoice/offers', [OffersController::class, 'viewOffers']);
    Route::post('/offers', [OffersController::class, 'store']);
    Route::post('/offer/selected', [OffersController::class, 'selectedOffer']);
    Route::post('/send/client/portal', [OffersController::class, 'sendClientPortal']);
    Route::post('/send-offers-email', [OffersController::class, 'sendOffersEmail']);
    //invoices
    Route::get('invoices', [InvoiceController::class, 'groupInvoices']);
    Route::post('invoices', [InvoiceController::class, 'storeGroup']);
    Route::put('invoices', [InvoiceController::class, 'updateInvoice']);
    //->middleware(InvoiceMiddleware::class)
    Route::get('invoices/{id}', [InvoiceController::class, 'show']);

    Route::get('contracts/list', [ContractController::class, 'groupContractsList']);
    Route::post('contracts', [ContractController::class, 'store']);
    Route::get('dashboard/stats', [ProfileController::class, 'groupStats']);


    Route::post('/referral/points', [ReferralController::class, 'updateReferalPoints']);
    Route::get('/referral/points', [ReferralController::class, 'ReferalPoints']);

    Route::post('goals', [GoalsController::class, 'store']);
    Route::get('goals', [GoalsController::class, 'groupList']);
    Route::put('goals/{goal}', [GoalsController::class, 'update']);
    Route::patch('goals/{goal}/status', [GoalsController::class, 'changeStatus']);
    Route::delete('goals/{goal}', [GoalsController::class, 'delete']);
    Route::get('mark-as-read/goal/{id}', [GoalsController::class, 'markAsRead']);
    Route::get('plans', [PlanContoller::class, 'index']);


    Route::apiResource('products', ProductController::class);
    Route::get('list/products', [ProductController::class, 'allProductsData']);
    //session history of agents/clients
    Route::post('session/history', [LoginController::class, 'sessionHistoryOther']);
    //payments
    Route::get('/order/history', [PaymentIntentController::class, 'orderHistory']);
    Route::get('/subscription/history', [PaymentIntentController::class, 'subscriptionHistory']);

    //comapny

    Route::get('company/details', [CompanyController::class, 'companyDetails']);
    Route::post('company/details', [CompanyController::class, 'updateCompanyDetails']);
    Route::get('client/list', [ContractController::class, 'clientList']);
    //agreements
    Route::post('/agreements', [ProfileController::class, 'agreementStore']);
    Route::get('/agreements', [ProfileController::class, 'agreementList']);
    Route::get('/agreement/view/{id}', [ProfileController::class, 'agreementView']);
    Route::post('agreement/edit/{id}', [ProfileController::class, 'agreementUpdate']);
    Route::delete('/agreement/delete/{id}', [ProfileController::class, 'agreementDelete']);



        Route::get('hooks', [ZapierHookController::class, 'index']);
        Route::post('hooks', [ZapierHookController::class, 'store']);
        Route::get('hooks/{id}', [ZapierHookController::class, 'show']);
        Route::put('hooks/{id}', [ZapierHookController::class, 'update']);
        Route::delete('hooks/{id}', [ZapierHookController::class, 'destroy']);
        Route::post('hook/test', [ZapierHookController::class, 'testHook']);

});
  Route::get('inv', [InvoiceController::class, 'inv']);
