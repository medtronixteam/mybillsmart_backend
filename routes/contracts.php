<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ContractController;


Route::group(['middleware'=>'auth:sanctum','prefix'=>'contract'])->group(function () {


    Route::get('contracts', [ContractController::class, 'list']);
    Route::get('client/contracts', [ContractController::class, 'clientContracts']);
    Route::get('client/list', [ContractController::class, 'clientList']);
    Route::post('contracts', [ContractController::class, 'store']);
});
