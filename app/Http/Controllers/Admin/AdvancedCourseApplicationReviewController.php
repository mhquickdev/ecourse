<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdvancedCourseApplication;
use Illuminate\Http\Request;

class AdvancedCourseApplicationReviewController extends Controller
{
    public function show(AdvancedCourseApplication $advancedApplication)
    {
        // Load relationships if needed for the review page (e.g., user and course)
        $advancedApplication->load(['user', 'course']);

        return view('admin.advanced_applications.review', compact('advancedApplication'));
    }
}
