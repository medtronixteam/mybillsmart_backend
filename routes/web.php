<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;





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
Route::get('list/users', [MainController::class, 'userList'])->name('user.list');
Route::get('view/user/{viewId}', [MainController::class, 'userView'])->name('user.view');
Route::post('/users/disable/{userId}', [MainController::class, 'disable'])->name('user.disable');
Route::post('/users/enable/{userId}', [MainController::class, 'enable'])->name('user.enable');
Route::post('/users/delete/{deleteId}', [MainController::class, 'delete'])->name('user.delete');
Route::get('user/create', [MainController::class, 'usersdata'])->name('user.create');
Route::post('user/store', [MainController::class, 'storeUsers'])->name('user.store');
Route::get('profile', [MainController::class, 'profile'])->name('profile');
Route::post('reset-pass', [MainController::class, 'resetPass'])->name('reset.password');
Route::post('reset-name', [MainController::class, 'resetName'])->name('reset.name');
//user
Route::get('user/reset-password/{passId}', [MainController::class, 'reset'])->name('user.password');
Route::post('Student/reset-pass', [MainController::class, 'changePass'])->name('change.password');



