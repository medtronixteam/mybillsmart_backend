<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;


Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'products'], function () {

    Route::get('/list/products', [ProductController::class, 'allProductsData']);

});
