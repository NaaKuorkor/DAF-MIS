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
Route::get('/modules', [DashboardController::class, 'fetchModules'])->middleware('auth');
Route::get('/overview', [DashboardController::class, 'overviewContent']);
Route::get('/myAccount', [RoutingController::class, 'showMyAccount']);
Route::get('/courses', [RoutingController::class, 'showCourseMngt']);



//Staff dashboard routes

Route::prefix('staff')->group(function () {
    Route::post('/logout', [AuthController::class, 'staffLogout'])->name('staff.logout');
    Route::get('/login', [RoutingController::class, 'showStaffLogin'])->name('staff.login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('staff.login');
    Route::get('/staff-info', [RoutingController::class, 'showStaffMngt']);
    Route::get('/student-info', [RoutingController::class, 'showStudent']);
    Route::get('/tasks', [RoutingController::class, 'showTaskMngt']);
    Route::get('/cohorts', [RoutingController::class, 'showCohortMngt']);
    Route::get('/studentTable/date', [StudentMngtController::class, 'studentTableContent']);
    Route::get('/staffTable/date', [StaffMngtController::class, 'staffTableContent']);
    Route::get('/staffTable/A-Z', [StaffMngtController::class, 'alphaStaffFilter']);
    Route::get('/studentTable/A-Z', [StudentMngtController::class, 'alphaStudentFilter']);
    Route::post('/updateStudent', [StudentMngtController::class, 'update']);
    Route::post('/deleteStudent', [StudentMngtController::class, 'deleteStudent']);
    Route::get('/searchStudent', [StudentMngtController::class, 'searchStudents']);
    Route::get('/exportStudents', [StudentMngtController::class, 'exportStudents']);
    Route::post('/importStudents', [StudentMngtController::class, 'importStudents']);
    Route::get('/exportStaff', [StaffMngtController::class, 'exportStaff'])->name('exportStaff');
    Route::post('/importStaff', [StaffMngtController::class, 'importStaff'])->name('importStaff');
    Route::post('/searchStaff', [StaffMngtController::class, 'searchStaff']);
    Route::post('/updateStaff', [StaffMngtController::class, 'updateStaff']);
    Route::post('/deleteStaff', [StaffMngtController::class, 'deleteStaff']);
    Route::post('/updateProfile', [StaffController::class, 'updateProfile']);
    Route::get('/staffProfile', [RoutingController::class, 'showStaffProfile']);
    Route::post('/updatePassword', [AuthController::class, 'updatePassword']);
});
