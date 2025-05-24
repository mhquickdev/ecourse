<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
    @forelse($courses as $course)
        @include('components.course-card', [
            'image' => $course->preview_image ? (Str::startsWith($course->preview_image, 'http') ? $course->preview_image : asset('storage/'.$course->preview_image)) : 'https://images.unsplash.com/photo-1513258496099-48168024aec0?auto=format&fit=crop&w=600&q=80',
            'discount' => $course->is_free ? 'Free' : null,
            'instructor_avatar' => $course->user && $course->user->profile_image ? Storage::url($course->user->profile_image) : 'https://i.pravatar.cc/120',
            'instructor' => $course->user->name ?? 'Instructor',
            'category' => $course->category->name ?? 'General',
            'title' => $course->title,
            'rating' => '4.4', // static for now
            'reviews' => '180', // static for now
            'price' => $course->price ?? '0',
            'url' => route('courses.show', $course),
        ])
    @empty
        <div class="col-span-full text-center text-gray-400 py-12">No courses found.</div>
    @endforelse
</div>
<div class="mt-8 flex justify-center" id="course-pagination">
    {{ $courses->links('vendor.pagination.tailwind') }}
</div> 