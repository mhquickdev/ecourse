@extends('layouts.student')

@section('content')
<div class="p-8">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">My Courses</h2>

        @if($enrolledCourses->isEmpty())
            <div class="bg-white rounded-lg shadow-lg p-6 text-center text-gray-600">
                You haven't enrolled in any courses yet. <a href="{{ route('courses.index') }}" class="text-blue-600 hover:underline">Browse available courses</a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($enrolledCourses as $enrollment)
                    @php
                        $course = $enrollment->course;
                        $progress = $enrollment->progress_percentage ?? 0;
                        $certificate = \App\Models\Certificate::where('user_id', Auth::id())
                                        ->where('course_id', $course->id)
                                        ->first();
                        $thumbnail = $course->preview_image ? (Str::startsWith($course->preview_image, 'http') ? $course->preview_image : asset('storage/'.$course->preview_image)) : 'https://via.placeholder.com/400x250.png?text=Course+Image';
                    @endphp
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <img class="w-full h-48 object-cover" src="{{ $thumbnail }}" alt="{{ $course->title }}">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $course->title }}</h3>
                                @if($enrollment->payment_status === 'completed')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Enrolled</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600 mb-3">Mentor: {{ $course->mentor->name ?? 'N/A' }}</p>

                            <div class="mb-4">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm font-medium text-gray-900">Progress</span>
                                    <span class="text-sm font-medium text-gray-600">{{ $progress }}% Complete</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $progress }}%"></div>
                                </div>
                            </div>

                            @if($enrollment->payment_status === 'completed')
                               

                                <a href="{{ route('student.course-info', $course) }}" class="block text-center bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                    Continue Learning
                                </a>
                            @else
                                <p class="block text-center bg-gray-200 text-gray-700 py-2 px-4 rounded-lg">Payment Pending</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
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
                    // Optionally, reload the page or replace the button with download link
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
<\/script>
@endpush
@endsection 