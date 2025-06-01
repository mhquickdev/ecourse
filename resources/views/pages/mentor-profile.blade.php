@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
        <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
            <div class="flex-shrink-0">
                <img src="{{ $mentor->profile_image ? Storage::url($mentor->profile_image) : 'https://i.pravatar.cc/150' }}" class="w-32 h-32 rounded-full object-cover border-4 border-pink-600 shadow-md" alt="{{ $mentor->name }}">
            </div>
            <div class="flex-1 text-center md:text-left">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $mentor->name }}</h1>
                <p class="text-gray-600 mb-4">{{ $mentor->bio ?? 'No bio available.' }}</p>
                @if($mentor->education && is_array($mentor->education) && count($mentor->education))
                    <div class="mb-3">
                        <span class="font-semibold text-gray-800">Education:</span>
                        <ul class="list-disc list-inside text-gray-700">
                            @foreach($mentor->education as $edu)
                                <li>{{ is_array($edu) ? ($edu['degree'] ?? $edu['institute'] ?? '') : $edu }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if($mentor->experience && is_array($mentor->experience) && count($mentor->experience))
                     <div class="mb-3">
                        <span class="font-semibold text-gray-800">Experience:</span>
                        <ul class="list-disc list-inside text-gray-700">
                            @foreach($mentor->experience as $exp)
                                <li>{{ is_array($exp) ? ($exp['title'] ?? $exp['company'] ?? '') : $exp }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                 @if($mentor->skills && is_array($mentor->skills) && count($mentor->skills))
                    <div class="mb-3">
                        <span class="font-semibold text-gray-800">Skills:</span>
                        <div class="flex flex-wrap gap-2 mt-1">
                            @foreach($mentor->skills as $skill)
                                @php
                                    $skillName = is_array($skill) ? ($skill['name'] ?? null) : (is_string($skill) ? $skill : null);
                                    $skillRating = is_array($skill) && isset($skill['rating']) ? floatval($skill['rating']) : null;
                                @endphp
                                @if($skillName)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-800 font-semibold text-sm shadow-sm">
                                        {{ $skillName }}
                                        @if($skillRating)
                                            <span class="ml-2 flex items-center">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-3 h-3 {{ $i <= $skillRating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.175c.969 0 1.371 1.24.588 1.81l-3.38 2.455a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.38-2.455a1 1 0 00-1.175 0l-3.38 2.455c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.394c-.783-.57-.38-1.81.588-1.81h4.175a1 1 0 00.95-.69l1.286-3.967z"/></svg>
                                                @endfor
                                            </span>
                                        @endif
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="mt-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Courses by {{ $mentor->name }}</h2>
        @if($mentor->courses->count())
             <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                @foreach($mentor->courses as $course)
                    @include('components.course-card', [
                         'course' => $course, // Pass the course object
                         'image' => $course->preview_image ? (Str::startsWith($course->preview_image, 'http') ? $course->preview_image : asset('storage/'.$course->preview_image)) : 'https://images.unsplash.com/photo-1513258496099-48168024aec0?auto=format&fit=crop&w=600&q=80',
                         'discount' => $course->is_free ? 'Free' : null,
                         'instructor_avatar' => $course->user && $course->user->profile_image ? Storage::url($course->user->profile_image) : 'https://i.pravatar.cc/120',
                         'instructor' => $course->user->name ?? 'Instructor',
                         'category' => $course->category->name ?? 'General',
                         'title' => $course->title,
                         'rating' => '4.4', // Replace with actual rating if available
                         'reviews' => '180', // Replace with actual review count if available
                         'price' => $course->price ?? '0',
                         'url' => route('courses.show', $course),
                         // Pass isWishlisted status if available for this course for the logged-in user
                         'isWishlisted' => auth()->check() ? auth()->user()->wishlistedCourses->contains($course->id) : false,
                    ])
                @endforeach
            </div>
        @else
            <p class="text-gray-600">This mentor has no courses available yet.</p>
        @endif
    </div>
</div>
@endsection 