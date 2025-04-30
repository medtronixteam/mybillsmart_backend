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
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\AutoMessageController;
use App\Http\Controllers\NotificationController;


use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\OffersController;


//authentication
Route::post('/login', [LoginController::class, 'login']);
Route::post('2fa/login', [TwoFactorApiController::class, 'login2FA']);
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
    Route::post('payment/receipt', [StripePaymentController::class, 'paymentReceipt']);
    Route::post('/store-subscription', [StripePaymentController::class, 'storeSubscription']);


    Route::get('auth/enable-2fa', [TwoFactorApiController::class, 'setup']);
    Route::get('auth/disable-2fa', [TwoFactorApiController::class, 'disable']);

    Route::get('2fa/disable', [TwoFactorApiController::class, 'disable']);
    Route::get('2fa/enable', [TwoFactorApiController::class, 'enable2Fa']);
    Route::post('2fa/verify/code', [TwoFactorApiController::class, 'verify2FA']);

    Route::get('/profile', fn() => auth('sanctum')->user());


    Route::post('/notifications', [NotificationController::class, 'sendNotification']);
    Route::get('/notifications', [NotificationController::class, 'getUserNotifications']);
    Route::get('/notification/{id}', [NotificationController::class, 'getSingleNotification']);
    Route::put('/notification/read/{id}', [NotificationController::class, 'markAsRead']);
    //all
    Route::get('goals', [GoalsController::class, 'list']);
    //session history
    Route::get('session/history', [LoginController::class, 'sessionHistory']);

    Route::get('plan/info', [StripePaymentController::class, 'planInfo']);
    Route::get('agents/info', [StripePaymentController::class, 'planInfo']);
    Route::get('company/info/{id}', [CompanyController::class, 'specificCompanyInfo']);

});
Route::post('auth/verify-2fa', [TwoFactorApiController::class, 'validateToken']);

//2fa by email
Route::post('whatsapp/pdf', [WhatsAppController::class, 'sendPDF']);

Route::get('whatsapp/link/{id}', [WhatsAppController::class, 'linkWhats']);
Route::get('whatsapp/unlink/{id}', [WhatsAppController::class, 'unlinkWhats']);


Route::apiResource('auto-messages', AutoMessageController::class);


Route::post('/v1/stripe/webhook', [StripePaymentController::class, 'handle']);



Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'member'], function () {

    Route::post('invoices', [InvoiceController::class, 'store']);
    Route::get('invoices', [InvoiceController::class, 'index']);
    Route::post('invoice/offers', [OffersController::class, 'view']);
    Route::post('/offers', [OffersController::class, 'store']);


    //client management
    Route::post('/user', [ClientController::class, 'userCreate']);
    Route::get('/user', [ClientController::class, 'userList']);
    Route::post('user/edit/{id}', [ClientController::class, 'update']);
    Route::post('user/enable/{id}', [ClientController::class, 'enable']);
    Route::post('user/disable/{id}', [ClientController::class, 'disable']);
    Route::delete('user/delete/{id}', [ClientController::class, 'delete']);
});


//others url
require __DIR__ . '/supervisor.php';
require __DIR__ . '/group_admin.php';
require __DIR__ . '/agent.php';
require __DIR__ . '/client.php';
//require __DIR__ . '/user.php';
