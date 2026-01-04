<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseRegistrationController;
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
Route::post('/forgotPassword', [AuthController::class, 'forgotPassword'])->name('forgotPassword');

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
    Route::get('/student-info', [RoutingController::class, 'showStudentMngt']);
    Route::get('/tasks', [RoutingController::class, 'showTaskMngt']);
    Route::get('/cohorts', [RoutingController::class, 'showCohortMngt']);
    Route::get('/studentTable/date', [StudentMngtController::class, 'studentTableContent']);
    Route::get('/staffTable/date', [StaffMngtController::class, 'staffTableContent']);
    Route::get('/staffTable/A-Z', [StaffMngtController::class, 'alphaStaffFilter']);
    Route::get('/studentTable/A-Z', [StudentMngtController::class, 'alphaStudentFilter']);
    Route::post('/updateStudent', [StudentMngtController::class, 'update']);
    Route::post('/deleteStudent', [StudentMngtController::class, 'deleteStudent']);
    Route::get('/searchStudent', [StudentMngtController::class, 'searchStudents']);
    Route::get('/exportStudents', [StudentMngtController::class, 'exportStudents'])->name('exportStudents');
    Route::post('/importStudents', [StudentMngtController::class, 'importStudents'])->name('importStudents');
    Route::get('/exportStaff', [StaffMngtController::class, 'exportStaff'])->name('exportStaff');
    Route::post('/importStaff', [StaffMngtController::class, 'importStaff'])->name('importStaff');
    Route::post('/searchStaff', [StaffMngtController::class, 'searchStaff']);
    Route::post('/updateStaff', [StaffMngtController::class, 'updateStaff']);
    Route::post('/deleteStaff', [StaffMngtController::class, 'deleteStaff']);
    Route::post('/updateProfile', [StaffController::class, 'updateProfile']);
    Route::get('/staffProfile', [RoutingController::class, 'showStaffProfile']);
    Route::post('/updatePassword', [AuthController::class, 'updatePassword']);
    // View all courses
    Route::get('/courses', [CourseController::class, 'viewCourses'])->name('courses.index');

    // Create course
    Route::post('/createcourse', [CourseController::class, 'createCourse'])->name('courses.create');

    // Get single course
    Route::get('/{course_id}', [CourseController::class, 'getCourse'])->name('courses.show');

    // Search courses
    Route::get('/search', [CourseController::class, 'searchCourse'])->name('courses.search');

    // Filter courses
    Route::post('/filter', [CourseController::class, 'filterCourses'])->name('courses.filter');

    // Update course
    Route::put('/{course_id}', [CourseController::class, 'updateCourse'])->name('courses.update');
    Route::post('/{course_id}/update', [CourseController::class, 'updateCourse'])->name('courses.update.post'); // For form submissions

    // Delete course
    Route::delete('/{course_id}', [CourseController::class, 'deleteCourse'])->name('courses.delete');

    // View registrations by course
    Route::get('/{course_id}/registrations', [CourseRegistrationController::class, 'viewRegistrationsByCourse'])->name('courses.registrations');
});
