<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Mentor\DashboardController as MentorDashboardController;
use App\Http\Controllers\Mentor\CourseController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
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

require __DIR__.'/auth.php';
