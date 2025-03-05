<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

Route::middleware('api')->group(function () {
    // Your API endpoints here
    Route::get('/test', function () {
        return response()->json(['message' => 'API is working!']);
    });

    // Example resource route
    Route::apiResource('posts', \App\Http\Controllers\PostController::class);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//authentication
Route::post('/register', [LoginController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/provider', [LoginController::class, 'providerSignup']);
Route::post('/agent', [LoginController::class, 'agentSignup']);



Route::get('/list/products', [ProductController::class, 'allProductsData']);
Route::middleware(['auth:sanctum'])->group(function () {

    //products
    Route::apiResource('products', ProductController::class);

    //invoices
    Route::post('invoices', [InvoiceController::class, 'store']);
    Route::get('invoices', [InvoiceController::class, 'index']);
    Route::get('invoices/{id}', [InvoiceController::class, 'show']);

    Route::post('/change-password', [ProfileController::class, 'changePassword']);
    //forgot password
    Route::post('/forgot-password', [ProfileController::class, 'forgotPassword']);
    Route::post('/verify-otp', [ProfileController::class, 'verifyOtp']);
    Route::post('/resend-otp', [ProfileController::class, 'resendOtp']);
    Route::post('/reset-password', [ProfileController::class, 'resetPassword']);
});
