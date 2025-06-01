@extends('layouts.student')

@section('content')
<div class="p-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row gap-8 mb-8">
            <!-- Stat Card: Enrolled Courses -->
            <div class="flex-1 bg-blue-100 rounded-2xl shadow-lg flex items-center p-6 relative overflow-hidden">
                <div class="flex items-center justify-center w-16 h-16 bg-blue-200 rounded-xl mr-4">
                    <i class="fa-solid fa-book-open text-3xl text-blue-500"></i>
                </div>
                <div>
                    <div class="text-lg font-bold text-blue-600">Enrolled Courses</div>
                    <div class="text-2xl font-extrabold text-blue-800">{{ $enrolledCoursesCount ?? 0 }}</div>
                </div>
                <span class="absolute top-4 right-4 bg-blue-500 text-white text-xs font-bold px-4 py-1 rounded-full">Total</span>
            </div>
            <!-- Stat Card: Active Courses -->
            <div class="flex-1 bg-green-100 rounded-2xl shadow-lg flex items-center p-6 relative overflow-hidden">
                <div class="flex items-center justify-center w-16 h-16 bg-green-200 rounded-xl mr-4">
                    <i class="fa-solid fa-heart text-3xl text-green-600"></i>
                </div>
                <div>
                    <div class="text-lg font-bold text-green-600">Wishlisted Courses</div>
                    <div class="text-2xl font-extrabold text-green-800">{{ $wishlistedCoursesCount ?? 0 }}</div>
                </div>
                <span class="absolute top-4 right-4 bg-green-600 text-white text-xs font-bold px-4 py-1 rounded-full">Wishlisted</span>
            </div>
            <!-- Stat Card: Completed Courses -->
            <div class="flex-1 bg-yellow-100 rounded-2xl shadow-lg flex items-center p-6 relative overflow-hidden">
                <div class="flex items-center justify-center w-16 h-16 bg-yellow-200 rounded-xl mr-4">
                    <i class="fa-solid fa-check-circle text-3xl text-yellow-500"></i>
                </div>
                <div>
                    <div class="text-lg font-bold text-yellow-600">Completed Courses</div>
                    <div class="text-2xl font-extrabold text-yellow-800">1</div>
                </div>
                <span class="absolute top-4 right-4 bg-yellow-500 text-white text-xs font-bold px-4 py-1 rounded-full">Completed</span>
            </div>
        </div>
        <!-- Enrolled Courses List -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mt-8">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Enrolled Courses</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($enrolledCourses as $course)
                    @include('components.course-card', [
                        'image' => $course->preview_image ? (Str::startsWith($course->preview_image, 'http') ? $course->preview_image : asset('storage/'.$course->preview_image)) : 'https://images.unsplash.com/photo-1513258496099-48168024aec0?auto=format&fit=crop&w=600&q=80',
                        'discount' => null, // Or display enrollment status if needed
                        'instructor_avatar' => $course->user && $course->user->profile_image ? Storage::url($course->user->profile_image) : 'https://i.pravatar.cc/120',
                        'instructor' => $course->user->name ?? 'Instructor',
                        'category' => $course->category->name ?? 'General',
                        'title' => $course->title,
                        'rating' => 'N/A', // Assuming rating is not per enrollment
                        'reviews' => 'N/A', // Assuming reviews are not per enrollment
                        'price' => $course->is_free ? 'Free' : number_format($course->price, 2),
                        'url' => route('student.course-content', $course),
                        'show_price' => false, // Hide price for enrolled courses
                        'show_rating' => false, // Hide rating for enrolled courses
                        'course' => $course, // Pass the course object for wishlist toggle
                        'isWishlisted' => Auth::user()->wishlistedCourses->contains($course->id) // Pass wishlist status
                    ])
                @empty
                    <div class="col-span-full">
                        <div class="alert alert-info">
                            You have not enrolled in any courses yet.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection 