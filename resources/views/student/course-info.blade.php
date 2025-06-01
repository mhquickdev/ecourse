@extends('layouts.student')

@section('content')
<div class="p-8">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Course Information: {{ $course->title }}</h2>

        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
                <div>
                    <p class="text-gray-700 mb-2"><strong>Mentor:</strong> {{ $course->mentor->name ?? 'N/A' }}</p>
                    <p class="text-gray-700"><strong>Status:</strong> 
                        @if($enrollment->payment_status === 'completed')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Enrolled</span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                        @endif
                    </p>
                </div>
                
                 @if($enrollment->payment_status === 'completed')
                      <div class="flex flex-col md:flex-row gap-4 mt-4 md:mt-0">
                         @if($course->modules->isNotEmpty() && $course->modules->first()->contents->isNotEmpty())
                            <a href="{{ route('student.course-content', ['course' => $course->id, 'content' => $course->modules->first()->contents->first()->id]) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 text-center">
                                 Start Learning
                            </a>
                         @endif

                     @if(isset($certificate))
                         <a href="{{ Storage::url($certificate->file_path) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200 text-center" download>
                             Download Certificate
                         </a>
                     @elseif(($enrollment->progress_percentage ?? 0) >= 80)
                         <form action="{{ route('student.generate-certificate', ['course' => $course->id]) }}" method="POST" target="_blank">
                             @csrf
                             <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors duration-200">
                                 Generate Certificate
                             </button>
                         </form>
                         
                     @endif
                     {{-- Show Apply for Advanced Course button if eligible and no pending/approved application --}}
                     @if(($enrollment->progress_percentage ?? 0) >= 80 && (!isset($latestApplication) || $latestApplication->status === 'rejected'))
                         <button type="button" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors duration-200" onclick="showAdvancedCourseApplicationModal()">
                             Apply for Advanced Course
                         </button>
                     @endif
                      </div>
                 @endif
            </div>

            <div class="course-progress mt-6">
                <h5 class="font-bold text-gray-800 mb-3">Your Progress</h5>
                <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mb-1">
                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $enrollment->progress_percentage ?? 0 }}%"></div>
                </div>
                <div class="text-sm font-medium text-gray-600">{{ $enrollment->progress_percentage ?? 0 }}% Complete</div>
            </div>
        </div>

        {{-- Advanced Course Application Status --}}
        @if(isset($latestApplication))
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <h5 class="font-bold text-gray-800 mb-3">Advanced Course Application Status</h5>
                
                @if($latestApplication->status === 'pending')
                    <p><strong>Status:</strong> <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span></p>
                    <p class="mt-2 text-gray-600">Your application is currently under review.</p>
                @elseif($latestApplication->status === 'approved')
                    <p><strong>Status:</strong> <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span></p>
                    <h6 class="font-bold text-gray-800 mt-4 mb-2">Advanced Course Details:</h6>
                    <p><strong>Course Link:</strong> <a href="{{ $latestApplication->course_link }}" target="_blank" class="text-blue-600 hover:underline">{{ $latestApplication->course_link ?? 'N/A' }}</a></p>
                    <p><strong>Instructions:</strong> {{ $latestApplication->instruction ?? 'N/A' }}</p>
                @elseif($latestApplication->status === 'rejected')
                    <p><strong>Status:</strong> <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span></p>
                    <p class="text-red-600 mt-4">Your last application was rejected. You can apply again.</p>
                @endif
            </div>
        @endif

        {{-- Rejected Applications History --}}
        @if($rejectedApplications->isNotEmpty())
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <h5 class="font-bold text-gray-800 mb-3">Rejected Application History</h5>
                <ul>
                    @foreach($rejectedApplications as $rejectedApplication)
                        <li class="mb-2 text-gray-600">
                            Applied on: {{ $rejectedApplication->created_at->format('Y-m-d') }}
                             @if($rejectedApplication->application_message)
                                (Message: {{ Str::limit($rejectedApplication->rejection_note, 100) }})
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Modules and Content Overview -->
        <div class="bg-white rounded-lg shadow-lg p-6">
             <h5 class="text-xl font-bold text-gray-900 mb-4">Course Outline</h5>
             <div class="space-y-4">
                @foreach($course->modules as $module)
                     <div class="border rounded-lg overflow-hidden">
                        <button type="button" class="w-full flex justify-between items-center px-4 py-3 bg-gray-50 hover:bg-gray-100 font-semibold text-left focus:outline-none" onclick="document.getElementById('module-info-content-{{ $module->id }}').classList.toggle('hidden')">
                             <span>{{ $module->title }}</span>
                            <i class="fa fa-chevron-down ml-2"></i>
                         </button>
                         <div id="module-info-content-{{ $module->id }}" class="hidden bg-white">
                             @forelse($module->contents as $content)
                                 @php
                                      $isCompleted = \App\Models\ContentCompletion::where('user_id', Auth::id())
                                           ->where('content_id', $content->id)
                                           ->exists();
                                 @endphp
                                 <a href="{{ route('student.course-content', ['course' => $course->id, 'content' => $content->id]) }}" class="flex items-center justify-between px-6 py-3 border-t hover:bg-gray-50 transition">
                                     <div class="flex items-center gap-2">
                                         @if($content->type === 'video')
                                             <i class="fa-solid fa-play-circle {{ $isCompleted ? 'text-green-500' : 'text-pink-500' }}"></i>
                                         @elseif($content->type === 'quiz')
                                             <i class="fa-solid fa-question-circle {{ $isCompleted ? 'text-green-500' : 'text-yellow-500' }}"></i>
                                         @elseif($content->type === 'file')
                                             <i class="fa-solid fa-file-alt {{ $isCompleted ? 'text-green-500' : 'text-green-500' }}"></i>
                                         @else
                                             <i class="fa-solid fa-circle {{ $isCompleted ? 'text-green-500' : 'text-gray-400' }}"></i>
                                         @endif
                                         <span class="font-medium text-gray-800">{{ $content->title }}</span>
                                     </div>
                                      @if($isCompleted)
                                         <span class="text-green-600"><i class="fas fa-check-circle"></i></span>
                                     @endif
                                 </a>
                             @empty
                                 <div class="px-6 py-3 text-gray-400">No content in this module</div>
                             @endforelse
                         </div>
                     </div>
                 @endforeach
             </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // JavaScript for Generate Certificate Button
    document.querySelectorAll('.generate-certificate-btn').forEach(button => {
        button.addEventListener('click', function() {
            const courseId = this.dataset.courseId;
            const generateBtn = this;

            // Disable button and show loading indicator (optional)
            generateBtn.disabled = true;
            generateBtn.textContent = 'Generating...';

            fetch(`/student/courses/${courseId}/generate-certificate`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message); // Or use a more stylish notification
                    // Reload the page to show the download button
                    window.location.reload();
                } else {
                    alert('Error generating certificate: ' + data.message); // Show error message
                    // Re-enable button
                    generateBtn.disabled = false;
                    generateBtn.textContent = 'Generate Certificate';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                 alert('An error occurred while generating the certificate.');
                 // Re-enable button
                 generateBtn.disabled = false;
                 generateBtn.textContent = 'Generate Certificate';
            });
        });
    });
});
</script>

{{-- Stylish Modal for Advanced Course Application --}}
<div id="advanced-course-application-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" style="display: none;">
    <div class="relative top-20 mx-auto p-6 border w-full max-w-sm shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Apply for Advanced Course</h3>
            <div class="mt-2 px-7 py-3">
                {{-- The actual application form --}}
                <form id="advanced-application-form" action="{{ route('student.apply-advanced-course', ['course' => $course->id]) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="application_message" class="block text-gray-700 text-sm font-bold mb-2">Your Message (Optional):</label>
                        <textarea name="application_message" id="application_message" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                    </div>
                    <div class="flex items-center justify-end mt-6 gap-4">
                        <button type="button" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500" onclick="hideAdvancedCourseApplicationModal()">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Submit Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showAdvancedCourseApplicationModal() {
    document.getElementById('advanced-course-application-modal').style.display = 'block';
}

function hideAdvancedCourseApplicationModal() {
    document.getElementById('advanced-course-application-modal').style.display = 'none';
}

// Modify the button's onclick to show the modal directly
document.addEventListener('DOMContentLoaded', function () {
    const applyButton = document.querySelector('button.bg-yellow-600'); // Assuming this is the unique class for the apply button
    if (applyButton) {
        applyButton.onclick = showAdvancedCourseApplicationModal;
    }
});

// Keep the original form submission - no AJAX needed
document.getElementById('advanced-application-form').addEventListener('submit', function(e) {
    // You could add client-side validation here if needed
});
</script>
@endpush
@endsection 