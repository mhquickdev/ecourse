@extends('layouts.mentor')

@section('content')
<div class="p-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
            <h2 class="text-3xl font-extrabold text-gray-900">My Courses</h2>
            <a href="{{ route('mentor.courses.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-xl shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 font-semibold transition text-lg">
                <i class="fas fa-plus"></i> Create New Course
            </a>
        </div>
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($courses as $course)
                @include('components.course-card', [
                    'image' => $course->preview_image ? (Str::startsWith($course->preview_image, 'http') ? $course->preview_image : asset('storage/'.$course->preview_image)) : 'https://images.unsplash.com/photo-1513258496099-48168024aec0?auto=format&fit=crop&w=600&q=80',
                    'discount' => $course->is_free ? 'Free' : null,
                    'instructor_avatar' => $course->user && $course->user->profile_image ? Storage::url($course->user->profile_image) : 'https://i.pravatar.cc/120',
                    'instructor' => $course->user->name ?? 'Instructor',
                    'category' => $course->category->name ?? 'General',
                    'title' => $course->title,
                    'rating' => $course->rating ?? '4.5',
                    'reviews' => $course->reviews_count ?? '15',
                    'price' => $course->price ?? '0',
                    'url' => route('mentor.courses.edit', $course),
                ])
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
        <div class="mt-8 flex justify-center">
            {{ $courses->links('vendor.pagination.tailwind') }}
        </div>
    </div>
</div>
@endsection 