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
use App\Http\Controllers\Student\CourseCohortController;
use App\Http\Controllers\StudentMngtController;
use App\Http\Controllers\CohortController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Controllers\AnnouncementsController;

// ============================================================================
// PUBLIC ROUTES (No Authentication Required)
// ============================================================================

// Base route
Route::get('/', [RoutingController::class, 'showLoginForm']);

// ============================================================================
// GUEST ROUTES (Authentication Not Required)
// ============================================================================

Route::middleware('guest')->group(function () {
    // Student Authentication
    Route::get('/login', [RoutingController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    
    Route::get('/register', [RoutingController::class, 'showRegisterForm'])->name('register.form');
    Route::post('/register', [StudentController::class, 'register'])->name('register');
    
    // Staff Authentication
    Route::get('/staff/login', [RoutingController::class, 'showStaffLogin'])->name('staff.login.form');
    Route::post('/staff/login', [AuthController::class, 'login'])->name('staff.login');
    
    // Password Reset
    Route::get('/forgotPassword', [AuthController::class, 'showForgotPassword'])->name('forgotPassword.form');
    Route::post('/forgotPassword', [AuthController::class, 'forgotPassword'])->name('forgotPassword');
    
    // OTP Verification (for password reset)
    Route::get('/verifyOTP', [AuthController::class, 'showVerifyOTP'])->name('verifyOTP.form');
    Route::post('/verifyOTP', [AuthController::class, 'verifyOTP'])->name('verifyOTP');

    Route::get('/resetPassword', [AuthController::class, 'showResetPassword'])->middleware('auth')->name('password.reset');
    Route::post('/resetPassword', [AuthController::class, 'resetPassword'])->middleware('auth')->name('password.update');
});

// ============================================================================
// EMAIL VERIFICATION ROUTES
// ============================================================================

Route::get('/email/verify', [RoutingController::class, 'showVerifyEmail'])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [StudentController::class, 'showVerifySuccess'])
    ->middleware(['signed'])
    ->name('verification.verify');

// ============================================================================
// AUTHENTICATED ROUTES (Any Authenticated User)
// ============================================================================

Route::middleware('auth')->group(function () {
    // Dashboard API
    Route::get('/modules', [DashboardController::class, 'fetchModules']);
    Route::get('/content/{route}', [DashboardController::class, 'getContent'])->name('dashboard.content');
    
    // Logout routes
    Route::post('/staff/logout', [AuthController::class, 'staffLogout'])->name('staff.logout');
    Route::post('/student/logout', [AuthController::class, 'studentLogout'])->name('student.logout');
});

// ============================================================================
// STUDENT ROUTES
// ============================================================================

Route::middleware(['auth', 'student'])->group(function () {
    // Student Dashboard
    Route::get('/dashboard', [RoutingController::class, 'showDashboard'])->name('dashboard');
    
    // Student Views
    Route::get('/course-cohort', [RoutingController::class, 'course_cohort']);
    Route::get('/indexcourse-cohort', [CourseCohortController::class, 'index']);
    Route::get('/myProfile', [RoutingController::class, 'showStudentProfile']);
    
    // Student Profile API
    Route::get('/profile', [ProfileController::class, 'showProfile'])->name('student.profile');
    Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('student.profile.update');
    Route::post('/updatePassword', [AuthController::class, 'updatePassword'])->name('student.password.update');
});

// ============================================================================
// STAFF ROUTES
// ============================================================================

Route::prefix('staff')->middleware(['auth', 'staff'])->group(function () {
    // ========================================================================
    // Staff Dashboard & Views
    // IMPORTANT: View routes must come FIRST before any API routes
    // ========================================================================
    
    Route::get('/dashboard', [RoutingController::class, 'showStaffDashboard'])->name('staff.dashboard');
    Route::get('/overview', [RoutingController::class, 'showStaffOverview']);
    Route::get('/staff-info', [RoutingController::class, 'showStaffMngt']);
    Route::get('/student-info', [RoutingController::class, 'showStudentMngt']);
    Route::get('/tasks', [RoutingController::class, 'showTaskMngt']);
    Route::get('/cohorts', [RoutingController::class, 'showCohortMngt']);
    Route::get('/courses', [RoutingController::class, 'showCourseMngt']);
    Route::get('/staffProfile', [RoutingController::class, 'showStaffProfile']);
    Route::get('/announcements', [RoutingController::class, 'showAnnouncements']);
    
    // ========================================================================
    // Staff Management API
    // ========================================================================
    
    Route::prefix('staffTable')->group(function () {
        Route::get('/date', [StaffMngtController::class, 'staffTableContent']);
        Route::get('/A-Z', [StaffMngtController::class, 'alphaStaffFilter']);
    });
    
    Route::post('/searchStaff', [StaffMngtController::class, 'searchStaff']);
    Route::post('/updateStaff', [StaffMngtController::class, 'updateStaff']);
    Route::post('/deleteStaff', [StaffMngtController::class, 'deleteStaff']);
    Route::get('/exportStaff', [StaffMngtController::class, 'exportStaff'])->name('exportStaff');
    Route::post('/importStaff', [StaffMngtController::class, 'importStaff'])->name('importStaff');
    
    // ========================================================================
    // Student Management API
    // ========================================================================
    
    Route::prefix('studentTable')->group(function () {
        Route::get('/date', [StudentMngtController::class, 'studentTableContent']);
        Route::get('/A-Z', [StudentMngtController::class, 'alphaStudentFilter']);
    });
    
    Route::post('/updateStudent', [StudentMngtController::class, 'update']);
    Route::post('/deleteStudent', [StudentMngtController::class, 'deleteStudent']);
    Route::get('/searchStudent', [StudentMngtController::class, 'searchStudents']);
    Route::get('/exportStudents', [StudentMngtController::class, 'exportStudents'])->name('exportStudents');
    Route::post('/importStudents', [StudentMngtController::class, 'importStudents'])->name('importStudents');
    
    // ========================================================================
    // Staff Profile Management
    // ========================================================================
    
    Route::post('/updateProfile', [StaffController::class, 'updateProfile']);
    Route::post('/updatePassword', [AuthController::class, 'updatePassword']);
    
    // ========================================================================
    // Announcements API
    // IMPORTANT: Must come BEFORE /{course_id} route to prevent conflicts
    // ========================================================================
    
    Route::prefix('announcements')->group(function () {
        Route::get('/list', [AnnouncementsController::class, 'index'])->name('announcements.index');
        Route::post('/', [AnnouncementsController::class, 'store'])->name('announcements.store');
        Route::post('/draft', [AnnouncementsController::class, 'saveDraft'])->name('announcements.draft');
        Route::get('/{announcement_id}', [AnnouncementsController::class, 'show'])->name('announcements.show');
        Route::put('/{announcement_id}', [AnnouncementsController::class, 'update'])->name('announcements.update');
        Route::delete('/{announcement_id}', [AnnouncementsController::class, 'destroy'])->name('announcements.delete');
    });
    
    // ========================================================================
    // Course Management API
    // IMPORTANT: Specific routes must come BEFORE parameterized routes
    // ========================================================================
    
    Route::get('/viewCourses', [CourseController::class, 'viewCourses'])->name('courses.index');
    Route::post('/createCourse', [CourseController::class, 'createCourse'])->name('courses.create');
    Route::get('/search', [CourseController::class, 'searchCourse'])->name('courses.search');
    Route::post('/filter', [CourseController::class, 'filterCourses'])->name('courses.filter');
    
    // Course parameterized routes (MUST come after all specific routes including announcements)
    Route::get('/{course_id}/registrations', [CourseRegistrationController::class, 'viewRegistrationsByCourse'])
        ->name('courses.registrations');
    Route::get('/{course_id}', [CourseController::class, 'getCourse'])->name('courses.show');
    Route::put('/{course_id}', [CourseController::class, 'updateCourse'])->name('courses.update');
    Route::post('/{course_id}/update', [CourseController::class, 'updateCourse'])->name('courses.update.post');
    Route::delete('/{course_id}', [CourseController::class, 'deleteCourse'])->name('courses.delete');
    
    // ========================================================================
    // Cohort Management API
    // IMPORTANT: Specific routes must come BEFORE parameterized routes
    // ========================================================================
    
    Route::post('/createCohort', [CohortController::class, 'createCohort'])->name('cohorts.create');
    Route::post('/assignToCohort', [CohortController::class, 'assignToCohort'])->name('cohorts.assign');
    
    // Cohort parameterized routes (must come after specific routes)
    Route::get('/cohorts/{course_id}', [CohortController::class, 'viewCohortsForCourse'])->name('cohorts.for-course');
    Route::get('/cohort/{cohort_id}/students', [CohortController::class, 'viewStudentsForCohort'])->name('cohorts.students');
    Route::delete('/cohorts/{cohort_id}', [CohortController::class, 'deleteCohort'])->name('cohorts.delete');
    
    // ========================================================================
    // Task Management API
    // IMPORTANT: Specific routes must come BEFORE parameterized routes
    // ========================================================================
    
    Route::get('/tasks/list', [TaskController::class, 'indexTasks'])->name('tasks.index');
    Route::post('/tasks/create', [TaskController::class, 'createTask'])->name('tasks.create');
    Route::get('/tasks/export', [TaskController::class, 'exportTasks'])->name('tasks.export');
    
    // Task parameterized routes (must come after specific routes)
    Route::get('/tasks/{task_id}', [TaskController::class, 'showTask'])->name('tasks.show');
    Route::put('/tasks/{task_id}', [TaskController::class, 'updateTask'])->name('tasks.update');
    Route::post('/tasks/{task_id}/complete', [TaskController::class, 'markCompleted'])->name('tasks.complete');
    Route::delete('/tasks/{task_id}', [TaskController::class, 'deleteTask'])->name('tasks.delete');
});

// ============================================================================
// PUBLIC API ROUTES (No Authentication Required)
// ============================================================================

Route::get('/overview', [DashboardController::class, 'overviewContent']);

// ============================================================================
// STAFF REGISTRATION (If needed without auth - adjust middleware as needed)
// ============================================================================

Route::post('/staff/register', [StaffController::class, 'createStaff'])->name('register.staff');