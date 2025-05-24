@extends('layouts.mentor')

@section('header')
    <h2 class="text-2xl font-bold text-gray-800 leading-tight">
        Mentor Dashboard
    </h2>
@endsection

@section('content')
<div class="p-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row gap-8">
           
            <!-- Main Content -->
            <main class="flex-1">
                <div class="py-8">
                    <div class="flex flex-col gap-6">
                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="flex-1 bg-pink-100 rounded-2xl shadow-lg flex items-center p-6 relative overflow-hidden">
                                <div class="flex items-center justify-center w-16 h-16 bg-pink-200 rounded-xl mr-4">
                                    <i class="fa-solid fa-book-open text-3xl text-pink-500"></i>
                                </div>
                                <div>
                                    <div class="text-lg font-bold text-pink-600">Total Courses</div>
                                    <div class="text-2xl font-extrabold text-pink-800">{{ $courses_count }}</div>
                                </div>
                                <span class="absolute top-4 right-4 bg-pink-500 text-white text-xs font-bold px-4 py-1 rounded-full">Active</span>
                            </div>
                            <div class="flex-1 bg-green-100 rounded-2xl shadow-lg flex items-center p-6 relative overflow-hidden">
                                <div class="flex items-center justify-center w-16 h-16 bg-green-200 rounded-xl mr-4">
                                    <i class="fa-solid fa-users text-3xl text-green-600"></i>
                                </div>
                                <div>
                                    <div class="text-lg font-bold text-green-600">Total Students</div>
                                    <div class="text-2xl font-extrabold text-green-800">{{ $students_count }}</div>
                                </div>
                                <span class="absolute top-4 right-4 bg-green-600 text-white text-xs font-bold px-4 py-1 rounded-full">Enrolled</span>
                            </div>
                            <div class="flex-1 bg-yellow-100 rounded-2xl shadow-lg flex items-center p-6 relative overflow-hidden">
                                <div class="flex items-center justify-center w-16 h-16 bg-yellow-200 rounded-xl mr-4">
                                    <i class="fa-solid fa-star text-3xl text-yellow-500"></i>
                                </div>
                                <div>
                                    <div class="text-lg font-bold text-yellow-600">Total Earnings</div>
                                    <div class="text-2xl font-extrabold text-yellow-800">${{ number_format($average_rating, 2) }}</div>
                                </div>
                                <span class="absolute top-4 right-4 bg-yellow-500 text-white text-xs font-bold px-4 py-1 rounded-full">This Month</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6 mt-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Recent Courses</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($recent_courses as $course)
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
                            <div class="col-span-3 text-gray-500">No recent courses</div>
                        @endforelse
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
@endsection 