<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\WhatsAppController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\GoalsController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\TwoFactorApiController;
use App\Http\Controllers\Api\AutoMessageController;
use App\Http\Controllers\NotificationController;




//authentication
Route::post('/login', [LoginController::class, 'login']);
Route::post('/signup', [LoginController::class, 'referalRegister']);


Route::get('/list/products/{groupId}', [ProductController::class, 'providerProducts']);


Route::post('/forgot-password', [ProfileController::class, 'forgotPassword']);
Route::post('/verify-otp', [ProfileController::class, 'verifyOtp']);
Route::post('/resend-otp', [ProfileController::class, 'resendOtp']);
Route::post('/reset-password', [ProfileController::class, 'resetPassword']);

Route::post('truncate-table-columns', [ProfileController::class, 'truncateTableColumns']);
Route::get('verify-url/{randomId}', [ProfileController::class, 'verifyUrl']);
//auth
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('generate-url', [ProfileController::class, 'generateUrl']);
    Route::post('/change-password', [ProfileController::class, 'changePassword']);
    Route::post('user/profile', [ProfileController::class, 'update']);


    Route::post('/create-payment-intent', [StripePaymentController::class, 'createPaymentIntent']);
    Route::post('/store-subscription', [StripePaymentController::class, 'storeSubscription']);


    Route::get('auth/enable-2fa', [TwoFactorApiController::class, 'setup']);
    Route::get('auth/disable-2fa', [TwoFactorApiController::class, 'disable']);
    Route::get('/profile', fn() => auth('sanctum')->user());


    Route::post('/notifications', [NotificationController::class, 'sendNotification']);
    Route::get('/notifications', [NotificationController::class, 'getUserNotifications']);
    Route::get('/notification/{id}', [NotificationController::class, 'getSingleNotification']);
    Route::put('/notification/read/{id}', [NotificationController::class, 'markAsRead']);

    //all
    Route::get('goals', [GoalsController::class, 'list']);

});
Route::post('auth/verify-2fa', [TwoFactorApiController::class, 'validateToken']);

Route::post('whatsapp/pdf', [WhatsAppController::class, 'sendPDF']);


Route::apiResource('auto-messages', AutoMessageController::class);



//others url
require __DIR__ . '/supervisor.php';
require __DIR__ . '/group_admin.php';
require __DIR__ . '/agent.php';
require __DIR__ . '/client.php';
