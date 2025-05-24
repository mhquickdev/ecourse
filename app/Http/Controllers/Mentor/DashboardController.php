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

        $totalCourses = $courses->count();
        $activeCourses = $courses->where('status', 'published')->count();
        $archivedCourses = $courses->where('status', 'archived')->count();
        $recentCourses = $courses->sortByDesc('created_at')->take(5);
        $totalEarnings = $courses->sum('price');

        return view('mentor.dashboard', compact(
            'totalCourses',
            'activeCourses',
            'archivedCourses',
            'recentCourses',
            'totalEarnings'
        ));
    }
}
