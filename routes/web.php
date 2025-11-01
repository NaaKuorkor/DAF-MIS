<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StudentController;

//Base route
Route::get('/', [StudentController::class, 'showLoginForm']);

//User routes
Route::get('/register',[StudentController::class, 'showRegisterForm'])->middleware('guest')->name('register.form');

Route::post('/register',[StudentController::class, 'register'])->middleware('guest')->name('register');


Route::get('/login', [StudentController::class, 'showLoginForm'])->middleware('guest:student')->name('login.form');

Route::post('/login', [AuthController::class, 'login'])->middleware('guest:student')->name('login');

Route::get('/dashboard', [StudentController::class, 'showDashboard'])->middleware('auth:student')->name('dashboard');



//Admin routesi
Route::middleware('guest:staff')->group(function () {

    Route::get('/staff/login', [StaffController::class, 'showStaffLogin'])->name('staff.login.form');

    Route::post('/staff/login', [AuthController::class, 'login'])->name('staff.login');

});


Route::middleware('auth:staff')->group(function(){

        Route::get('/staff/dashboard', [StaffController::class, 'showStaffDashboard'])->name('staff.dashboard');
});

Route::post('/logout/{guard}', [AuthController::class, 'logout'])->name('logout');

