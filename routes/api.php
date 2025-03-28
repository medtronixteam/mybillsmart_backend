<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\WhatsAppController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ContractController;
use App\Http\Controllers\Api\SupervisorController;




//authentication
Route::post('/login', [LoginController::class, 'login']);
Route::post('/provider', [LoginController::class, 'providerSignup']);
Route::post('/agent', [LoginController::class, 'agentSignup']);

Route::post('/list/products', [ProductController::class, 'providerProducts']);


Route::post('/forgot-password', [ProfileController::class, 'forgotPassword']);
Route::post('/verify-otp', [ProfileController::class, 'verifyOtp']);
Route::post('/resend-otp', [ProfileController::class, 'resendOtp']);
Route::post('/reset-password', [ProfileController::class, 'resetPassword']);

//auth
Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::post('/change-password', [ProfileController::class, 'changePassword']);
    Route::post('user/profile', [ProfileController::class, 'update']);

    Route::get('admin/data', [ProfileController::class, 'totalUsers']);
    Route::get('agent/data', [ContractController::class, 'agentData']);
    Route::get('supervisor/data', [SupervisorController::class, 'supervisorData']);
});


Route::post('whatsapp/pdf', [WhatsAppController::class, 'sendPDF']);
//s
require __DIR__ . '/supervisor.php';
require __DIR__ . '/group_admin.php';
require __DIR__ . '/agent.php';
require __DIR__ . '/client.php';
