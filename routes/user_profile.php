<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProfileController;


Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'profile'], function () {

    Route::post('/change-password', [ProfileController::class, 'changePassword']);
    Route::post('/forgot-password', [ProfileController::class, 'forgotPassword']);
    Route::post('/verify-otp', [ProfileController::class, 'verifyOtp']);
    Route::post('/resend-otp', [ProfileController::class, 'resendOtp']);
    Route::post('/reset-password', [ProfileController::class, 'resetPassword']);
    Route::post('user/profile', [ProfileController::class, 'update']);
    Route::get('/users/list', [ProfileController::class, 'list']);
    Route::get('/user/detail/{id}', [ProfileController::class, 'detail']);
    Route::post('/user/enable/{id}', [ProfileController::class, 'enable']);
    Route::post('/user/disable/{id}', [ProfileController::class, 'disable']);
    Route::delete('/user/delete/{id}', [ProfileController::class, 'delete']);
    Route::get('/agent/clients', [ProfileController::class, 'listClients']);
});
