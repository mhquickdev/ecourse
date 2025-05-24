<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $mentor = auth()->user();
        $coursesQuery = $mentor->courses();
        $courses = $coursesQuery->get();

        
        $activeCourses = $courses->where('status', 'published')->count();
        $archivedCourses = $courses->where('status', 'archived')->count();
        $recent_courses = $courses->sortByDesc('created_at')->take(6);
        $totalEarnings = $courses->sum('price');
        $courses_count = $courses->count();
        $students_count = $courses->sum('students_count');
        $average_rating = $courses->avg('rating');


        return view('mentor.dashboard', compact(
            'mentor',
            'activeCourses',
            'archivedCourses',
            'recent_courses',
            'totalEarnings',
            'courses_count',
            'students_count',
            'average_rating',
            'courses'
        ));
    }
}
