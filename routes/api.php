<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProfileController;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

//authentication
Route::post('/login', [LoginController::class, 'login']);
Route::post('/provider', [LoginController::class, 'providerSignup']);
Route::post('/agent', [LoginController::class, 'agentSignup']);
Route::get('/list/products', [ProductController::class, 'allProductsData']);
Route::get('/list/products/{userId}', [ProductController::class, 'providerProducts']);

Route::post('/change-password', [ProfileController::class, 'changePassword']);
Route::post('/forgot-password', [ProfileController::class, 'forgotPassword']);
Route::post('/verify-otp', [ProfileController::class, 'verifyOtp']);
Route::post('/resend-otp', [ProfileController::class, 'resendOtp']);
Route::post('/reset-password', [ProfileController::class, 'resetPassword']);


use App\Http\Controllers\WhatsAppController;

Route::post('whatsapp/pdf', [WhatsAppController::class, 'sendPDF']);

require __DIR__ . '/supervisor.php';
require __DIR__ . '/group_admin.php';
require __DIR__ . '/agent.php';
require __DIR__ . '/client.php';
