@extends('layouts.app')

@section('content')
<div x-data="demoVideoModal()">
<!-- Course Hero/Header -->
<div class="relative w-full h-[320px] md:h-[340px] flex items-end justify-start overflow-hidden mb-10">
    <img src="{{ $course->preview_image ? (Str::startsWith($course->preview_image, 'http') ? $course->preview_image : asset('storage/'.$course->preview_image)) : 'https://images.unsplash.com/photo-1513258496099-48168024aec0?auto=format&fit=crop&w=1200&q=80' }}" class="absolute inset-0 w-full h-full object-cover object-center z-0" alt="{{ $course->title }}">
    <div class="absolute inset-0 bg-black bg-opacity-70 z-10"></div>
    <div class="relative z-20 w-full max-w-7xl mx-auto px-6 py-10 flex flex-col md:flex-row md:items-end md:justify-between">
        <div class="flex-1 min-w-0">
            <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-2">{{ $course->title }}</h1>
            <div class="text-lg text-gray-200 mb-4">{{ Str::limit(strip_tags($course->description), 120) }}</div>
            <div class="flex flex-wrap items-center gap-4 text-white text-sm mb-4">
                <span class="flex items-center gap-2"><i class="fa-solid fa-book-open text-pink-400"></i> {{ $course->modules->sum(fn($m) => $m->contents->count()) }}+ Lesson</span>
                <span class="flex items-center gap-2"><i class="fa-solid fa-clock text-yellow-300"></i> 9hr 30min</span>
                <span class="flex items-center gap-2"><i class="fa-solid fa-users text-orange-300"></i> 32 students enrolled</span>
                @if($course->category)
                    <span class="bg-yellow-400 text-black font-semibold px-3 py-1 rounded-full text-xs">{{ $course->category->name }}</span>
                @endif
            </div>
            <div class="flex items-center gap-4 mt-2">
                <div class="flex items-center gap-2">
                    <img src="{{ $course->user && $course->user->profile_image ? Storage::url($course->user->profile_image) : 'https://i.pravatar.cc/120' }}" class="w-10 h-10 rounded-full object-cover border-2 border-white shadow" alt="Instructor">
                    <div>
                        <div class="font-semibold text-white leading-tight">{{ $course->user->name ?? 'Instructor' }}</div>
                        <div class="text-xs text-gray-300">Instructor</div>
                    </div>
                </div>
                <div class="flex items-center gap-1 ml-6">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fa-solid fa-star {{ $i <= 4 ? 'text-yellow-400' : 'text-gray-400' }}"></i>
                    @endfor
                    <span class="ml-2 text-white font-semibold">4.0</span>
                    <span class="text-gray-300">(15)</span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="max-w-7xl mx-auto py-10 px-4 flex flex-col lg:flex-row gap-8">
    <!-- Left/Main -->
    <div class="flex-1 min-w-0">
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 hidden">
            <img src="{{ $course->preview_image ? (Str::startsWith($course->preview_image, 'http') ? $course->preview_image : asset('storage/'.$course->preview_image)) : 'https://images.unsplash.com/photo-1513258496099-48168024aec0?auto=format&fit=crop&w=600&q=80' }}" class="w-full h-72 object-cover rounded-xl mb-6" alt="{{ $course->title }}">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $course->title }}</h1>
            <div class="flex items-center gap-2 text-yellow-500 text-base mb-2">
                <i class="fa-solid fa-star"></i>
                <span>4.4</span>
                <span class="text-gray-400">(180 Reviews)</span>
            </div>
            <div class="flex items-center gap-3 mb-4">
                <img src="{{ $course->user && $course->user->profile_image ? Storage::url($course->user->profile_image) : 'https://i.pravatar.cc/120' }}" class="w-9 h-9 rounded-full object-cover border-2 border-white shadow" alt="Instructor">
                <span class="font-semibold text-gray-700">{{ $course->user->name ?? 'Instructor' }}</span>
                <span class="ml-auto px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-700 font-semibold">{{ $course->category->name ?? 'General' }}</span>
            </div>
        </div>
        <!-- Overview -->
        <div class="bg-white rounded-2xl shadow p-6 mb-8">
            <h2 class="text-xl font-bold mb-4">Overview</h2>
            <div class="mb-4">
                <h3 class="font-semibold mb-1">Course Description</h3>
                <div class="text-gray-700">{!! nl2br(e($course->description)) !!}</div>
            </div>
        </div>
        <!-- Demo Content -->
        @if($course->demoVideos->count())
        <div class="bg-white rounded-2xl shadow p-6 mb-8">
            <h2 class="text-xl font-bold mb-4">Demo Content</h2>
            <div class="space-y-4">
                @foreach($course->demoVideos as $demo)
                    <div class="flex items-center gap-4 p-4 border rounded-lg bg-gray-50">
                        <i class="fa-solid fa-video text-blue-500 text-xl"></i>
                        <div class="flex-1">
                            <div class="font-semibold text-gray-800">{{ $demo->title }}</div>
                        </div>
                        <span @click="open('{{ $demo->type }}', '{{ $demo->video_url ?? '' }}', '{{ $demo->file_path ? asset('storage/'.$demo->file_path) : '' }}')" class="cursor-pointer px-3 py-2 text-white rounded-full hover:bg-gray-200 transition flex items-center justify-center">
                            <!-- Custom Play SVG -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M18.7 8.97989L4.14 17.7099C4.05 17.3799 4 17.0299 4 16.6699V7.32989C4 4.24989 7.33 2.32989 10 3.86989L14.04 6.19989L18.09 8.53989C18.31 8.66989 18.52 8.80989 18.7 8.97989Z" fill="#392C7D"/>
                                <path opacity="0.4" d="M18.0902 15.4598L14.0402 17.7998L10.0002 20.1298C8.09022 21.2298 5.84021 20.5698 4.72021 18.9598L5.14021 18.7098L19.5802 10.0498C20.5802 11.8498 20.0902 14.3098 18.0902 15.4598Z" fill="#392C7D"/>
                            </svg>
                        </span>
                        <span class="inline-block px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold ml-2">{{ ucfirst($demo->type) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
        <!-- Curriculum -->
        <div class="bg-white rounded-2xl shadow p-6 mb-8">
            <h2 class="text-xl font-bold mb-4">Course Content <span class="text-xs font-normal text-gray-400 ml-2">{{ $course->modules->sum(fn($m) => $m->contents->count()) }} Lectures</span></h2>
            <div id="accordion-curriculum">
                @foreach($course->modules as $module)
                    <div class="mb-2 border rounded-lg overflow-hidden">
                        <button type="button" class="w-full flex justify-between items-center px-4 py-3 bg-gray-50 hover:bg-gray-100 font-semibold text-left focus:outline-none" onclick="document.getElementById('module-{{ $module->id }}').classList.toggle('hidden')">
                            <span>{{ $module->title }}</span>
                            <i class="fa fa-chevron-down ml-2"></i>
                        </button>
                        <div id="module-{{ $module->id }}" class="hidden bg-white">
                            @forelse($module->contents as $content)
                                <div class="flex items-center justify-between px-6 py-3 border-t">
                                    <div class="flex items-center gap-2">
                                        @if($content->type === 'video')
                                            <i class="fa-solid fa-play text-pink-500"></i>
                                        @elseif($content->type === 'quiz')
                                            <i class="fa-solid fa-question-circle text-yellow-500"></i>
                                        @elseif($content->type === 'file')
                                            <i class="fa-solid fa-file-alt text-green-500"></i>
                                        @else
                                            <i class="fa-solid fa-circle text-gray-400"></i>
                                        @endif
                                        <span class="font-medium text-gray-800">{{ $content->title }}</span>
                                        <span class="ml-2 text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-500">{{ ucfirst($content->type) }}</span>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <span class="text-xs text-gray-400 hidden">02:53</span>
                                    </div>
                                </div>
                            @empty
                                <div class="px-6 py-3 text-gray-400">No content</div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <!-- Instructor -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 flex flex-col md:flex-row gap-8 items-start">
            <div class="flex-shrink-0">
                <img src="{{ $course->user && $course->user->profile_image ? Storage::url($course->user->profile_image) : 'https://i.pravatar.cc/120' }}" class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg" alt="Instructor">
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-2xl font-bold text-gray-900">{{ $course->user->name ?? 'Instructor' }}</span>
                    <span class="ml-2 px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-700 font-semibold">Instructor</span>
                </div>
                @if($course->user->bio)
                    <div class="text-gray-600 mb-2">{{ $course->user->bio }}</div>
                @endif
                @if($course->user->education && is_array($course->user->education) && count($course->user->education))
                    <div class="mb-2">
                        <span class="font-semibold">Education:</span>
                        <ul class="list-disc pl-6 text-gray-700">
                            @foreach($course->user->education as $edu)
                                <li>{{ is_array($edu) ? ($edu['degree'] ?? $edu['institute'] ?? '') : $edu }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if($course->user->experience && is_array($course->user->experience) && count($course->user->experience))
                    <div class="mb-2">
                        <span class="font-semibold">Experience:</span>
                        <ul class="list-disc pl-6 text-gray-700">
                            @foreach($course->user->experience as $exp)
                                <li>{{ is_array($exp) ? ($exp['title'] ?? $exp['company'] ?? '') : $exp }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if($course->user->skills && is_array($course->user->skills) && count($course->user->skills))
                    <div class="mb-2">
                        <span class="font-semibold">Skills:</span>
                        <div class="flex flex-wrap gap-2 mt-1">
                            @foreach($course->user->skills as $skill)
                                @php
                                    $skillName = is_array($skill) ? ($skill['name'] ?? null) : (is_string($skill) ? $skill : null);
                                    $skillRating = is_array($skill) && isset($skill['rating']) ? floatval($skill['rating']) : null;
                                @endphp
                                @if($skillName)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 text-blue-700 font-semibold text-xs shadow-sm">
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
    <!-- Right/Sidebar -->
    <aside class="w-full lg:w-80 flex-shrink-0">
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-6 sticky top-8 border border-gray-100">
            <div class="flex items-center gap-2 mb-4">
                @if($course->is_free)
                    <span class="text-green-600 font-bold text-2xl">FREE</span>
                @elseif(isset($course->price))
                    <span class="text-[#F85A7E] font-bold text-2xl">${{ $course->price }}</span>
                @endif
                @if(!$course->is_free && isset($course->price) && $course->price < 200)
                    <span class="text-gray-400 line-through text-lg">$200.00</span>
                    <span class="text-xs bg-pink-100 text-pink-600 font-bold px-2 py-1 rounded">{{ round(100 - ($course->price/200)*100) }}% off</span>
                @endif
            </div>
            <div class="flex gap-2 mb-6">
                <button class="flex-1 px-4 py-2 bg-gray-100 rounded-lg text-gray-700 font-semibold hover:bg-gray-200"><i class="fa-regular fa-heart"></i> Wishlist</button>
                <button class="flex-1 px-4 py-2 bg-gray-100 rounded-lg text-gray-700 font-semibold hover:bg-gray-200"><i class="fa-solid fa-share"></i> Share</button>
            </div>
            <button class="w-full bg-[#392C7D] text-white font-bold rounded-lg py-3 text-lg shadow hover:bg-[#2D2363] transition mb-8">Enroll Now</button>
            <div class="mb-6">
                <h4 class="font-semibold mb-3 flex items-center gap-2"><i class="fa-solid fa-list-ul text-blue-400"></i> Includes</h4>
                <ul class="text-sm text-gray-700 space-y-1">
                    @if($course->modules->sum(fn($m) => $m->contents->count()))<li><i class="fa-solid fa-play-circle text-pink-400 mr-2"></i>{{ $course->modules->sum(fn($m) => $m->contents->count()) }} lectures</li>@endif
                    <li><i class="fa-solid fa-infinity text-green-400 mr-2"></i>Full lifetime access</li>
                    <li><i class="fa-solid fa-mobile-alt text-blue-400 mr-2"></i>Access on mobile and TV</li>
                    <li><i class="fa-solid fa-tasks text-yellow-400 mr-2"></i>Assignments</li>
                    <li><i class="fa-solid fa-certificate text-purple-400 mr-2"></i>Certificate of Completion</li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-3 flex items-center gap-2"><i class="fa-solid fa-star text-yellow-400"></i> Course Features</h4>
                <ul class="text-sm text-gray-700 space-y-1">
                    @if(isset($course->enrolled_count))<li><i class="fa-solid fa-users text-blue-400 mr-2"></i>Enrolled: {{ $course->enrolled_count }} students</li>@endif
                    @if(isset($course->duration))<li><i class="fa-solid fa-clock text-green-400 mr-2"></i>Duration: {{ $course->duration }}</li>@endif
                    @if($course->modules->count())<li><i class="fa-solid fa-layer-group text-purple-400 mr-2"></i>Chapters: {{ $course->modules->count() }}</li>@endif
                    @if(isset($course->video_hours))<li><i class="fa-solid fa-video text-pink-400 mr-2"></i>Video: {{ $course->video_hours }}</li>@endif
                    @if(isset($course->level))<li><i class="fa-solid fa-signal text-yellow-400 mr-2"></i>Level: {{ $course->level }}</li>@endif
                </ul>
            </div>
        </div>
    </aside>
</div>
@if($relatedCourses->count())
<div class="max-w-7xl mx-auto px-4 mt-16">
    <h2 class="text-2xl font-bold mb-6">Related Courses</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
        @foreach($relatedCourses as $course)
            @include('components.course-card', [
                'image' => $course->preview_image ? (Str::startsWith($course->preview_image, 'http') ? $course->preview_image : asset('storage/'.$course->preview_image)) : 'https://images.unsplash.com/photo-1513258496099-48168024aec0?auto=format&fit=crop&w=600&q=80',
                'discount' => $course->is_free ? 'Free' : null,
                'instructor_avatar' => $course->user && $course->user->profile_image ? Storage::url($course->user->profile_image) : 'https://i.pravatar.cc/120',
                'instructor' => $course->user->name ?? 'Instructor',
                'category' => $course->category->name ?? 'General',
                'title' => $course->title,
                'rating' => '4.4',
                'reviews' => '180',
                'price' => $course->price ?? '0',
                'url' => route('courses.show', $course),
            ])
        @endforeach
    </div>
</div>
@endif
@if($authorCourses->count())
<div class="max-w-7xl mx-auto px-4 mt-16">
    <h2 class="text-2xl font-bold mb-6">More from this Instructor</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
        @foreach($authorCourses as $course)
            @include('components.course-card', [
                'image' => $course->preview_image ? (Str::startsWith($course->preview_image, 'http') ? $course->preview_image : asset('storage/'.$course->preview_image)) : 'https://images.unsplash.com/photo-1513258496099-48168024aec0?auto=format&fit=crop&w=600&q=80',
                'discount' => $course->is_free ? 'Free' : null,
                'instructor_avatar' => $course->user && $course->user->profile_image ? Storage::url($course->user->profile_image) : 'https://i.pravatar.cc/120',
                'instructor' => $course->user->name ?? 'Instructor',
                'category' => $course->category->name ?? 'General',
                'title' => $course->title,
                'rating' => '4.4',
                'reviews' => '180',
                'price' => $course->price ?? '0',
                'url' => route('courses.show', $course),
            ])
        @endforeach
    </div>
</div>
@endif
<!-- Demo Video Modal -->
<div x-show="show" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl relative overflow-hidden">
        <button @click="close()" type="button" class="absolute top-2 right-2 text-gray-500 hover:text-pink-500 text-2xl z-[999]">&times;</button>
        <template x-if="type === 'youtube' && url">
            <iframe :src="youtubeEmbedUrl(url)" class="w-full h-80 rounded-b-xl" frameborder="0" allowfullscreen></iframe>
        </template>
        <template x-if="type === 'hosted' && file">
            <video :src="file" controls class="w-full h-80 rounded-b-xl"></video>
        </template>
    </div>
</div>
<script>
function demoVideoModal() {
    return {
        show: false,
        type: '',
        url: '',
        file: '',
        open(type, url, file) {
            this.type = type;
            this.url = url;
            this.file = file;
            this.show = true;
        },
        close() {
            this.show = false;
            this.type = '';
            this.url = '';
            this.file = '';
        },
        youtubeEmbedUrl(url) {
            if (!url) return '';
            // Robust YouTube ID extraction
            let id = '';
            if (url.includes('youtu.be/')) {
                id = url.split('youtu.be/')[1].split(/[?&]/)[0];
            } else if (url.includes('youtube.com/watch')) {
                const params = new URLSearchParams(url.split('?')[1]);
                id = params.get('v');
            } else if (url.includes('youtube.com/embed/')) {
                id = url.split('embed/')[1].split(/[?&]/)[0];
            }
            return id ? `https://www.youtube.com/embed/${id}` : '';
        }
    }
}
</script>
@endsection 