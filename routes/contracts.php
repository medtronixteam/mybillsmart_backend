<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ContractController;


Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'contract'], function () {



});
