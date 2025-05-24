<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = \App\Models\User::count();
        $totalCourses = \App\Models\Course::count();
        $totalCategories = \App\Models\Category::count();
        $recentUsers = \App\Models\User::latest()->limit(5)->get();
        $recentCourses = \App\Models\Course::latest()->limit(5)->get();
        return view('admin.dashboard', compact('totalUsers', 'totalCourses', 'totalCategories', 'recentUsers', 'recentCourses'));
    }
}
