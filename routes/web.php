<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Livewire\PlanForm;
use App\Livewire\PaymentIntentsTable;
use App\Livewire\SubscriptionTable;
use App\Http\Controllers\AgreementController;




//Main Page
Route::get('/', function () {
    return view('login');
});
Route::get('logout', function () {
    auth()->logout();
    return redirect()->route('login');
})->name('logout');
Route::get('login', [MainController::class, 'login'])->name('login');
Route::post('login', [MainController::class, 'auth'])->name('login.auth');
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

Route::post('agreements', [AgreementController::class, 'store'])->name('agreements.store');
Route::get('agreements/list', [AgreementController::class, 'agreements'])->name('agreements');
Route::get('agreements', [AgreementController::class, 'index'])->name('agreements.create');
Route::get('agreements/view/{id}', [AgreementController::class, 'view'])->name('agreements.view');
Route::get('agreements/edit/{id}', [AgreementController::class, 'edit'])->name('agreements.edit');
Route::post('agreements/update', [AgreementController::class, 'update'])->name('agreements.update');
Route::post('/agreements/delete/{deleteId}', [AgreementController::class, 'delete'])->name('agreements.delete');
