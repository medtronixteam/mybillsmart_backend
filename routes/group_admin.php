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
Route::post('invoice/offers', [OffersController::class, 'viewOffers']);
Route::post('/offers', [OffersController::class, 'store']);
//invoices
Route::get('invoices', [InvoiceController::class, 'groupInvoices']);
Route::post('invoices', [InvoiceController::class, 'storeGroup']);
Route::get('invoices/{id}', [InvoiceController::class, 'show']);

Route::get('contracts/list', [ContractController::class, 'groupContractsList']);
Route::get('dashboard/stats', [ProfileController::class, 'groupStats']);


Route::post('/referral/points', [ReferralController::class, 'updateReferalPoints']);
Route::get('/referral/points', [ReferralController::class, 'ReferalPoints']);

Route::post('goals', [GoalsController::class, 'store']);
Route::get('goals', [GoalsController::class, 'groupList']);
Route::put('goals/{goal}', [GoalsController::class, 'update']);
Route::patch('goals/{goal}/status', [GoalsController::class, 'changeStatus']);
Route::delete('goals/{goal}', [GoalsController::class, 'delete']);

Route::get('plans', [PlanContoller::class, 'index']);


Route::apiResource('products', ProductController::class);
});
