<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ContractController;


Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'client'], function () {


    Route::post('/documents', [ProfileController::class, 'store']);
    Route::get('contracts/list', [ContractController::class, 'clientContracts']);


});
