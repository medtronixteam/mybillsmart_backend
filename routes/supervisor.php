<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SupervisorController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\GoalsController;

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'supervisor'], function () {
   //products
   Route::apiResource('products', ProductController::class);
   Route::get('list/products', [ProductController::class, 'allProductsData']);

   Route::post('goals', [GoalsController::class, 'store']);
    Route::get('goals', [GoalsController::class, 'groupList']);
    Route::put('goals/{goal}', [GoalsController::class, 'update']);
    Route::patch('goals/{goal}/status', [GoalsController::class, 'changeStatus']);
    Route::delete('goals/{goal}', [GoalsController::class, 'delete']);
    Route::get('mark-as-read/goal/{id}', [GoalsController::class, 'markAsRead']);

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
