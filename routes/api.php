<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

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
Route::post('/login', [LoginController::class, 'login']);
Route::post('/provider', [LoginController::class, 'providerSignup']);
Route::post('/agent', [LoginController::class, 'agentSignup']);


Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/register', [LoginController::class, 'register']);
});


require __DIR__ . '/supervisor.php';
require __DIR__ . '/group_admin.php';
require __DIR__ . '/invoices.php';
require __DIR__ . '/offers.php';
require __DIR__ . '/contracts.php';
require __DIR__ . '/documents.php';
require __DIR__ . '/user_profile.php';
require __DIR__ . '/products.php';
