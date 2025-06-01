<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $enrolledCourses = $user->enrolledCourses()->with('user', 'category')->latest()->get();
        $enrolledCoursesCount = $enrolledCourses->count();
        $wishlistedCoursesCount = $user->wishlistedCourses()->count();

        return view('student.dashboard', compact('enrolledCourses', 'enrolledCoursesCount', 'wishlistedCoursesCount'));
    }
}
