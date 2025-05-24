@extends('layouts.app')

@section('header')
    <h2 class="text-2xl font-bold text-gray-800 leading-tight">
        Mentor Dashboard
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Sidebar -->
            <aside class="md:w-1/4 w-full bg-white rounded-xl shadow p-6 sticky top-8 h-fit mb-8 md:mb-0">
                <div class="flex flex-col items-center">
                    <img src="{{ $mentor->profile_image ? Storage::url($mentor->profile_image) : 'https://i.pravatar.cc/120' }}" class="w-24 h-24 rounded-full border-4 border-blue-200 object-cover shadow mb-3" alt="Profile Image">
                    <h3 class="text-lg font-bold text-gray-800">{{ $mentor->name }}</h3>
                    <p class="text-gray-500 text-sm mb-2">@ {{ $mentor->username }}</p>
                    <a href="{{ route('mentor.profile') }}" class="mt-2 px-4 py-2 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 font-semibold">Edit Profile</a>
                </div>
            </aside>
            <!-- Main Content -->
            <main class="flex-1">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-blue-100 p-6 rounded-lg shadow-md flex flex-col items-center">
                        <i class="fa-solid fa-book-open text-3xl text-blue-600 mb-2"></i>
                        <div class="text-2xl font-bold text-blue-800">{{ $courses_count }}</div>
                        <div class="text-blue-700">Courses</div>
                    </div>
                    <div class="bg-green-100 p-6 rounded-lg shadow-md flex flex-col items-center">
                        <i class="fa-solid fa-users text-3xl text-green-600 mb-2"></i>
                        <div class="text-2xl font-bold text-green-800">{{ $students_count }}</div>
                        <div class="text-green-700">Students</div>
                    </div>
                    <div class="bg-yellow-100 p-6 rounded-lg shadow-md flex flex-col items-center">
                        <i class="fa-solid fa-star text-3xl text-yellow-500 mb-2"></i>
                        <div class="text-2xl font-bold text-yellow-800">{{ $average_rating }}</div>
                        <div class="text-yellow-700">Avg. Rating</div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Courses</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($recent_courses as $course)
                            <div class="bg-gray-50 rounded-lg p-4 flex flex-col">
                                <h4 class="font-bold text-blue-700 mb-1">{{ $course->title }}</h4>
                                <p class="text-gray-600 text-sm mb-2">{{ $course->description }}</p>
                                <div class="flex items-center gap-2 mt-auto">
                                    <span class="text-xs text-gray-400">{{ $course->created_at->diffForHumans() }}</span>
                                    <a href="{{ route('courses.show', $course->id) }}" class="ml-auto text-blue-600 hover:underline text-sm">View</a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-3 text-gray-500">No recent courses</div>
                        @endforelse
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
@endsection 