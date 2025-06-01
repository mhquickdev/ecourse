<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\MentorRegisterController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Mentor\DashboardController as MentorDashboardController;
use App\Http\Controllers\Mentor\CourseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CourseContentController;
use App\Http\Controllers\CertificateController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/create-storage-link', function () {
    Artisan::call('storage:link');
    return 'Storage link created!';
});

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // User Routes (more specific routes first)
        Route::get('users/students', [UserController::class, 'students'])->name('users.students.index');
        Route::get('users/mentors', [UserController::class, 'mentors'])->name('users.mentors.index');
        Route::resource('users', UserController::class);

        Route::resource('courses', \App\Http\Controllers\Admin\CourseController::class);
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->except(['show']);

        Route::get('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
        Route::post('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');

        // Advanced Course Applications Routes
        Route::get('/advanced-applications', [\App\Http\Controllers\Admin\AdvancedCourseApplicationController::class, 'index'])->name('advanced-applications.index');
        Route::post('/advanced-applications/{advancedApplication}/approve', [\App\Http\Controllers\Admin\AdvancedCourseApplicationController::class, 'approve'])->name('advanced-applications.approve');
        Route::post('/advanced-applications/{advancedApplication}/reject', [\App\Http\Controllers\Admin\AdvancedCourseApplicationController::class, 'reject'])->name('advanced-applications.reject');
        Route::get('/advanced-applications/{advancedApplication}', [\App\Http\Controllers\Admin\AdvancedCourseApplicationReviewController::class, 'show'])->name('advanced-applications.review');
    });

    // Student Routes
    Route::middleware(['role:student'])->prefix('student')->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
        Route::get('/profile', [\App\Http\Controllers\Student\ProfileController::class, 'index'])->name('student.profile');
        Route::post('/profile', [\App\Http\Controllers\Student\ProfileController::class, 'update'])->name('student.profile.update');

        // Enrollment Routes
        Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'enroll'])->name('courses.enroll');
        Route::post('/courses/{course}/enroll-free', [EnrollmentController::class, 'enrollFree'])->name('courses.enroll-free');
        Route::post('/courses/{course}/payment', [EnrollmentController::class, 'processPayment'])->name('courses.payment');
        Route::get('/my-courses', [EnrollmentController::class, 'myCourses'])->name('student.my-courses');
        Route::get('/enrollment/success/{enrollment}', [EnrollmentController::class, 'success'])->name('enrollment.success');

        // Course Info Page
        Route::get('/courses/{course}/info', [EnrollmentController::class, 'courseInfo'])->name('student.course-info');

        // Course Content Routes
        Route::get('/courses/{course}/content', [CourseContentController::class, 'courseContent'])->name('student.course-content');
        Route::get('/courses/{course}/content/{content}', [CourseContentController::class, 'getCourseContent'])->name('student.get-course-content');
        Route::post('/content/{content}/mark-completed', [CourseContentController::class, 'markContentAsCompleted'])->name('student.mark-content-completed');
        Route::post('/content/{content}/submit-quiz', [CourseContentController::class, 'submitQuiz'])->name('student.submit-quiz');

        // Wishlist Routes
        Route::get('/wishlist', [WishlistController::class, 'index'])->name('student.wishlist');
        Route::post('/wishlist/{course}', [WishlistController::class, 'add'])->name('wishlist.add');
        Route::delete('/wishlist/{course}', [WishlistController::class, 'remove'])->name('wishlist.remove');
        Route::post('/wishlist/{course}/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

        // Manual Certificate Generation
        Route::post('/courses/{course}/generate-certificate', [EnrollmentController::class, 'generateManualCertificate'])->name('student.generate-certificate');

        // Advanced Course Application
        Route::post('/courses/{course}/apply-advanced', [EnrollmentController::class, 'applyAdvancedCourse'])->name('student.apply-advanced-course');

        // Student Transactions
        Route::get('/transactions', [\App\Http\Controllers\Student\TransactionController::class, 'index'])->name('student.transactions.index');
    });

    // Mentor Routes
    Route::middleware(['auth', 'verified', 'role:mentor'])->prefix('mentor')->name('mentor.')->group(function () {
        Route::get('/dashboard', [MentorDashboardController::class, 'index'])->name('dashboard');
        Route::resource('courses', CourseController::class);
        Route::get('/profile', [\App\Http\Controllers\Mentor\ProfileController::class, 'index'])->name('my-profile');
        Route::post('/profile', [\App\Http\Controllers\Mentor\ProfileController::class, 'update'])->name('profile.update');
        
        // Course deletion routes
        Route::post('/courses/{course}/demo-videos/delete', [CourseController::class, 'deleteDemoVideo'])->name('courses.demo-videos.delete');
        Route::post('/courses/{course}/modules/delete', [CourseController::class, 'deleteModule'])->name('courses.modules.delete');
        Route::post('/courses/{course}/modules/contents/delete', [CourseController::class, 'deleteContent'])->name('courses.modules.contents.delete');

        // Mentor Students
        Route::get('/students', [\App\Http\Controllers\Mentor\StudentController::class, 'index'])->name('students.index');
        Route::get('/students/{student}', [\App\Http\Controllers\Mentor\StudentDetailController::class, 'show'])->name('students.show');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('mentor/register', [MentorRegisterController::class, 'create'])
        ->name('mentor.register');
    Route::post('mentor/register', [MentorRegisterController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    // User Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // // Mentor Dashboard
    // Route::middleware('role:mentor')->group(function () {
    //     Route::get('/mentor/dashboard', function () {
    //         return view('mentor.dashboard');
    //     })->name('mentor.dashboard');
    // });

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/courses', [\App\Http\Controllers\CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course}', [\App\Http\Controllers\CourseController::class, 'show'])->name('courses.show');

// Mentor Profile Route
Route::get('/mentor/{mentor}', [App\Http\Controllers\MentorProfileController::class, 'show'])->name('mentor.profile');

// All Mentors Route
Route::get('/mentors', [App\Http\Controllers\PublicMentorController::class, 'index'])->name('mentors.index');

// Certificate Verification Route (accessible publicly)
Route::get('/certificate/{uuid}', [CertificateController::class, 'verify'])->name('certificate.verify');

require __DIR__.'/auth.php';
