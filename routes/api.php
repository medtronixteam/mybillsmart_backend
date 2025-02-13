<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
Route::middleware('api')->group(function () {
    // Your API endpoints here
    Route::get('/test', function () {
        return response()->json(['message' => 'API is working!']);
    });

    // Example resource route
    Route::apiResource('posts', \App\Http\Controllers\PostController::class);
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
//authentication
Route::post('/register', [LoginController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/provider', [LoginController::class, 'providerSignup']);
Route::post('/agent', [LoginController::class, 'agentSignup']);

//products
Route::post('products',[ProductController::class,'store']);
Route::get('products',[ProductController::class,'list']);
Route::post('products/{id}',[ProductController::class,'update']);
Route::delete('products/{id}',[ProductController::class, 'delete']);
