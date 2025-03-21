<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;


Route::group(['prefix'=>'products'])->group(function () {

    Route::get('/list/products', [ProductController::class, 'allProductsData']);

});
