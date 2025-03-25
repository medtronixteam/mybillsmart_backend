<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InvoiceController;


Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'invoice'], function () {


});
