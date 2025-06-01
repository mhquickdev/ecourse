<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdvancedCourseApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdvancedCourseApplicationController extends Controller
{
    public function index()
    {
        $applications = AdvancedCourseApplication::with(['user', 'course'])->latest()->get();
        return view('admin.advanced_applications.index', compact('applications'));
    }

    public function approve(Request $request, AdvancedCourseApplication $advancedApplication)
    {
        Log::info('Approving application: ' . $advancedApplication->id);
        $request->validate([
            'course_link' => 'required|url',
            'instruction' => 'nullable|string',
        ]);

        $advancedApplication->update([
            'status' => 'approved',
            'course_link' => $request->course_link,
            'instruction' => $request->instruction,
        ]);

        return redirect()->back()->with('success', 'Application approved successfully.');
    }

    public function reject(Request $request, AdvancedCourseApplication $advancedApplication)
    {
        Log::info('Rejecting application: ' . $advancedApplication->id);
        $request->validate([
            'rejection_note' => 'required|string',
        ]);

        $advancedApplication->update([
            'status' => 'rejected',
            'rejection_note' => $request->rejection_note,
        ]);

        return redirect()->back()->with('success', 'Application rejected successfully.');
    }
}
