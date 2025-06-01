<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\User;

class StudentController extends Controller
{
    public function index()
    {
        $mentor = Auth::user();

        // Get courses created by the mentor
        $mentorCourses = $mentor->courses()->pluck('id');

        // Get students enrolled in these courses
        // This assumes an 'enrollments' table linking users and courses
        $students = User::whereHas('enrollments', function ($query) use ($mentorCourses) {
            $query->whereIn('course_id', $mentorCourses);
        })->with(['enrollments' => function ($query) use ($mentorCourses) {
             $query->whereIn('course_id', $mentorCourses);
        }, 'enrollments.course'])
        ->get();

        // You might need to add logic here or in the view to calculate and display progress

        return view('mentor.students.index', compact('students'));
    }
}
