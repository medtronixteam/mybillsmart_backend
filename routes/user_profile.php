<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProfileController;


Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'profile'], function () {


    Route::get('/agent/clients', [ProfileController::class, 'listClients']);
});
