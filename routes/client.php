<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\SupervisorController;


Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'client'], function () {


    Route::post('/documents', [ProfileController::class, 'store']);


    Route::post('/user', [SupervisorController::class, 'userCreate']);
    Route::get('/user', [SupervisorController::class, 'userList']);

});
