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
    });

    // Student Routes
    Route::middleware(['role:student'])->prefix('student')->group(function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
        Route::get('/profile', [\App\Http\Controllers\Student\ProfileController::class, 'index'])->name('student.profile');
        Route::post('/profile', [\App\Http\Controllers\Student\ProfileController::class, 'update'])->name('student.profile.update');
    });

    // Mentor Routes
    Route::middleware(['auth', 'verified', 'role:mentor'])->prefix('mentor')->name('mentor.')->group(function () {
        Route::get('/dashboard', [MentorDashboardController::class, 'index'])->name('dashboard');
        Route::resource('courses', CourseController::class);
        Route::get('/profile', [\App\Http\Controllers\Mentor\ProfileController::class, 'index'])->name('profile');
        Route::post('/profile', [\App\Http\Controllers\Mentor\ProfileController::class, 'update'])->name('profile.update');
        
        // Course deletion routes
        Route::post('/courses/{course}/demo-videos/delete', [CourseController::class, 'deleteDemoVideo'])->name('courses.demo-videos.delete');
        Route::post('/courses/{course}/modules/delete', [CourseController::class, 'deleteModule'])->name('courses.modules.delete');
        Route::post('/courses/{course}/modules/contents/delete', [CourseController::class, 'deleteContent'])->name('courses.modules.contents.delete');
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

require __DIR__.'/auth.php';
