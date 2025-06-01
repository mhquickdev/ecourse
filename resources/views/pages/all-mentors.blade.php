@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">All Mentors</h1>

    @if($mentors->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @foreach($mentors as $mentor)
                 <a href="{{ route('mentor.profile', $mentor) }}" class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition flex flex-col overflow-hidden border border-gray-100">
                    <div class="relative h-48 bg-gray-200 flex items-center justify-center overflow-hidden">
                         {{-- Placeholder for mentor cover image if available --}}
                         <img src="{{ $mentor->profile_image ? Storage::url($mentor->profile_image) : 'https://i.pravatar.cc/150?u='.$mentor->id }}" class="w-full h-full object-cover object-center" alt="{{ $mentor->name }}">
                    </div>
                    <div class="p-5 text-center">
                        <h3 class="font-bold text-[#181818] text-lg mb-1">{{ $mentor->name }}</h3>
                        <p class="text-gray-500 text-sm">{{ $mentor->instructor_title ?? 'Instructor' }}</p>
                        <div class="flex items-center justify-center gap-4 mt-4 text-gray-600 text-sm">
                            <span><i class="fa-solid fa-users mr-1 text-pink-500"></i>{{ $mentor->enrolled_students_count ?? '0' }} Students</span>
                            <span><i class="fa-solid fa-book mr-1 text-blue-500"></i>{{ $mentor->courses_count }} Courses</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <p class="text-gray-600 text-center">No mentors found.</p>
    @endif
</div>
@endsection 