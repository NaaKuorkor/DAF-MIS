<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\RoutingController;

//Base route
Route::get('/', [RoutingController::class, 'showLoginForm']);

//User routes
Route::get('/register', [RoutingController::class, 'showRegisterForm'])->middleware('guest')->name('register.form');

Route::post('/register', [StudentController::class, 'register'])->middleware('guest')->name('register');


Route::get('/login', [RoutingController::class, 'showLoginForm'])->middleware('guest:student')->name('login.form');

Route::post('/login', [AuthController::class, 'login'])->middleware('guest:student')->name('login');

Route::get('/dashboard', [RoutingController::class, 'showDashboard'])->middleware('auth:student')->name('dashboard');



//Admin routesi
Route::middleware('guest:staff')->group(function () {

    Route::get('/staff/login', [RoutingController::class, 'showStaffLogin'])->name('staff.login.form');

    Route::post('/staff/login', [AuthController::class, 'login'])->name('staff.login');
});


Route::middleware('auth:staff')->group(function () {

    Route::get('/staff/dashboard', [RoutingController::class, 'showStaffDashboard'])->name('staff.dashboard');
});

Route::post('/logout/{guard}', [AuthController::class, 'logout'])->name('logout');

Route::get('email/verify', [RoutingController::class, 'showVerifyEmail'])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [RoutingController::class, 'showVerifySuccess'])->middleware(['auth', 'signed'])->name('verification.verify');
