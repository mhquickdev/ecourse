<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\CourseContent;
use App\Models\ContentCompletion;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use PDF;
use Illuminate\Support\Facades\Log;

class CourseContentController extends Controller
{
    public function courseContent(Course $course)
    {
        // Check if user is enrolled
        $enrollment = Auth::user()->enrollments()
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment) {
            return redirect()->route('courses.show', $course)
                ->with('error', 'You need to enroll in this course first.');
        }

        // For paid courses, check payment status
        if ($course->price > 0 && $enrollment->payment_status !== 'completed') {
            return redirect()->route('student.dashboard')
                ->with('error', 'Your enrollment is pending approval.');
        }

        // Load modules and contents
        $course->load(['modules.contents' => function($query) {
            $query->orderBy('order');
        }]);

        // Get the first content item
        $firstContent = null;
        if ($course->modules->isNotEmpty() && $course->modules->first()->contents->isNotEmpty()) {
            $firstContent = $course->modules->first()->contents->first();
        }

        // Get completion status for all contents
        $completions = ContentCompletion::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->pluck('is_completed', 'content_id')
            ->toArray();

        // Calculate progress
        $totalContents = $course->modules->sum(function($module) {
            return $module->contents->count();
        });
        
        $completedContents = count(array_filter($completions));
        $progressPercentage = $totalContents > 0 ? round(($completedContents / $totalContents) * 100) : 0;

        // Update enrollment progress
        $enrollment->update([
            'progress_percentage' => $progressPercentage
        ]);

        return view('student.course-content', [
            'course' => $course,
            'enrollment' => $enrollment,
            'firstContent' => $firstContent,
            'completions' => $completions
        ]);
    }

    public function getCourseContent(Course $course, CourseContent $content)
    {
        $completion = ContentCompletion::where('user_id', Auth::id())
            ->where('content_id', $content->id)
            ->first();

        // Render the content using a partial view
        $html = view('student.partials.course-content-item', [
            'content' => $content,
            'isCompleted' => $completion ? $completion->is_completed : false
        ])->render();

        return response()->json(['html' => $html]);
    }

    public function submitQuiz(Request $request, CourseContent $content)
    {
        $submittedAnswer = $request->input('answer');
        $correctAnswer = $content->quiz_answer;

        if ($submittedAnswer === $correctAnswer) {
            // Mark content as completed
            $this->completeContent($content);
            return response()->json([
                'success' => true, 
                'message' => 'Correct Answer! Content marked as completed.',
                'isCompleted' => true
            ]);
        } else {
            return response()->json([
                'success' => false, 
                'message' => 'Incorrect Answer. Please try again.',
                'isCompleted' => false
            ]);
        }
    }

    public function markContentAsCompleted(Request $request, CourseContent $content)
    {
        $this->completeContent($content);

        return response()->json([
            'success' => true,
            'message' => 'Content marked as completed',
            'isCompleted' => true
        ]);
    }

    private function completeContent(CourseContent $content)
    {
        ContentCompletion::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'course_id' => $content->module->course_id,
                'module_id' => $content->module_id,
                'content_id' => $content->id,
            ],
            [
                'is_completed' => true,
                'completed_at' => now()
            ]
        );

        // Calculate and update course progress
        $this->updateCourseProgress($content->module->course_id);
    }

    private function updateCourseProgress($courseId)
    {
        $totalContents = CourseContent::whereHas('module', function($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })->count();

        $completedContents = ContentCompletion::where('user_id', Auth::id())
            ->where('course_id', $courseId)
            ->where('is_completed', true)
            ->count();

        $progressPercentage = $totalContents > 0 ? round(($completedContents / $totalContents) * 100) : 0;

        $enrollment = Enrollment::where('user_id', Auth::id())
            ->where('course_id', $courseId)
            ->first();

        if ($enrollment) {
            $enrollment->update(['progress_percentage' => $progressPercentage]);

            // Check for certificate generation
            if ($progressPercentage >= 80 && !$this->hasCertificate($enrollment->user_id, $courseId)) {
                // Eager load relationships for certificate generation
                $enrollment->load(['user', 'course.mentor']);
                 // Pass the enrollment object to generateCertificate
                $this->generateCertificateForEnrollment($enrollment);
            }
        }
    }

    private function hasCertificate($userId, $courseId)
    {
        return Certificate::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->exists();
    }

    // Renamed method to be more specific
    private function generateCertificateForEnrollment(Enrollment $enrollment)
    {
        $user = $enrollment->user;
        $course = $enrollment->course;

        // Ensure relationships are loaded, though eager loading is done in caller
        if (!$user || !$course || !$course->mentor) {
             Log::error('Certificate generation failed: Missing user, course, or mentor relationship.', ['enrollment_id' => $enrollment->id]);
            return;
        }

        // Check if certificate already exists (extra check)
         if ($this->hasCertificate($user->id, $course->id)) {
              return; // Don't generate if it already exists
         }

        // Generate a shorter unique identifier (8 characters)
        $uuid = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));

        // Define the base file path for the certificate PDF
        $baseFilePath = 'certificates/' . $uuid . '.pdf';
        $storagePath = 'public/' . $baseFilePath;

         // Create a record in the certificates table FIRST
         $certificate = Certificate::create([
             'user_id' => $user->id,
             'course_id' => $course->id,
             'uuid' => $uuid,
             'file_path' => $baseFilePath, // Store the base path
             'issue_date' => now(),
         ]);

        // Generate the PDF from the certificate view, passing the certificate object
        // Ensure the view has access to $user, $course, and $certificate
        $pdf = PDF::loadView('certificates.appreciation', compact('user', 'course', 'certificate'));

        // Save the PDF to storage, explicitly using the 'public' disk
        Storage::disk('public')->put($baseFilePath, $pdf->output());

         // Note: file_path in DB is the base path, Storage::url will prepend /storage

        // Log or notify that a certificate was generated
         Log::info('Certificate generated for user ' . $user->id . ' and course ' . $course->id . ' with UUID ' . $uuid);
    }
} 