<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Livewire\PlanForm;
use App\Livewire\PaymentIntentsTable;
use App\Livewire\SubscriptionTable;
use App\Livewire\DocumentList;
use App\Livewire\ManageSubscriptions;
use App\Http\Controllers\AgreementController;
use App\Http\Controllers\ProductController;


use App\Http\Controllers\PdfController;

Route::get('/generate-invoice', [PdfController::class, 'generatePdf']);

//Main Page
Route::get('/', function () {
    return view('login');
});
Route::get('logout', function () {
    auth()->logout();
    return redirect()->route('login');
})->name('logout');

// Add this route


Route::get('login', [MainController::class, 'login'])->name('login');
Route::post('login', [MainController::class, 'auth'])->name('login.auth');


Route::group(['middleware' => 'auth'], function () {



Route::get('dashboard', [MainController::class, 'dashboard'])->name('dashboard')->middleware('auth');
Route::get('list/admins', [MainController::class, 'userList'])->name('user.list');
Route::get('list/group/admin', [MainController::class, 'groupAdmin'])->name('group.admin');
Route::get('list/users', [MainController::class, 'allUsers'])->name('all.users');
Route::get('view/user/{viewId}', [MainController::class, 'userView'])->name('user.view');
Route::post('/users/disable/{userId}', [MainController::class, 'disable'])->name('user.disable');
Route::post('/users/enable/{userId}', [MainController::class, 'enable'])->name('user.enable');
Route::post('/users/delete/{deleteId}', [MainController::class, 'delete'])->name('user.delete');
Route::get('user/create', [MainController::class, 'usersdata'])->name('user.create');
Route::post('user/create', [MainController::class, 'storeUsers'])->name('user.store');
Route::get('profile', [MainController::class, 'profile'])->name('profile');
Route::post('reset-pass', [MainController::class, 'resetPass'])->name('reset.password');
Route::post('reset-name', [MainController::class, 'resetName'])->name('reset.name');
//user
Route::get('user/reset-password/{passId}', [MainController::class, 'reset'])->name('user.password');
Route::post('user/reset-pass', [MainController::class, 'changePass'])->name('change.password');


Route::get('contracts/list', [MainController::class, 'contractsList'])->name('contracts.list');
Route::get('invoice/list', [MainController::class, 'invoiceList'])->name('invoice.list');
Route::get('offer/view/{id}', [MainController::class, 'showOffer'])->name('offers.view');
Route::get('view/detail/{id}', [MainController::class, 'showDetail'])->name('view.detail');


Route::get('plans', PlanForm::class)->name('plans');
Route::get('payments', PaymentIntentsTable::class)->name('payments');
Route::get('subscriptions', SubscriptionTable::class)->name('subscriptions');
Route::get('subscription/manage/{userId}', ManageSubscriptions::class)->name('subscriptions.view');
Route::get('whatsapp', App\Livewire\WhatsappManager::class)->name('whatsapp');
Route::get('ShowDatabaseTables', App\Livewire\ShowDatabaseTables::class)->name('ShowDatabaseTables');


//electricity agreements
Route::get('electricity/agreements', [AgreementController::class, 'agreements'])->name('agreements');
Route::get('electricity/agreements/create', [AgreementController::class, 'electricityCreate'])->name('electricity.create');
Route::post('electricity/agreements/store', [AgreementController::class, 'electricityStore'])->name('electricity.store');
Route::post('/electricity/agreement/delete/{deleteId}', [AgreementController::class, 'electricityDelete'])->name('electricity.delete');
Route::get('/electricity/agreement/edit/{editId}', [AgreementController::class, 'electricityEdit'])->name('electricity.edit');
Route::post('electricity/agreement/update', [AgreementController::class, 'electricityUpdate'])->name('electricity.update');
Route::get('/electricity/agreement/view/{viewId}', [AgreementController::class, 'electricityView'])->name('electricity.view');
//gas agreements
Route::get('gas/agreements', [AgreementController::class, 'gasAgreements'])->name('gas.list');
Route::get('gas/agreements/create', [AgreementController::class, 'gasCreate'])->name('gas.create');
Route::post('gas/agreements/store', [AgreementController::class, 'gasStore'])->name('gas.store');
Route::post('/gas/agreement/delete/{deleteId}', [AgreementController::class, 'gasDelete'])->name('gas.delete');
Route::get('/gas/agreement/edit/{editId}', [AgreementController::class, 'gasEdit'])->name('gas.edit');
Route::post('gas/agreement/update', [AgreementController::class, 'gasUpdate'])->name('gas.update');
Route::get('/gas/agreement/view/{viewId}', [AgreementController::class, 'gasView'])->name('gas.view');
//both agreements
Route::get('combined/agreements', [AgreementController::class, 'combinedAgreements'])->name('combined.list');
Route::get('combined/agreements/create', [AgreementController::class, 'combinedCreate'])->name('combined.create');
Route::post('combined/agreements/store', [AgreementController::class, 'combinedStore'])->name('combined.store');
Route::post('/combined/agreement/delete/{deleteId}', [AgreementController::class, 'combinedDelete'])->name('combined.delete');
Route::get('/combined/agreement/edit/{editId}', [AgreementController::class, 'combinedEdit'])->name('combined.edit');
Route::post('combined/agreement/update', [AgreementController::class, 'combinedUpdate'])->name('combined.update');
Route::get('/combined/agreement/view/{viewId}', [AgreementController::class, 'combinedView'])->name('combined.view');



Route::get('/documents', DocumentList::class)->name('documents');
Route::get('/webhooks', App\Livewire\WebhookManager::class)->name('webhooks');


});

