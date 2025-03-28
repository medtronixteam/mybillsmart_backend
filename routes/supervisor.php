<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SupervisorController;
use App\Http\Controllers\Api\ProductController;

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'supervisor'], function () {
   //products
   Route::apiResource('products', ProductController::class);
   Route::get('list/products', [ProductController::class, 'allProductsData']);


Route::post('/user', [SupervisorController::class, 'userCreate']);
Route::get('/user', [SupervisorController::class, 'userList']);

});
