@extends('layouts.mentor')

@section('content')
<div class="p-6">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">My Courses</h2>
            <a href="{{ route('mentor.courses.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <i class="fas fa-plus mr-2"></i> Create New Course
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($courses as $course)
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="relative">
                        <img src="{{ Storage::url($course->preview_image) }}" alt="{{ $course->title }}" class="w-full h-48 object-cover">
                        <div class="absolute top-2 right-2">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $course->status === 'published' ? 'bg-green-100 text-green-800' : 
                                   ($course->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst($course->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">{{ $course->title }}</h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit(strip_tags($course->description), 100) }}</p>
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-500">
                                <span class="font-medium">{{ $course->modules->count() }}</span> Modules
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('mentor.courses.edit', $course) }}" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('mentor.courses.destroy', $course) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Are you sure you want to delete this course?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-12">
                        <div class="text-gray-400 mb-4">
                            <i class="fas fa-book text-4xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No courses yet</h3>
                        <p class="text-gray-500 mb-6">Start creating your first course to share your knowledge with students.</p>
                        <a href="{{ route('mentor.courses.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <i class="fas fa-plus mr-2"></i> Create Your First Course
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection 