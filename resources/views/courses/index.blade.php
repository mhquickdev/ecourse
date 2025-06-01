@extends('layouts.app')

@section('content')
<div class="relative min-h-screen overflow-x-hidden">
    <!-- Blurry Animated Circles Background -->
    <div class="absolute -top-2 -left-32 w-96 h-96 bg-pink-100 rounded-full blur-3xl opacity-70 z-0"></div>
    <div class="absolute top-1/2 right-0 w-80 h-80 bg-blue-100 rounded-full blur-2xl opacity-50 z-0"></div>
    <div class="bg-gradient-to-b from-pink-100 to-white py-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-4xl font-extrabold text-gray-900 mb-1">Courses</h1>
                    <nav class="text-sm text-gray-500"><a href="/" class="hover:underline">Home</a> <span class="mx-2">/</span> Courses</nav>
                </div>
            </div>
            <div x-data="{ showFilters: false }" class="flex flex-col md:flex-row gap-8 relative">
                <!-- Mobile Filter Toggle -->
                <div class="md:hidden mb-4">
                    <button type="button" @click="showFilters = true" class="px-4 py-2 bg-pink-500 text-white rounded-lg font-semibold w-full">Show Filters</button>
                </div>
                <!-- Mobile Filter Drawer & Backdrop -->
                <div x-show="showFilters" class="fixed inset-0 z-40 flex md:hidden" style="display: none;">
                    <div @click="showFilters = false" class="fixed inset-0 bg-black bg-opacity-40 transition-opacity"></div>
                    <aside class="relative w-80 max-w-full bg-white shadow-xl h-full p-6 overflow-y-auto z-50 transform transition-transform duration-300" x-show="showFilters" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-lg flex items-center gap-2"><i class="fa-solid fa-filter"></i> Filters</h3>
                            <button type="button" @click="showFilters = false" class="text-gray-500 hover:text-pink-500 text-2xl">&times;</button>
                        </div>
                        <form id="course-filters-mobile">
                            @include('courses.partials.filters', ['categories' => $categories, 'instructors' => $instructors])
                        </form>
                    </aside>
                </div>
                <!-- Desktop Filter Sidebar -->
                <aside class="hidden md:block w-64 flex-shrink-0">
                    <div class="sticky top-24">
                        <form id="course-filters" class="bg-white rounded-2xl shadow p-6 mb-6">
                            @include('courses.partials.filters', ['categories' => $categories, 'instructors' => $instructors])
                        </form>
                    </div>
                </aside>
                <!-- Main Content -->
                <main class="flex-1">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
                        <div class="text-gray-500 text-sm" id="course-results-count">Showing {{ $courses->firstItem() }}-{{ $courses->lastItem() }} of {{ $courses->total() }} results</div>
                        <div class="flex items-center gap-2">
                            <select id="sort-select" name="sort" class="ml-2 pr-5 border border-gray-200 rounded-lg px-3 py-2 text-sm">
                                <option value="">Newly Published</option>
                                <option value="price_asc">Price: Low to High</option>
                                <option value="price_desc">Price: High to Low</option>
                            </select>
                            <input type="text" id="search-input" name="search" class="ml-2 border border-gray-200 rounded-lg px-3 py-2 text-sm w-48" placeholder="Search">
                        </div>
                    </div>
                    <div id="course-grid">
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
                                    'course' => $course,
                                    'isWishlisted' => Auth::check() && Auth::user()->isStudent() ? Auth::user()->wishlistedCourses->contains($course->id) : false
                                ])
                            @empty
                                <div class="col-span-full text-center text-gray-400 py-12">No courses found.</div>
                            @endforelse
                        </div>
                        <div class="mt-8 flex justify-center" id="course-pagination">
                            {{ $courses->links('vendor.pagination.tailwind') }}
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
</div>
<script>
// AJAX search/filter/sort for courses
function fetchCourses(page = 1) {
    const form = document.getElementById('course-filters');
    const params = new URLSearchParams(new FormData(form));
    params.append('sort', document.getElementById('sort-select').value);
    params.append('search', document.getElementById('search-input').value);
    params.append('page', page);
    params.append('ajax', 1);

    fetch(`{{ route('courses.index') }}?${params.toString()}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
        }
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('course-grid').innerHTML = data.html;
        document.getElementById('course-results-count').innerText = data.count_text;
        // Re-attach pagination click events
        document.querySelectorAll('#course-pagination a').forEach(a => {
            a.addEventListener('click', function(e) {
                e.preventDefault();
                const url = new URL(a.href);
                const page = url.searchParams.get('page') || 1;
                fetchCourses(page);
            });
        });
    });
}
// Listen to filter, sort, and search changes
['change', 'input'].forEach(evt => {
    document.getElementById('course-filters').addEventListener(evt, () => fetchCourses());
    document.getElementById('sort-select').addEventListener(evt, () => fetchCourses());
    document.getElementById('search-input').addEventListener(evt, () => fetchCourses());
});
// Initial pagination click events
setTimeout(() => {
    document.querySelectorAll('#course-pagination a').forEach(a => {
        a.addEventListener('click', function(e) {
            e.preventDefault();
            const url = new URL(a.href);
            const page = url.searchParams.get('page') || 1;
            fetchCourses(page);
        });
    });
}, 100);
</script>
@endsection 