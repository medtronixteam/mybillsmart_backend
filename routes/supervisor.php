<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SupervisorController;


Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'supervisor'], function () {

Route::post('/user', [SupervisorController::class, 'userCreate']);
Route::get('/user', [SupervisorController::class, 'userList']);

});
