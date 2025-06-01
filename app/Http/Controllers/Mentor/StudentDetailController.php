<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

class StudentDetailController extends Controller
{
    public function show(User $student)
    {
        $mentor = Auth::user();

        // Fetch enrollments of this student specifically for courses taught by the current mentor
        $enrollments = $student->enrollments()
            ->whereIn('course_id', $mentor->courses()->pluck('id'))
            ->with('course')
            ->get();

        // Optionally, fetch other student-specific data if needed

        return view('mentor.students.show', compact('student', 'enrollments'));
    }
} 