<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OffersController;


Route::group(['middleware'=>'auth:sanctum','prefix'=>'offers'])->group(function () {


    Route::post('/offers', [OffersController::class, 'store']);
    Route::get('offers', [OffersController::class, 'list']);
    Route::post('/send-offers-email', [OffersController::class, 'sendOffersEmail']);
});
