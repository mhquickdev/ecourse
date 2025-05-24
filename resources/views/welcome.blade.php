@extends('layouts.app')

@section('content')
<!-- Hero & Counters: Gradient BG -->
<div class="bg-gradient-to-br from-[#fff7f7] via-[#f3f8fd] to-[#e6eaff] min-h-screen">
    <!-- Hero Section -->
    <section class="relative max-w-7xl mx-auto px-4 sm:px-8 pt-32 pb-16 flex flex-col md:flex-row items-center gap-12 overflow-visible">
        <!-- Left: Text -->
        <div class="flex-1 z-10">
            <span class="text-sm font-bold text-[#6E6B7B] uppercase tracking-widest">THE LEADER IN ONLINE LEARNING</span>
            <h1 class="mt-3 text-4xl md:text-5xl font-extrabold text-[#2D2363] leading-tight">
                Engaging & Accessible <span class="text-[#F85A7E]">Online Courses</span> For All
            </h1>
            <p class="mt-4 text-base text-gray-600 max-w-xl">Our specialized online courses are designed to bring the classroom experience to you, no matter where you are.</p>
          
            <!-- Trust/Rating -->
            <div class="mt-10 flex flex-col sm:flex-row items-center gap-6">
                <span class="font-semibold text-gray-700 text-lg">Trusted by over <span class="text-[#2D2363] font-extrabold">15K Users</span> worldwide since 2022</span>
                <span class="flex items-center gap-2 text-4xl font-bold text-[#2D2363]">
                    1000+ <span class="ml-2 text-[#F85A7E]">4.4</span>
                    <span class="flex text-yellow-400 text-xl ml-1">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star-half-stroke"></i>
                            </span>
                        </span>
            </div>
        </div>
        <!-- Right: Hero Image & Shapes -->
        <div class="flex-1 flex justify-center items-center relative z-10 min-w-[320px] hidden lg:block">
            <img src="https://dreamslms.dreamstechnologies.com/html/template/assets/img/hero/hero-2.png" alt="Hero" class="w-full max-w-lg object-contain drop-shadow-2xl select-none pointer-events-none">
            
        </div>
        <!-- Background Blurs/Gradients -->
        <div class="absolute -top-2 -left-32 w-96 h-96 bg-pink-100 rounded-full blur-3xl opacity-70 z-0"></div>
        <div class="absolute top-1/2 right-0 w-80 h-80 bg-blue-100 rounded-full blur-2xl opacity-50 z-0"></div>
    </section>

    <!-- Counters Section -->
    <section class="max-w-5xl mx-auto px-4 sm:px-8 pb-20 -mt-10 z-20 relative">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
            <!-- Counter 1 -->
            <div class="bg-white rounded-2xl shadow-xl p-6 flex flex-col items-center text-center hover:shadow-2xl transition group">
                <div class="relative flex items-center justify-center mb-4">
                    <span class="absolute inset-0 flex items-center justify-center">
                        <svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="50" cy="50" r="50" fill="#FF5757" fill-opacity="0.1"></circle>
                          </svg>
                    </span>
                    <img src="https://dreamslms.dreamstechnologies.com/html/template/assets/img/icons/icon-1.svg" class="w-12 h-12 z-10" alt="Online Courses Icon">
                </div>
                <span class="text-4xl font-extrabold text-[#181818] counter" x-data="{count: 0}" x-intersect.once="let i = setInterval(() => { if(count < 4){ count++ } else { count=4; clearInterval(i) } }, 150)" x-text="count+'K'">0K</span>
                <span class="text-gray-500 text-sm">Online Courses</span>
            </div>
            <!-- Counter 2 -->
            <div class="bg-white rounded-2xl shadow-xl p-6 flex flex-col items-center text-center hover:shadow-2xl transition group">
                <div class="relative flex items-center justify-center mb-4">
                    <span class="absolute inset-0 flex items-center justify-center">
                        <svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="50" cy="50" r="50" fill="#8E54E9" fill-opacity="0.1"></circle>
                          </svg>
                    </span>
                    <img src="https://dreamslms.dreamstechnologies.com/html/template/assets/img/icons/icon-2.svg" class="w-12 h-12 z-10" alt="Expert Tutors Icon">
                </div>
                <span class="text-4xl font-extrabold text-[#181818] counter" x-data="{count: 0}" x-intersect.once="let i = setInterval(() => { if(count < 90){ count++ } else { count=90; clearInterval(i) } }, 20)" x-text="count+'K'">0K</span>
                <span class="text-gray-500 text-sm">Expert Tutors</span>
            </div>
            <!-- Counter 3 -->
            <div class="bg-white rounded-2xl shadow-xl p-6 flex flex-col items-center text-center hover:shadow-2xl transition group">
                <div class="relative flex items-center justify-center mb-4">
                    <span class="absolute inset-0 flex items-center justify-center">
                        <svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="50" cy="50" r="50" fill="#E91E63" fill-opacity="0.1"></circle>
                          </svg>
                    </span>
                    <img src="https://dreamslms.dreamstechnologies.com/html/template/assets/img/icons/icon-3.svg" class="w-12 h-12 z-10" alt="Certified Courses Icon">
                </div>
                <span class="text-4xl font-extrabold text-[#181818] counter" x-data="{count: 0}" x-intersect.once="let i = setInterval(() => { if(count < 2){ count++ } else { count=2; clearInterval(i) } }, 300)" x-text="count+'K'">0K</span>
                <span class="text-gray-500 text-sm">Certified Courses</span>
            </div>
            <!-- Counter 4 -->
            <div class="bg-white rounded-2xl shadow-xl p-6 flex flex-col items-center text-center hover:shadow-2xl transition group">
                <div class="relative flex items-center justify-center mb-4">
                    <span class="absolute inset-0 flex items-center justify-center">
                        <svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="50" cy="50" r="50" fill="#00BCD4" fill-opacity="0.1"></circle>
                          </svg>
                    </span>
                    <img src="https://dreamslms.dreamstechnologies.com/html/template/assets/img/icons/icon-4.svg" class="w-12 h-12 z-10" alt="Online Students Icon">
                </div>
                <span class="text-4xl font-extrabold text-[#181818] counter" x-data="{count: 0}" x-intersect.once="let i = setInterval(() => { if(count < 27){ count++ } else { count=27; clearInterval(i) } }, 50)" x-text="count+'K'">0K</span>
                <span class="text-gray-500 text-sm">Online Students</span>
            </div>
        </div>
    </section>
</div>

<!-- Top Category & Featured Courses: Light Blue BG -->
<div class="bg-[#f8fafd]">
    <!-- Top Category -->
    <section class="max-w-7xl mx-auto px-4 sm:px-8 py-16">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-10 gap-4">
            <div>
                <span class="text-[#F85A7E] font-bold text-base mb-1 block">Favourite Course</span>
                <h2 class="text-4xl font-extrabold text-[#181818] leading-tight">Top Category</h2>
            </div>
            <a href="#" class="bg-[#F85A7E] text-white font-semibold rounded-full px-8 py-3 text-base shadow hover:bg-[#e13a5e] transition self-start sm:self-auto">View all Categories</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
            <!-- Angular -->
            <div class="bg-white border border-[#eee] rounded-2xl p-8 flex flex-col items-center text-center shadow-sm hover:bg-[#392C7D] transition group">
                <div class="relative flex items-center justify-center mb-6">
                    <span class="absolute inset-0 flex items-center justify-center">
                        <svg width="116" height="116" viewBox="0 0 116 116" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle opacity="0.08" cx="58" cy="58" r="58" fill="#F85A7E"/>
                            <circle opacity="0.08" cx="58" cy="58" r="46" fill="#F85A7E"/>
                            <circle opacity="0.08" cx="58" cy="58" r="36" fill="#F85A7E"/>
                                </svg>
                        </span>
                    <img src="https://dreamslms.dreamstechnologies.com/html/template/assets/img/category/icons/icon-2.svg" class="w-16 h-16 z-10" alt="Angular">
                </div>
                <span class="font-extrabold text-lg text-[#181818] mb-1 group-hover:text-white transition">Angular Development</span>
                <span class="text-gray-400 text-base group-hover:text-white transition">40 Instructors</span>
            </div>
            <!-- Docker -->
            <div class="bg-white border border-[#eee] rounded-2xl p-8 flex flex-col items-center text-center shadow-sm hover:bg-[#392C7D] transition group">
                <div class="relative flex items-center justify-center mb-6">
                    <span class="absolute inset-0 flex items-center justify-center">
                        <svg width="116" height="116" viewBox="0 0 116 116" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle opacity="0.08" cx="58" cy="58" r="58" fill="#F85A7E"/>
                            <circle opacity="0.08" cx="58" cy="58" r="46" fill="#F85A7E"/>
                            <circle opacity="0.08" cx="58" cy="58" r="36" fill="#F85A7E"/>
                        </svg>
                            </span>
                    <img src="https://dreamslms.dreamstechnologies.com/html/template/assets/img/category/icons/icon-3.svg" class="w-16 h-16 z-10" alt="Docker">
                </div>
                <span class="font-extrabold text-lg text-[#181818] mb-1 group-hover:text-white transition">Docker Development</span>
                <span class="text-gray-400 text-base group-hover:text-white transition">45 Instructors</span>
            </div>
            <!-- Node JS -->
            <div class="bg-white border border-[#eee] rounded-2xl p-8 flex flex-col items-center text-center shadow-sm hover:bg-[#392C7D] transition group">
                <div class="relative flex items-center justify-center mb-6">
                    <span class="absolute inset-0 flex items-center justify-center">
                        <svg width="116" height="116" viewBox="0 0 116 116" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle opacity="0.08" cx="58" cy="58" r="58" fill="#F85A7E"/>
                            <circle opacity="0.08" cx="58" cy="58" r="46" fill="#F85A7E"/>
                            <circle opacity="0.08" cx="58" cy="58" r="36" fill="#F85A7E"/>
                                </svg>
                        </span>
                    <img src="https://dreamslms.dreamstechnologies.com/html/template/assets/img/category/icons/icon-4.svg" class="w-16 h-16 z-10" alt="Node JS">
                </div>
                <span class="font-extrabold text-lg text-[#181818] mb-1 group-hover:text-white transition">Node JS Frontend</span>
                <span class="text-gray-400 text-base group-hover:text-white transition">40 Instructors</span>
            </div>
            <!-- Swift -->
            <div class="bg-white border border-[#eee] rounded-2xl p-8 flex flex-col items-center text-center shadow-sm hover:bg-[#392C7D] transition group">
                <div class="relative flex items-center justify-center mb-6">
                    <span class="absolute inset-0 flex items-center justify-center">
                        <svg width="116" height="116" viewBox="0 0 116 116" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle opacity="0.08" cx="58" cy="58" r="58" fill="#F85A7E"/>
                            <circle opacity="0.08" cx="58" cy="58" r="46" fill="#F85A7E"/>
                            <circle opacity="0.08" cx="58" cy="58" r="36" fill="#F85A7E"/>
                    </svg>
                    </span>
                    <img src="https://dreamslms.dreamstechnologies.com/html/template/assets/img/category/icons/icon-5.svg" class="w-16 h-16 z-10" alt="Swift">
                </div>
                <span class="font-extrabold text-lg text-[#181818] mb-1 group-hover:text-white transition">Swift Development</span>
                <span class="text-gray-400 text-base group-hover:text-white transition">23 Instructors</span>
            </div>
        </div>
    </section>

    <!-- Featured Courses (dynamic, fallback to static if empty) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-8 py-12">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
            <div>
                <span class="text-[#F85A7E] font-bold text-base mb-1 block">What's New</span>
                <h2 class="text-4xl font-extrabold text-[#181818] leading-tight">Featured Courses</h2>
            </div>
            <a href="{{ route('courses.index') }}" class="bg-[#F85A7E] text-white font-semibold rounded-full px-8 py-2 text-base shadow hover:bg-[#e13a5e] transition self-start sm:self-auto">View all Courses</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            @forelse($courses as $course)
                <div class="bg-white border border-[#eee] rounded-2xl shadow-sm hover:shadow-lg transition flex flex-col overflow-hidden">
                    <div class="relative">
                        <img src="{{ $course->preview_image ? (Str::startsWith($course->preview_image, 'http') ? $course->preview_image : asset('storage/'.$course->preview_image)) : 'https://images.unsplash.com/photo-1513258496099-48168024aec0?auto=format&fit=crop&w=600&q=80' }}" class="w-full h-48 object-cover" alt="{{ $course->title }}">
                        @if($course->is_free)
                            <span class="absolute top-4 left-4 bg-[#22C55E] text-white font-bold px-4 py-1 rounded-full text-sm">Free</span>
                        @else
                            <span class="absolute top-4 left-4 bg-[#F85A7E] text-white font-bold px-4 py-1 rounded-full text-sm">${{ number_format($course->price, 2) }}</span>
                        @endif
                        <button class="absolute top-4 right-4 bg-white/80 rounded-full p-2 shadow hover:bg-[#F85A7E] hover:text-white transition"><i class="fa-regular fa-heart"></i></button>
                    </div>
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex items-center gap-3 mb-3">
                            <img src="{{ $course->user && $course->user->profile_image ? Storage::url($course->user->profile_image) : 'https://i.pravatar.cc/120' }}" class="w-10 h-10 rounded-full object-cover" alt="Instructor">
                            <div class="text-left">
                                <div class="font-semibold text-base text-[#181818] leading-tight">{{ $course->user->name ?? 'Instructor' }}</div>
                                <div class="text-sm text-gray-400">Instructor</div>
                            </div>
                        </div>
                        <div class="font-bold text-[#181818] text-lg mb-2 line-clamp-2">{{ $course->title }}</div>
                        <div class="flex items-center gap-4 text-sm text-gray-500 mb-4">
                            <span><i class="fa-regular fa-file-lines mr-1 text-[#F85A7E]"></i>12+ Lesson</span>
                            <span><i class="fa-regular fa-clock mr-1 text-[#392C7D]"></i>9hr 30min</span>
                        </div>
                        <div class="flex items-center justify-between mt-auto">
                            <div class="flex items-center gap-1 text-[#FBBF24] text-base">
                                <i class="fa-solid fa-star"></i>
                                <span>4.5</span>
                                <span class="text-gray-400">(15)</span>
                            </div>
                            <a href="{{ route('courses.show', $course->id)}}" class="bg-[#392C7D] text-white font-semibold rounded-full px-6 py-2 text-sm shadow hover:bg-[#2D2363] transition">Buy Now</a>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Fallback static cards if no courses -->
                @for($i = 0; $i < 3; $i++)
                <div class="bg-white border border-[#eee] rounded-2xl shadow-sm hover:shadow-lg transition flex flex-col overflow-hidden">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1513258496099-48168024aec0?auto=format&fit=crop&w=600&q=80" class="w-full h-48 object-cover" alt="Course">
                        <span class="absolute top-4 left-4 bg-[#F85A7E] text-white font-bold px-4 py-1 rounded-full text-sm">$200 <span class="line-through text-xs text-white/70 ml-1">$900.00</span></span>
                        <button class="absolute top-4 right-4 bg-white/80 rounded-full p-2 shadow hover:bg-[#F85A7E] hover:text-white transition"><i class="fa-regular fa-heart"></i></button>
                    </div>
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex items-center gap-3 mb-3">
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" class="w-10 h-10 rounded-full object-cover" alt="Instructor">
                            <div class="text-left">
                                <div class="font-semibold text-base text-[#181818] leading-tight">Nicole Brown</div>
                                <div class="text-sm text-gray-400">Instructor</div>
                            </div>
                        </div>
                        <div class="font-bold text-[#181818] text-lg mb-2 line-clamp-2">Information About UI/UX Design Degree</div>
                        <div class="flex items-center gap-4 text-sm text-gray-500 mb-4">
                            <span><i class="fa-regular fa-file-lines mr-1 text-[#F85A7E]"></i>12+ Lesson</span>
                            <span><i class="fa-regular fa-clock mr-1 text-[#392C7D]"></i>9hr 30min</span>
                        </div>
                        <div class="flex items-center justify-between mt-auto">
                            <div class="flex items-center gap-1 text-[#FBBF24] text-base">
                                <i class="fa-solid fa-star"></i>
                                <span>4.0</span>
                                <span class="text-gray-400">(15)</span>
                            </div>
                            <a href="#" class="bg-[#392C7D] text-white font-semibold rounded-full px-6 py-2 text-sm shadow hover:bg-[#2D2363] transition">Buy Now</a>
                        </div>
                    </div>
                </div>
                @endfor
            @endforelse
        </div>
    </section>
</div>

<!-- Master the skills & Featured Instructor: Soft Purple BG -->
<div class="bg-[#f7f3ff]">
    <!-- Master the skills Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-8 py-16 flex flex-col md:flex-row items-center gap-10 relative overflow-hidden">
        <!-- Background Shapes -->
        <div class="absolute inset-0 pointer-events-none z-0">
             <!-- Add abstract background shapes here -->
        </div>
        <div class="flex-1 z-10">
            <span class="text-[#F85A7E] font-bold text-base mb-1 block">What's New</span>
            <h2 class="text-4xl font-extrabold text-[#181818] leading-tight mb-4">Master the skills to drive your career</h2>
            <p class="text-gray-600 text-base mb-8">Get certified, master modern tech skills, and level up your career — whether you're starting out or a seasoned pro. 95% of eLearning learners report our hands-on content directly helped their careers.</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Feature 1 -->
                <div class="bg-white rounded-xl shadow-sm p-6 flex items-start gap-4">
                    <div class="flex-shrink-0 w-14 h-14 rounded-full bg-[#FEE6EC] flex items-center justify-center">
                        <i class="fa-solid fa-heart text-[#F85A7E] text-xl"></i> <!-- Placeholder icon -->
                    </div>
                    <div>
                        <span class="font-bold text-[#181818] block mb-1 text-lg">Stay motivated with engaging instructors</span>
                        <!-- <span class="text-gray-500 text-sm">Short description here</span> -->
                    </div>
                </div>
                <!-- Feature 2 -->
                <div class="bg-white rounded-xl shadow-sm p-6 flex items-start gap-4">
                    <div class="flex-shrink-0 w-14 h-14 rounded-full bg-[#FEE6EC] flex items-center justify-center">
                        <i class="fa-solid fa-cloud text-[#F85A7E] text-xl"></i> <!-- Placeholder icon -->
                    </div>
                    <div>
                        <span class="font-bold text-[#181818] block mb-1 text-lg">Keep up with in the latest in cloud</span>
                        <!-- <span class="text-gray-500 text-sm">Short description here</span> -->
                    </div>
                </div>
                <!-- Feature 3 -->
                <div class="bg-white rounded-xl shadow-sm p-6 flex items-start gap-4">
                    <div class="flex-shrink-0 w-14 h-14 rounded-full bg-[#FEE6EC] flex items-center justify-center">
                        <i class="fa-solid fa-certificate text-[#F85A7E] text-xl"></i> <!-- Placeholder icon -->
                    </div>
                    <div>
                        <span class="font-bold text-[#181818] block mb-1 text-lg">Get certified with 100+ certification courses</span>
                        <!-- <span class="text-gray-500 text-sm">Short description here</span> -->
                    </div>
                </div>
                <!-- Feature 4 -->
                <div class="bg-white rounded-xl shadow-sm p-6 flex items-start gap-4">
                    <div class="flex-shrink-0 w-14 h-14 rounded-full bg-[#FEE6EC] flex items-center justify-center">
                        <i class="fa-solid fa-book text-[#F85A7E] text-xl"></i> <!-- Placeholder icon -->
                    </div>
                    <div>
                        <span class="font-bold text-[#181818] block mb-1 text-lg">Build skills your way, from labs to courses</span>
                        <!-- <span class="text-gray-500 text-sm">Short description here</span> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="flex-1 flex justify-center items-center relative z-10">
            <img src="https://dreamslms.dreamstechnologies.com/html/template/assets/img/feature/feature-16.png" alt="Master Skills Hero" class="w-full max-w-lg object-contain drop-shadow-2xl select-none pointer-events-none">
             <!-- Add abstract background shapes here -->
             <div class="absolute -top-10 -right-10 w-48 h-48 bg-pink-100 rounded-full mix-blend-multiply filter blur-xl opacity-80 z-0"></div>
             <div class="absolute bottom-10 left-10 w-40 h-40 bg-blue-100 rounded-full mix-blend-multiply filter blur-xl opacity-60 z-0 animation-delay-2000"></div>
        </div>
    </section>

    <!-- Featured Instructor -->
    <section class="max-w-7xl mx-auto px-4 sm:px-8 py-16">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-10 gap-4">
            <div>
                <span class="text-[#F85A7E] font-bold text-base mb-1 block">Featured Instructor</span>
                <h2 class="text-4xl font-extrabold text-[#181818] leading-tight">Learn from the best mentors</h2>
            </div>
             <!-- Optional: Add a View All button here if needed based on design -->
             <!-- <a href="#" class="bg-[#F85A7E] text-white font-semibold rounded-full px-8 py-3 text-base shadow hover:bg-[#e13a5e] transition self-start sm:self-auto">View all Instructors</a> -->
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
            @forelse($mentors as $mentor)
            <!-- Instructor Card -->
            <div class="bg-white border border-[#eee] rounded-2xl shadow-sm hover:shadow-lg transition flex flex-col overflow-hidden">
                <div class="relative">
                    <img src="{{ $mentor->profile_image ? Storage::url($mentor->profile_image) : 'https://i.pravatar.cc/400?img=' . ($loop->index + 1) }}" class="w-full h-64 object-cover" alt="{{ $mentor->name }}">
                    <span class="absolute top-4 left-4 bg-green-500 text-white rounded-full p-1"><i class="fa-solid fa-check text-xs"></i></span>
                    <span class="absolute top-4 right-4 bg-black/50 text-white text-xs font-semibold px-3 py-1 rounded-full">{{ $mentor->courses()->count() }} Courses</span>
                    <button class="absolute bottom-4 right-4 bg-white/80 rounded-full p-2 shadow hover:bg-[#F85A7E] hover:text-white transition"><i class="fa-regular fa-heart"></i></button>
                </div>
                <div class="p-5 flex flex-col items-center text-center">
                    <span class="font-extrabold text-lg text-[#181818]">{{ $mentor->name }}</span>
                    <span class="text-gray-500 text-sm mb-3">Instructor Title</span> {{-- Static Placeholder --}}
                    <div class="flex items-center gap-4 text-gray-500 text-xs">
                        <span><i class="fa-solid fa-book-open text-[#F85A7E] mr-1"></i>50 Students</span> {{-- Static Placeholder --}}
                        <span><i class="fa-solid fa-graduation-cap text-[#392C7D] mr-1"></i>{{ $mentor->courses()->count() }} Courses</span>
                    </div>
                </div>
            </div>
            @empty
            <!-- Fallback static cards if no mentors -->
             @for($i = 0; $i < 4; $i++)
             <div class="bg-white border border-[#eee] rounded-2xl shadow-sm hover:shadow-lg transition flex flex-col overflow-hidden">
                 <div class="relative">
                     <img src="https://images.unsplash.com/photo-1511367461989?auto=format&fit=crop&w=400&q=80" class="w-full h-64 object-cover" alt="Instructor">
                     <span class="absolute top-4 left-4 bg-green-500 text-white rounded-full p-1"><i class="fa-solid fa-check text-xs"></i></span>
                     <span class="absolute top-4 right-4 bg-black/50 text-white text-xs font-semibold px-3 py-1 rounded-full">20 Courses</span>
                     <button class="absolute bottom-4 right-4 bg-white/80 rounded-full p-2 shadow hover:bg-[#F85A7E] hover:text-white transition"><i class="fa-regular fa-heart"></i></button>
                 </div>
                 <div class="p-5 flex flex-col items-center text-center">
                     <span class="font-extrabold text-lg text-[#181818]">David Lee</span>
                     <span class="text-gray-500 text-sm mb-3">Web Developer</span>
                     <div class="flex items-center gap-4 text-gray-500 text-xs">
                         <span><i class="fa-solid fa-book-open text-[#F85A7E] mr-1"></i>50 Students</span>
                         <span><i class="fa-solid fa-graduation-cap text-[#392C7D] mr-1"></i>20 Courses</span>
                     </div>
                 </div>
             </div>
             @endfor
            @endforelse
        </div>
    </section>
</div>

<!-- Trusted By & Mentor CTA: White BG -->
<div class="bg-white">
    <!-- Trusted By Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-8 py-12">
        <h2 class="text-center text-xl font-extrabold text-[#2D2363] mb-8">Trusted by 500+ Leading Universities And Companies</h2>
        <div class="flex flex-wrap justify-center gap-10">
            <img src="https://upload.wikimedia.org/wikipedia/commons/4/44/Microsoft_logo.svg" class="h-10 grayscale hover:grayscale-0 transition" alt="Brand 1">
            <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/Google.png" class="h-10 grayscale hover:grayscale-0 transition" alt="Brand 2">
            <img src="https://upload.wikimedia.org/wikipedia/commons/4/4a/Logo_2013_Google.png" class="h-10 grayscale hover:grayscale-0 transition" alt="Brand 3">
            <img src="https://upload.wikimedia.org/wikipedia/commons/0/08/Netflix_2015_logo.svg" class="h-10 grayscale hover:grayscale-0 transition" alt="Brand 4">
            <img src="https://upload.wikimedia.org/wikipedia/commons/6/6e/Amazon_logo.svg" class="h-10 grayscale hover:grayscale-0 transition" alt="Brand 5">
        </div>
    </section>
</div>

<!-- Mentor CTA: Light Blue/Purple BG -->
<div class="bg-gradient-to-br from-[#f3f8fd] to-[#e6eaff] py-20">
    <section class="max-w-7xl mx-auto px-4 sm:px-8 flex flex-col md:flex-row items-center gap-16 relative overflow-hidden">
        <!-- Background Shapes -->
        <div class="absolute inset-0 pointer-events-none z-0">
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-pink-100 rounded-full mix-blend-multiply filter blur-xl opacity-70"></div>
            <div class="absolute bottom-20 left-20 w-48 h-48 bg-blue-100 rounded-full mix-blend-multiply filter blur-xl opacity-60"></div>
        </div>

        <!-- Left: Illustration -->
        <div class="flex-1 flex justify-center items-center relative z-10">
            <img src="https://dreamslms.dreamstechnologies.com/html/template/assets/img/feature/feature-15.svg" alt="Join as Mentor Illustration" class="w-full max-w-lg object-contain drop-shadow-2xl">
        </div>

        <!-- Right: Text and Button -->
        <div class="flex-1 z-10">
            <span class="text-[#F85A7E] font-bold text-base mb-2 block">Become an Instructor</span>
            <h2 class="text-4xl font-extrabold text-[#181818] leading-tight mb-6">Share Your Knowledge & Earn Money</h2>
            <p class="text-gray-600 text-base mb-8 max-w-xl">Join our community of expert instructors and share your knowledge with students worldwide. Create engaging courses, build your brand, and earn while making a difference.</p>
            
            <!-- Features Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-10">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-[#FEE6EC] flex items-center justify-center">
                        <i class="fa-solid fa-graduation-cap text-[#F85A7E] text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-[#181818] text-lg mb-1">Expert Guidance</h3>
                        <p class="text-gray-600 text-sm">Get support from our team of experts to create high-quality courses</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-[#FEE6EC] flex items-center justify-center">
                        <i class="fa-solid fa-chart-line text-[#F85A7E] text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-[#181818] text-lg mb-1">Growth Potential</h3>
                        <p class="text-gray-600 text-sm">Scale your reach and income with our growing student base</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-[#FEE6EC] flex items-center justify-center">
                        <i class="fa-solid fa-tools text-[#F85A7E] text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-[#181818] text-lg mb-1">Powerful Tools</h3>
                        <p class="text-gray-600 text-sm">Access our comprehensive course creation and management tools</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-[#FEE6EC] flex items-center justify-center">
                        <i class="fa-solid fa-users text-[#F85A7E] text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-[#181818] text-lg mb-1">Community Support</h3>
                        <p class="text-gray-600 text-sm">Join a community of passionate educators and learners</p>
                    </div>
                </div>
            </div>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4">
                @if(!auth()->check())
                <a href="{{ route('register') }}" class="bg-[#F85A7E] text-white px-8 py-4 rounded-xl font-semibold hover:bg-[#e13a5e] transition shadow-lg text-lg flex items-center justify-center gap-2">
                    <i class="fa-solid fa-user-plus"></i>
                    Become an Instructor
                </a>
                @elseif (auth()->user()->role_id == 3)
                <a href="{{ route('mentor.dashboard') }}" class="bg-[#F85A7E] text-white px-8 py-4 rounded-xl font-semibold hover:bg-[#e13a5e] transition shadow-lg text-lg flex items-center justify-center gap-2">
                    <i class="fa-solid fa-user-pen"></i>
                    Author Dashboard
                </a>
                @endif
                <a href="#" class="bg-white text-[#2D2363] px-8 py-4 rounded-xl font-semibold hover:bg-gray-50 transition shadow-lg text-lg flex items-center justify-center gap-2 border border-gray-200">
                    <i class="fa-solid fa-info-circle"></i>
                    Learn More
                </a>
                
            </div>
        </div>
    </section>
</div>
@endsection
