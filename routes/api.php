<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', [StudentController::class, 'register'])->name('register');

//Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/staff/login', [AuthController::class, 'login'])->name('staff.login');
