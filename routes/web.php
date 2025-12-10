<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\RoutingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StaffMngtController;
use App\Http\Controllers\StudentMngtController;

//Base route
Route::get('/', [RoutingController::class, 'showLoginForm']);

//User routes
Route::get('/register', [RoutingController::class, 'showRegisterForm'])->middleware('guest')->name('register.form');
Route::post('/register', [StudentController::class, 'register'])->name('register');
Route::post('/staff/register', [StaffController::class, 'createStaff'])->name('register.staff');



Route::get('/login', [RoutingController::class, 'showLoginForm'])->middleware('guest')->name('login.form');

Route::get('/dashboard', [RoutingController::class, 'showDashboard'])->middleware('auth', 'student')->name('dashboard');

Route::post('/login', [AuthController::class, 'login'])->name('login');


//Admin routesi


Route::get('/staff/login', [RoutingController::class, 'showStaffLogin'])->name('staff.login.form');

Route::post('/staff/login', [AuthController::class, 'login'])->name('staff.login');


Route::middleware('auth', 'staff')->group(function () {

    Route::get('/staff/dashboard', [RoutingController::class, 'showStaffDashboard'])->name('staff.dashboard');
});

Route::post('/staff/logout', [AuthController::class, 'staffLogout'])->name('staff.logout');
Route::post('/student/logout', [AuthController::class, 'studentLogout'])->name('student.logout');

Route::get('email/verify', [RoutingController::class, 'showVerifyEmail'])->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [StudentController::class, 'showVerifySuccess'])->middleware(['signed'])->name('verification.verify');


//Staff dashboard routes
Route::get('/modules', [DashboardController::class, 'fetchModules'])->middleware('auth');
Route::get('/overview', [DashboardController::class, 'overviewContent']);
Route::get('/staff/staff-info', [RoutingController::class, 'showStaffMngt']);
Route::get('/staff/student-info', [RoutingController::class, 'showStudentMngt']);
Route::get('/staff/tasks', [RoutingController::class, 'showTaskMngt']);
Route::get('/myAccount', [RoutingController::class, 'showMyAccount']);
Route::get('/courses', [RoutingController::class, 'showCourseMngt']);
Route::get('/staff/cohorts', [RoutingController::class, 'showCohortMngt']);
Route::get('/staff/studentTable/date', [StudentMngtController::class, 'studentTableContent']);
Route::get('/staff/staffTable/date', [StaffMngtController::class, 'staffTableContent']);
Route::get('/staff/staffTable/A-Z', [StaffMngtController::class, 'alphaStaffFilter']);
Route::get('/staff/studentTable/A-Z', [StudentMngtController::class, 'alphaStudentFilter']);
Route::post('/staff/updateStudent', [StudentMngtController::class, 'update']);
Route::post('/staff/deleteStudent', [StudentMngtController::class, 'deleteStudent']);
Route::get('/staff/searchStudent', [StudentMngtController::class, 'searchStudent']);
Route::get('/staff/exportStudents', [StudentMngtController::class, 'exportStudents'])->name('exportStudents');
Route::post('/staff/importStudents', [StudentMngtController::class, 'importStudents'])->name('importStudents');
