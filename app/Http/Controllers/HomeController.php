<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $courses = Course::where('status', 'published')->latest()->take(6)->get();
        $mentors = User::where('role_id', 3)->latest()->take(4)->get(); // Assuming role_id 3 is for mentors
        return view('welcome', compact('courses', 'mentors'));
    }
} 