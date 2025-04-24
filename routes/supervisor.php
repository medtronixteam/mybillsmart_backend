<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SupervisorController;
use App\Http\Controllers\Api\ProductController;

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'supervisor'], function () {
   //products
   Route::apiResource('products', ProductController::class);
   Route::get('list/products', [ProductController::class, 'allProductsData']);


Route::post('/user', [SupervisorController::class, 'userCreate']);
Route::get('/user', [SupervisorController::class, 'userList']);
Route::post('user/edit/{id}', [SupervisorController::class, 'update']);
Route::post('user/enable/{id}', [SupervisorController::class, 'enable']);
Route::post('user/disable/{id}', [SupervisorController::class, 'disable']);
Route::delete('user/delete/{id}', [SupervisorController::class, 'delete']);
Route::get('/user/detail/{id}', [SupervisorController::class, 'detail']);
//offers
Route::get('dashboard/stats', [SupervisorController::class, 'supervisorData']);
});
