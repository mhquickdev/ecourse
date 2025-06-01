<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Enrollment;

class DashboardController extends Controller
{
    public function index()
    {
        $mentor = Auth::user();
        $coursesQuery = $mentor->courses();
        $courses = $coursesQuery->get();

        // Get unique students enrolled in mentor's courses
        $totalStudents = Enrollment::whereIn('course_id', $mentor->courses()->pluck('id'))->distinct('user_id')->count();
        
        $activeCourses = $courses->where('status', 'published')->count();
        $archivedCourses = $courses->where('status', 'archived')->count();
        $recent_courses = $courses->sortByDesc('created_at')->take(6);
        $totalEarnings = $courses->sum('price');
        $courses_count = $courses->count();
        $average_rating = $courses->avg('rating');


        return view('mentor.dashboard', compact(
            'mentor',
            'activeCourses',
            'archivedCourses',
            'recent_courses',
            'totalEarnings',
            'courses_count',
            'average_rating',
            'courses',
            'totalStudents'
        ));
    }
}
