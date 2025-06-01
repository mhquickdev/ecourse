@extends('layouts.mentor')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h2 class="text-3xl font-extrabold text-gray-900 mb-6">Student Details: {{ $student->name ?? 'N/A' }}</h2>

    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <h5 class="text-xl font-bold text-gray-800 mb-4">Student Information</h5>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <p><strong>Name:</strong> {{ $student->name ?? 'N/A' }}</p>
            <p><strong>Email:</strong> {{ $student->email ?? 'N/A' }}</p>
            {{-- Add other student details you want to display here in the grid --}}
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <h3 class="text-xl font-bold text-gray-900 mb-4 p-6">Enrolled Courses (Your Courses)</h3>
        @if($enrollments->isEmpty())
            <div class="p-6 text-gray-600">
                No courses from you are enrolled by this student.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course Title</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enrollment Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                             {{-- Add more headers if needed --}}
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($enrollments as $enrollment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $enrollment->course->title ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $enrollment->created_at->format('Y-m-d') ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $enrollment->progress_percentage ?? 0 }}%</td>
                                 {{-- Add more data cells if needed --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection 