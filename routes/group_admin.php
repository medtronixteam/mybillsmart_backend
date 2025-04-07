<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\OffersController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ContractController;
Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'group'], function () {



   Route::post('/user', [LoginController::class, 'register']);

   Route::post('user/profile', [ProfileController::class, 'update']);
   Route::get('/users/list', [ProfileController::class, 'list']);
   Route::get('/user/detail/{id}', [ProfileController::class, 'detail']);
   Route::post('/user/enable/{id}', [ProfileController::class, 'enable']);
   Route::post('/user/disable/{id}', [ProfileController::class, 'disable']);
   Route::delete('/user/delete/{id}', [ProfileController::class, 'delete']);
//offers
Route::post('invoice/offers', [OffersController::class, 'viewOffers']);
//invoices
Route::get('invoices', [InvoiceController::class, 'agentInvoices']);
Route::post('invoices', [InvoiceController::class, 'storeGroup']);

Route::get('contracts/list', [ContractController::class, 'contractslist']);
Route::get('dashboard/stats', [ProfileController::class, 'groupStats']);
});
