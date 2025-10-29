<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminAuthController;

//Base route
Route::get('/', [AuthController::class, 'showLoginForm']);

//User routes
Route::get('/register',[AuthController::class, 'showRegisterForm'])->middleware('guest')->name('register.form');

Route::post('/register',[AuthController::class, 'register'])->middleware('guest')->name('register');


Route::get('/login', [AuthController::class, 'showLoginForm'])->middleware('guest')->name('login.form');

Route::post('/login', [AuthController::class, 'login'])->middleware('auth:student')->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:student')->name('logout');

Route::get('/dashboard', [AuthController::class, 'showDashboard'])->middleware('auth:student')->name('dashboard');



//Admin routes
Route::middleware('guest:staff')->group(function () {

    Route::get('/staff/login', [AdminAuthController::class, 'showStaffLogin'])->name('staff.login.form');

    Route::post('/staff/login', [AdminAuthController::class, 'staffLogin'])->name('staff.login');


});


Route::middleware('auth:staff')->group(function(){

    Route::post('/staff/logout', [AdminAuthController::class, 'logout'])->name('staff.logout');

    Route::get('/staff/dashboard', [AdminAuthController::class, 'showStaffDashboard'])->name('staff.dashboard');


});


