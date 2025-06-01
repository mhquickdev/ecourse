<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Wishlist;
use App\Models\CourseContent;
use App\Models\ContentCompletion;
use App\Models\Certificate;
use App\Models\AdvancedCourseApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use PDF;

class EnrollmentController extends Controller
{
    public function enroll(Request $request, Course $course)
    {
        // Check if user is already enrolled
        if ($course->enrolledStudents()->where('user_id', Auth::id())->exists()) {
            return redirect()->back()->with('error', 'You are already enrolled in this course.');
        }

        // For paid courses, redirect to payment page
        // Free course enrollment is handled by a separate method/route now
        return view('enrollments.payment', [
            'course' => $course
        ]);
    }

    public function enrollFree(Course $course)
    {
         // Check if user is already enrolled
        if ($course->enrolledStudents()->where('user_id', Auth::id())->exists()) {
            return redirect()->route('student.course-content', $course)->with('error', 'You are already enrolled in this course.');
        }

        // Ensure the course is actually free
        if ($course->price > 0) {
            return redirect()->route('courses.show', $course)->with('error', 'This course is not free.');
        }

        // Enroll directly for free courses
        $enrollment = Enrollment::create([
            'user_id' => Auth::id(),
            'course_id' => $course->id,
            'payment_status' => 'completed',
            'enrolled_at' => now(),
            'amount_paid' => 0,
            'payment_method' => 'free',
        ]);

         return redirect()->route('enrollment.success', $enrollment)->with('success', 'Successfully enrolled in the free course!');
    }

    public function processPayment(Request $request, Course $course)
    {
        $request->validate([
            'payment_method' => 'required|in:bank_transfer',
            'transaction_id' => 'required|string',
        ]);

        $enrollment = Enrollment::create([
            'user_id' => Auth::id(),
            'course_id' => $course->id,
            'amount_paid' => $course->price,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
            'transaction_id' => $request->transaction_id,
            'payment_details' => json_encode($request->except(['_token'])),
            'enrolled_at' => now(),
        ]);

        return redirect()->route('enrollment.success', $enrollment)
            ->with('success', 'Your enrollment is pending. Please wait for admin approval.');
    }

    public function success(Enrollment $enrollment)
    {
        // Ensure the logged-in user owns this enrollment
        if ($enrollment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('enrollments.success', compact('enrollment'));
    }

    public function myCourses()
    {
        $user = Auth::user();
        $enrolledCourses = $user->enrollments()
                                ->with(['course.mentor'])
                                ->get();

        return view('student.my-courses', compact('enrolledCourses'));
    }

    public function courseInfo(Course $course)
    {
        $user = Auth::user();

        // Check if the user is enrolled in the course
        $enrollment = $user->enrollments()
                           ->where('course_id', $course->id)
                           ->with(['course.mentor', 'course.modules.contents'])
                           ->first();

        if (!$enrollment) {
            return redirect()->route('student.my-courses')
                             ->with('error', 'You are not enrolled in this course.');
        }

        // Check if a certificate exists for this enrollment
        $certificate = Certificate::where('user_id', $user->id)
                                ->where('course_id', $course->id)
                                ->first();

        // Fetch all advanced course applications for this user and course
        $advancedApplications = AdvancedCourseApplication::where('user_id', $user->id)
                                                        ->where('course_id', $course->id)
                                                        ->orderByDesc('created_at') // Get the latest first
                                                        ->get();

        // Get the latest application (if any)
        $latestApplication = $advancedApplications->first();

        // Filter out rejected applications for a separate list
        $rejectedApplications = $advancedApplications->where('status', 'rejected');

        return view('student.course-info', compact('enrollment', 'course', 'certificate', 'latestApplication', 'rejectedApplications'));
    }

    /**
     * Generate certificate for a completed enrollment manually.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response|
     */
    public function generateManualCertificate(Course $course)
    {
        $user = Auth::user();

        // Check if the user is enrolled in the course and payment is completed
        $enrollment = $user->enrollments()->where('course_id', $course->id)->first();

        if (!$enrollment || $enrollment->payment_status !== 'completed') {
            abort(403, 'User is not enrolled or payment is not completed.');
        }

        // Check if progress is >= 80%
        if (($enrollment->progress_percentage ?? 0) < 80) {
             abort(400, 'Minimum 80% progress required to generate certificate.');
        }

        // Check if a certificate already exists for this enrollment
         $existingCertificate = Certificate::where('enrollment_id', $enrollment->id)->first();
         if ($existingCertificate) {
              // If exists, return the PDF file directly
              $filePath = Storage::path('public/' . $existingCertificate->file_path);
              if (Storage::exists('public/' . $existingCertificate->file_path)) {
                   return response()->file($filePath);
              } else {
                  // If file doesn't exist but record does, maybe try regenerating or show error
                  // For now, let's regenerate it.
                   $existingCertificate->delete(); // Delete the old record
              }
         }

        // Generate certificate
         try {
             // Generate a shorter unique identifier (8 characters)
             $uuid = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
             $filename = 'certificates/' . $uuid . '.pdf';

             // Ensure course, user, and mentor data is loaded for the view
             // Fetch relationships needed for the certificate view
              $enrollment->load('user', 'course.mentor');

             $data = [
                 'user' => $user, // Pass the full user model
                 'course' => $course, // Pass the full course model
                 'mentor' => $course->mentor ?? null, // Pass the mentor
                 'completionDate' => now()->format('F d, Y'),
                 'uuid' => $uuid,
             ];

             // Create certificate record in the database FIRST to get the object
             $certificate = Certificate::create([
                 'enrollment_id' => $enrollment->id,
                 'user_id' => $user->id,
                 'course_id' => $course->id,
                 'uuid' => $uuid,
                 'file_path' => $filename, // Store the intended file path
                 'issue_date' => now(),
             ]);

              // Now add the certificate object to the data for the view
               $data['certificate'] = $certificate;

              $pdf = PDF::loadView('certificates.appreciation', $data)->setPaper('a4', 'landscape');

              // Save the PDF to public storage
              Storage::disk('public')->put($filename, $pdf->output());

               // Now return the generated PDF file
               $filePath = Storage::path('public/' . $certificate->file_path);
               return response()->file($filePath);

         } catch (\Exception $e) {
              // Log the error for debugging
             logger()->error('Certificate generation failed: ' . $e->getMessage(), ['exception' => $e]);
             // Redirect back with an error message or show a simple error page
              return redirect()->back()->with('error', 'Failed to generate certificate. Please try again later.');
         }
    }

    public function applyAdvancedCourse(Request $request, Course $course)
    {
        $user = Auth::user();

        try {
            $application = AdvancedCourseApplication::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'application_message' => $request->input('application_message'),
                'status' => 'pending', // Default status is pending
            ]);

            // Optionally, notify the admin about the new application
            // Notification logic goes here...

            // Redirect back to the course info page with a success message
            return redirect()->back()->with('success', 'Your application for the advanced course has been submitted!');
        } catch (\Exception $e) {
            // Log any error that occurs during the database save
            Log::error('Failed to save advanced course application: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'course_id' => $course->id,
                'exception' => $e
            ]);

            // Redirect back with an error message
            return redirect()->back()->with('error', 'Failed to submit your application. Please try again.');
        }
    }
}
