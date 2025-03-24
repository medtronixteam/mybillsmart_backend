<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProfileController;


Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'documents'], function () {


    Route::post('/documents', [ProfileController::class, 'store']);
    Route::get('/documents/{id}', [ProfileController::class, 'listDocuments']);
});
