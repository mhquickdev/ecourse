@extends('layouts.mentor')

@section('content')
    <div class="p-6 flex flex-col lg:flex-row gap-8 bg-gray-100 min-h-screen" x-data="editCourseFormAlpine({{ $course->modules->toJson() }}, {{ $course->demoVideos->toJson() }}, '{{ csrf_token() }}')" x-init="init();"
        @beforeunload.window="if(hasUnsavedChanges) return 'You have unsaved changes. Are you sure you want to leave?'"
        x-on:keydown.ctrl.s.prevent="submitForm"
        x-on:keydown.meta.s.prevent="submitForm">
        <form action="{{ route('mentor.courses.update', $course->id) }}" method="POST" enctype="multipart/form-data"
            class="flex flex-col lg:flex-row gap-8 w-full relative"
            @submit.prevent="submitForm">
                    @csrf
            @method('PUT')
            <!-- Progress Bar -->
            <div x-show="isSaving" class="fixed top-0 left-0 w-full h-1 bg-blue-200 z-50">
                <div class="h-full bg-blue-600 transition-all duration-300" :style="'width: ' + saveProgress + '%'"></div>
            </div>
            <!-- Unsaved Changes Warning -->
            <div x-show="hasUnsavedChanges" class="fixed top-4 right-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded shadow-lg z-50">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <p>You have unsaved changes</p>
        </div>
            </div>
            <!-- Left Sticky Sidebar -->
            <div class="w-full lg:w-1/3 xl:w-1/4 lg:sticky lg:top-0 lg:h-screen lg:overflow-y-auto bg-white rounded-2xl shadow-lg p-8 space-y-8 flex-shrink-0 border border-gray-200">
                <h2 class="text-3xl font-extrabold text-blue-700 mb-8 tracking-tight">Edit Course Info</h2>
                <div class="space-y-4">
                        <div>
                        <label for="title" class="block text-base font-semibold text-gray-700 mb-1">Course Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $course->title) }}" required
                            class="w-full px-5 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg">
                        </div>
                        <div>
                        <label for="preview_image" class="block text-base font-semibold text-gray-700 mb-1">Preview Image</label>
                            <input type="file" name="preview_image" id="preview_image" accept="image/*"
                            class="w-full px-5 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @if($course->preview_image)
                            <img src="{{ asset('storage/' . $course->preview_image) }}" alt="Preview Image" class="mt-3 w-40 h-28 object-cover rounded-xl border border-gray-200 shadow">
                            @endif
                        </div>
                        <div>
                        <label for="description" class="block text-base font-semibold text-gray-700 mb-1">Course Description</label>
                        <textarea name="description" id="description" rows="6" required
                            class="w-full px-5 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg">{{ old('description', $course->description) }}</textarea>
                        </div>
                        <div>
                        <label for="category_id" class="block text-base font-semibold text-gray-700 mb-1">Category</label>
                            <select name="category_id" id="category_id" required
                            class="w-full px-5 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg">
                            <option value="">Select a category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @if($course->category_id == $category->id) selected @endif>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                            <label for="price" class="block text-base font-semibold text-gray-700 mb-1">Price</label>
                                <input type="number" name="price" id="price" step="0.01" min="0" value="{{ old('price', $course->price) }}"
                                class="w-full px-5 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg">
                            </div>
                        <div class="flex items-center mt-7">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_free" class="form-checkbox h-5 w-5 text-blue-600 rounded-xl" @if($course->is_free) checked @endif>
                                <span class="ml-3 text-gray-700 text-base">Make this course free</span>
                                </label>
                            </div>
                        </div>
                        <div>
                        <label for="tags" class="block text-base font-semibold text-gray-700 mb-1">Tags (comma separated)</label>
                        <input type="text" name="tags" id="tags" value="{{ old('tags', $course->tags->pluck('name')->implode(', ')) }}"
                            class="w-full px-5 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg"
                                placeholder="e.g. web development, javascript, php">
                        </div>
                        <div>
                        <label for="status" class="block text-base font-semibold text-gray-700 mb-1">Course Status</label>
                            <select name="status" id="status" required
                                class="w-full px-5 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg">
                            <option value="draft" @if($course->status == 'draft') selected @endif>Draft</option>
                            <option value="published" @if($course->status == 'published') selected @endif>Published</option>
                            <option value="archived" @if($course->status == 'archived') selected @endif>Archived</option>
                            </select>
                        </div>
                </div>
                <div class="flex justify-end pt-4">
                    <button type="submit"
                        class="px-8 py-3 bg-blue-600 text-white rounded-xl shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-lg font-semibold transition">
                                Save Changes
                            </button>
                </div>
            </div>
            <!-- Main Content Area -->
            <div class="w-full lg:w-2/3 xl:w-3/4 space-y-8">
                <!-- Demo Videos -->
                <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-200 transform transition-all duration-300 hover:shadow-xl">
                    <div class="flex items-center justify-between mb-4">
                        <label class="text-xl font-bold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-video text-blue-600"></i>
                            Demo Videos
                        </label>
                        <button type="button" class="px-4 py-2 bg-blue-100 text-blue-700 rounded-lg font-semibold hover:bg-blue-200 transition-all duration-300 transform hover:scale-105" @click="addNewDemoVideo">
                            <i class="fas fa-plus mr-2"></i> Add Demo Video
                        </button>
                    </div>
                    <div class="space-y-3">
                        <template x-for="(video, vIdx) in existingDemoVideos" :key="'existing-' + vIdx">
                            <div class="flex flex-col md:flex-row gap-2 md:gap-4 items-start md:items-center bg-gray-50 p-3 rounded-xl border border-gray-200 transform transition-all duration-300 hover:shadow-md">
                                <span class="font-semibold text-gray-700" x-text="video.title"></span>
                                <template x-if="video.type === 'youtube'">
                                    <div class="flex items-center gap-2">
                                        <span class="text-blue-600 flex items-center gap-1">
                                            <i class="fab fa-youtube"></i> YouTube
                                        </span>
                                        <a :href="video.video_url" target="_blank" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors duration-300">
                                            <i class="fas fa-play mr-1"></i> Watch
                                        </a>
                                    </div>
                                </template>
                                <template x-if="video.type === 'hosted'">
                                    <div class="flex items-center gap-2">
                                        <a :href="video.file_path ? '/storage/' + video.file_path : '#'" target="_blank" class="text-blue-600 underline flex items-center gap-1">
                                            <i class="fas fa-file-video"></i> Download File
                                        </a>
                                        <button type="button" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors duration-300" @click="openVideoPopup('/storage/' + video.file_path)">
                                            <i class="fas fa-play mr-1"></i> Watch
                                        </button>
                                    </div>
                                </template>
                                <span class="inline-block px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold ml-2" x-text="video.type.charAt(0).toUpperCase() + video.type.slice(1)"></span>
                                <button type="button" class="ml-auto text-red-600 hover:text-red-800 transition-colors duration-300" @click="confirmDelete('demoVideo', vIdx)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </template>
                        <template x-for="(video, vIdx) in newDemoVideos" :key="'new-' + vIdx">
                            <div class="flex flex-col md:flex-row gap-2 md:gap-4 items-start md:items-center bg-white p-3 rounded-xl border border-blue-200">
                                <input type="text" :name="`new_demo_videos[${vIdx}][title]`" x-model="video.title" placeholder="Video Title" class="w-full md:flex-1 px-4 py-2 border border-gray-300 rounded-lg">
                                <template x-if="video.type === 'youtube'">
                                    <input type="text" :name="`new_demo_videos[${vIdx}][url]`" x-model="video.url" placeholder="Video URL" class="w-full md:flex-1 px-4 py-2 border border-gray-300 rounded-lg">
                                </template>
                                <template x-if="video.type === 'hosted'">
                                    <input type="file" :name="`new_demo_videos[${vIdx}][file]`" class="w-full md:flex-1 px-4 py-2 border border-gray-300 rounded-lg">
                                </template>
                                <select :name="`new_demo_videos[${vIdx}][type]`" x-model="video.type" class="w-full md:w-auto px-4 py-2 border border-gray-300 rounded-lg">
                                    <option value="youtube">YouTube</option>
                                    <option value="hosted">Hosted</option>
                                </select>
                                <button type="button" class="text-red-600 hover:text-red-800 ml-0 md:ml-2" @click="removeNewDemoVideo(vIdx)"><i class="fas fa-trash"></i></button>
                            </div>
                        </template>
                    </div>
                </div>
                <!-- Course Modules -->
                <div class="space-y-6 relative pb-20">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-xl font-bold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-book text-blue-600"></i>
                            Course Modules
                        </label>
                    </div>
                    <!-- Existing Modules -->
                    @foreach ($course->modules as $mIdx => $module)
                        <div class="bg-white rounded-2xl shadow border border-gray-200 mb-4 transform transition-all duration-300 hover:shadow-lg">
                            <div class="flex items-center justify-between px-6 py-4 cursor-pointer group" @click="document.getElementById('module-{{ $mIdx }}').classList.toggle('hidden')">
                                <div class="flex items-center gap-3">
                                    <span class="text-lg font-bold text-blue-700">Module {{ $mIdx + 1 }}</span>
                                    <span class="text-gray-700 font-semibold">{{ $module->title ?? 'Untitled' }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" class="text-red-600 hover:text-red-800 transition-colors duration-300" @click.stop="confirmDelete('module', {{ $mIdx }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <i class="fas fa-chevron-down text-gray-400 group-hover:text-blue-600 transition-transform duration-300"></i>
                                </div>
                            </div>
                            <div id="module-{{ $mIdx }}" class="px-6 pb-6 space-y-4 hidden">
                                <input type="text" value="{{ $module->title }}" readonly class="w-full px-4 py-2 border border-gray-200 bg-gray-100 rounded-lg mb-2">
                                <textarea readonly class="w-full px-4 py-2 border border-gray-200 bg-gray-100 rounded-lg mb-2">{{ $module->description }}</textarea>
                                <div class="space-y-4">
                                    @foreach ($module->contents as $cIdx => $content)
                                        <div class="bg-gray-50 rounded-xl border border-gray-200 p-4">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="font-semibold text-gray-700">Content {{ $cIdx + 1 }}</span>
                                                <span class="inline-block px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold ml-2">{{ ucfirst($content->type) }}</span>
                                                <button type="button" class="text-red-600 hover:text-red-800 transition-colors duration-300 hidden" @click="confirmDelete('content', {{ $mIdx }}, {{ $cIdx }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                                <div class="space-y-4">
                                                <input type="text" value="{{ $content->title }}" readonly class="w-full px-4 py-2 border border-gray-200 bg-gray-100 rounded-lg">
                                                <input type="text" value="{{ ucfirst($content->type) }}" readonly class="w-full px-4 py-2 border border-gray-200 bg-gray-100 rounded-lg">
                                                @if($content->type === 'video')
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-600 mb-1">Video Source</label>
                                                        <div class="space-y-2">
                                                            @php
                                                                $videoUrls = $content->video_urls ? json_decode($content->video_urls, true) : [];
                                                                $videoFiles = $content->video_files ? json_decode($content->video_files, true) : [];
                                                            @endphp
                                                            @if((is_array($videoUrls) && count($videoUrls)) || (is_array($videoFiles) && count($videoFiles)))
                                                                @foreach($videoUrls as $url)
                                                                    <div class="flex items-center gap-2 mb-1 bg-gray-100 rounded px-2 py-1">
                                                                        <span class="text-xs text-gray-700 flex-1 truncate">YouTube</span>
                                                                        <a href="{{ $url }}" target="_blank" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors duration-300 text-sm">
                                                                            <i class="fas fa-play mr-1"></i> Watch
                                                                        </a>
                                                                    </div>
                                                                @endforeach
                                                                @foreach($videoFiles as $file)
                                                                    <div class="flex items-center gap-2 mb-1 bg-gray-100 rounded px-2 py-1">
                                                                        <span class="text-xs text-gray-700 flex-1 truncate">Hosted File</span>
                                                                        <button type="button" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors duration-300 text-sm" @click="openVideoPopup('{{ asset('storage/' . $file) }}')">
                                                                            <i class="fas fa-play mr-1"></i> Watch
                                                                        </button>
                                                                        <a href="{{ asset('storage/' . $file) }}" target="_blank" class="text-blue-600 underline text-xs">Download</a>
                                                                    </div>
                                                                @endforeach
                                                            @else
                                                                <div class="text-gray-500 text-sm mb-2">No video source available.</div>
                                                            @endif
                                                        </div>
                                                        @if($content->resources)
                                                            <div class="mt-2">
                                                                <label class="block text-xs font-medium text-gray-600 mb-1">Resources</label>
                                                                @foreach(json_decode($content->resources, true) as $resource)
                                                                    @if($resource['type'] === 'url')
                                                                        <a href="{{ $resource['url'] }}" target="_blank" class="text-blue-600 underline block mb-2">Download Resource File</a>
                                                                    @elseif($resource['type'] === 'file')
                                                                        <a href="{{ asset('storage/' . $resource['file']) }}" target="_blank" class="text-blue-600 underline block mb-2">Download Resource File</a>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                @elseif($content->type === 'file')
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-600 mb-1">File URLs</label>
                                                        @if($content->file_urls)
                                                            @foreach(json_decode($content->file_urls, true) as $url)
                                                                <a href="{{ $url }}" target="_blank" class="text-blue-600 underline block mb-2">Download File</a>
                                                            @endforeach
                                                        @endif
                                                        <label class="block text-xs font-medium text-gray-600 mb-1 mt-2">File Uploads</label>
                                                        @if($content->file_files)
                                                            @foreach(json_decode($content->file_files, true) as $file)
                                                                <a href="{{ asset('storage/' . $file) }}" target="_blank" class="text-blue-600 underline block mb-2">Download File</a>
                                                            @endforeach
                                                    @endif
                                                </div>
                                                @elseif($content->type === 'quiz')
                                                    <div class="quiz-fields space-y-2">
                                                        <input type="text" value="{{ $content->quiz_question }}" readonly class="w-full px-4 py-2 border border-gray-200 bg-gray-100 rounded-lg mb-2">
                                                        @if($content->quiz_options)
                                                            @foreach(json_decode($content->quiz_options, true) as $option)
                                                                <div class="flex gap-2 mb-2 quiz-option-row">
                                                                    <input type="text" value="{{ $option }}" readonly class="flex-1 px-4 py-2 border border-gray-200 bg-gray-100 rounded-lg">
                                                                    @if($content->quiz_answer === $option)
                                                                        <span class="text-green-600 font-bold">Correct</span>
                                                                    @endif
                                            </div>
                                        @endforeach
                                                        @endif
                                    </div>
                                                @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
                    @endforeach
                    <!-- New Modules -->
                    <template x-for="(module, mIdx) in newModules" :key="'new-' + mIdx">
                        <div class="bg-white rounded-2xl shadow border border-blue-200 mb-4 transform transition-all duration-300 hover:shadow-lg">
                            <div class="flex items-center justify-between px-6 py-4 cursor-pointer group" @click="module.open = !module.open">
                                <div class="flex items-center gap-3">
                                    <span class="text-lg font-bold text-blue-700">Module <span x-text="mIdx + 1"></span></span>
                                    <span class="text-gray-700 font-semibold" x-text="module.title || 'Untitled'"></span>
            </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" class="text-red-600 hover:text-red-800" @click.stop="removeNewModule(mIdx)"><i class="fas fa-trash"></i></button>
                                    <i :class="module.open ? 'fas fa-chevron-up text-blue-600' : 'fas fa-chevron-down text-gray-400'" class="transition"></i>
    </div>
</div>
                            <div x-show="module.open" class="px-6 pb-6 space-y-4">
                                <input type="text" :name="`new_modules[${mIdx}][title]`" x-model="module.title" placeholder="Module Title" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <textarea :name="`new_modules[${mIdx}][description]`" x-model="module.description" placeholder="Module Description" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
            <div class="space-y-4">
                                    <template x-for="(content, cIdx) in module.contents" :key="cIdx">
                                        <div class="bg-gray-50 rounded-xl border border-blue-200 p-4">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="font-semibold text-gray-700">Content <span x-text="cIdx + 1"></span></span>
                                                <span class="inline-block px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold ml-2" x-text="content.type.charAt(0).toUpperCase() + content.type.slice(1)"></span>
                                                <button type="button" class="text-red-600 hover:text-red-800" @click="removeNewContent(mIdx, cIdx)"><i class="fas fa-trash"></i></button>
                        </div>
                        <div class="space-y-4">
                                                <input type="text" :name="`new_modules[${mIdx}][contents][${cIdx}][title]`" x-model="content.title" placeholder="Content Title" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                <select :name="`new_modules[${mIdx}][contents][${cIdx}][type]`" x-model="content.type" required class="content-type w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="video">Video</option>
                                <option value="quiz">Quiz</option>
                                <option value="file">File</option>
                                                </select>
                                                <!-- Video: URL or File, plus multiple Resources -->
                                                <template x-if="content.type === 'video'">
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-600 mb-1">Video Source</label>
                                                        <select x-model="content.videoSource" class="mb-2 px-2 py-1 border rounded">
                                                            <option value="url">Video URL</option>
                                                            <option value="file">Video File</option>
                                                        </select>
                                                        <template x-if="content.videoSource === 'url'">
                                                            <template x-for="(url, vUrlIdx) in content.videoUrls" :key="vUrlIdx">
                                                                <div class="flex items-center gap-2 mb-2">
                                                                    <input type="text" :name="`new_modules[${mIdx}][contents][${cIdx}][video_urls][${vUrlIdx}]`" x-model="content.videoUrls[vUrlIdx]" placeholder="Video URL" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                                    <a :href="content.videoUrls[vUrlIdx]" target="_blank" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors duration-300" x-show="content.videoUrls[vUrlIdx]">
                                                                        <i class="fas fa-play mr-1"></i> Watch
                                                                    </a>
                                                                    <button type="button" class="text-red-600 hover:text-red-800" @click="removeVideoUrl(mIdx, cIdx, vUrlIdx)"><i class="fas fa-minus"></i></button>
                                                                </div>
                                                            </template>
                                                            <button type="button" class="text-sm text-blue-600 hover:text-blue-800 mb-2" @click="addVideoUrl(mIdx, cIdx)">+ Add Video URL</button>
                                                        </template>
                                                        <template x-if="content.videoSource === 'file'">
                                                            <template x-for="(file, vFileIdx) in content.videoFiles" :key="vFileIdx">
                                                                <div class="flex items-center gap-2 mb-2">
                                                                    <input type="file" :name="`new_modules[${mIdx}][contents][${cIdx}][video_files][${vFileIdx}]`" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                                    <button type="button" class="text-red-600 hover:text-red-800" @click="removeVideoFile(mIdx, cIdx, vFileIdx)"><i class="fas fa-minus"></i></button>
                                                                </div>
                                                            </template>
                                                            <button type="button" class="text-sm text-blue-600 hover:text-blue-800 mb-2" @click="addVideoFile(mIdx, cIdx)">+ Add Video File</button>
                                                        </template>
                                                        <!-- Multiple Resources for Video -->
                                                        <div class="mt-4">
                                                            <label class="block text-xs font-medium text-gray-600 mb-1">Resources (optional)</label>
                                                            <template x-for="(resource, rIdx) in content.resources" :key="rIdx">
                                                                <div class="flex items-center gap-2 mb-2">
                                                                    <select x-model="resource.type" class="px-2 py-1 border rounded">
                                                                        <option value="url">URL</option>
                                                                        <option value="file">File</option>
                                                                    </select>
                                                                    <template x-if="resource.type === 'url'">
                                                                        <input type="text" :name="`new_modules[${mIdx}][contents][${cIdx}][resources][${rIdx}][url]`" x-model="resource.url" placeholder="Resource URL" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                                    </template>
                                                                    <template x-if="resource.type === 'file'">
                                                                        <input type="file" :name="`new_modules[${mIdx}][contents][${cIdx}][resources][${rIdx}][file]`" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                                    </template>
                                                                    <button type="button" class="text-red-600 hover:text-red-800" @click="removeVideoResource(mIdx, cIdx, rIdx)"><i class="fas fa-minus"></i></button>
                                                                </div>
                                                            </template>
                                                            <button type="button" class="text-sm text-blue-600 hover:text-blue-800 mb-2" @click="addVideoResource(mIdx, cIdx)">+ Add Resource</button>
                                                        </div>
                                                    </div>
                                                </template>
                                                <!-- File (multiple URLs and uploads) -->
                                                <template x-if="content.type === 'file'">
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-600 mb-1">File URLs</label>
                                                        <template x-for="(url, fUrlIdx) in content.fileUrls" :key="fUrlIdx">
                                                            <div class="flex items-center gap-2 mb-2">
                                                                <input type="text" :name="`new_modules[${mIdx}][contents][${cIdx}][file_urls][${fUrlIdx}]`" x-model="content.fileUrls[fUrlIdx]" placeholder="File URL" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                                <button type="button" class="text-red-600 hover:text-red-800" @click="removeFileUrl(mIdx, cIdx, fUrlIdx)"><i class="fas fa-minus"></i></button>
                                                            </div>
                                                        </template>
                                                        <button type="button" class="text-sm text-blue-600 hover:text-blue-800 mb-2" @click="addFileUrl(mIdx, cIdx)">+ Add File URL</button>
                                                        <label class="block text-xs font-medium text-gray-600 mb-1 mt-2">File Uploads</label>
                                                        <template x-for="(file, fFileIdx) in content.fileFiles" :key="fFileIdx">
                                                            <div class="flex items-center gap-2 mb-2">
                                                                <input type="file" :name="`new_modules[${mIdx}][contents][${cIdx}][file_files][${fFileIdx}]`" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                                <button type="button" class="text-red-600 hover:text-red-800" @click="removeFileFile(mIdx, cIdx, fFileIdx)"><i class="fas fa-minus"></i></button>
                                                            </div>
                                                        </template>
                                                        <button type="button" class="text-sm text-blue-600 hover:text-blue-800 mb-2" @click="addFileFile(mIdx, cIdx)">+ Add File Upload</button>
                                                    </div>
                                                </template>
                                                <!-- Quiz -->
                                                <template x-if="content.type === 'quiz'">
                                                    <div class="quiz-fields space-y-2">
                                                        <input type="text" :name="`new_modules[${mIdx}][contents][${cIdx}][quiz_question]`" x-model="content.quiz_question" placeholder="Quiz Question" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 mb-2">
                                                        <template x-for="(option, oIdx) in content.quiz_options" :key="option.id">
                                                            <div class="flex gap-2 mb-2 quiz-option-row">
                                                                <input type="text" :name="`new_modules[${mIdx}][contents][${cIdx}][quiz_options][${oIdx}][value]`" x-model="option.value" placeholder="Option" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                                <input type="hidden" :name="`new_modules[${mIdx}][contents][${cIdx}][quiz_options][${oIdx}][id]`" :value="option.id">
                                                                <input type="radio" :name="`new_modules[${mIdx}][contents][${cIdx}][quiz_answer_id]`" :value="option.id" x-model="content.quiz_answer_id">
                                                                <button type="button" class="text-red-600 hover:text-red-800 ml-2" @click="removeQuizOption(mIdx, cIdx, oIdx)">Remove</button>
                                                            </div>
                                                        </template>
                                                        <!-- Hidden input for the selected answer value -->
                                                        <input type="hidden" :name="`new_modules[${mIdx}][contents][${cIdx}][quiz_answer]`" :value="content.quiz_options.find(opt => opt.id === content.quiz_answer_id)?.value || ''">
                                                        <button type="button" class="text-sm text-blue-600 hover:text-blue-800 mb-2" @click="addQuizOption(mIdx, cIdx)">+ Add Option</button>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                    <button type="button" class="add-content px-4 py-2 bg-blue-100 text-blue-700 rounded-lg font-semibold hover:bg-blue-200 transition" @click="addNewContent(mIdx)">
                                        <i class="fas fa-plus mr-2"></i> Add Content
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                <!-- Floating Add Module Button -->
                <div class="fixed bottom-8 right-8 z-50">
                    <button type="button" 
                        class="flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700 transition-all duration-300 transform hover:scale-105 hover:shadow-xl"
                        @click="addNewModule()">
                        <i class="fas fa-plus"></i>
                        <span>Add Module</span>
                    </button>
                </div>
            </div>
            <!-- Delete Confirmation Modal -->
            <div x-show="showDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" x-cloak>
                <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                    <div class="mt-3 text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Confirm Delete</h3>
                        <div class="mt-2 px-7 py-3">
                            <p class="text-sm text-gray-500" x-text="deleteModalMessage"></p>
                        </div>
                        <div class="items-center px-4 py-3">
                            <button type="button" @click.prevent="confirmDeleteAction" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                                Delete
                            </button>
                            <button type="button" @click.prevent="showDeleteModal = false" class="ml-3 px-4 py-2 bg-gray-100 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    function editCourseFormAlpine(existingModules, existingDemoVideos, csrfToken) {
        return {
            // Demo Videos
            existingDemoVideos: existingDemoVideos.map(v => ({...v})),
            newDemoVideos: [],
            addNewDemoVideo() {
                this.newDemoVideos.push({ title: '', url: '', type: 'youtube', file: null });
            },
            removeExistingDemoVideo(idx) {
                this.existingDemoVideos.splice(idx, 1);
            },
            removeNewDemoVideo(idx) {
                this.newDemoVideos.splice(idx, 1);
            },
            // Modules/Contents
            existingModules: existingModules.map(m => ({...m, contents: m.contents || []})),
            newModules: [],
            addNewModule() {
                this.newModules.push({ 
                    title: '', 
                    description: '', 
                    open: true, 
                    contents: [this.newContent()] 
                });
            },
            removeExistingModule(mIdx) {
                this.existingModules.splice(mIdx, 1);
            },
            removeNewModule(mIdx) {
                this.newModules.splice(mIdx, 1);
            },
            addNewContent(mIdx) {
                this.newModules[mIdx].contents.push(this.newContent());
            },
            removeExistingContent(mIdx, cIdx) {
                this.existingModules[mIdx].contents.splice(cIdx, 1);
            },
            removeNewContent(mIdx, cIdx) {
                this.newModules[mIdx].contents.splice(cIdx, 1);
            },
            addVideoUrl(mIdx, cIdx) {
                this.newModules[mIdx].contents[cIdx].videoUrls.push('');
            },
            removeVideoUrl(mIdx, cIdx, vUrlIdx) {
                if (this.newModules[mIdx].contents[cIdx].videoUrls.length > 1) {
                    this.newModules[mIdx].contents[cIdx].videoUrls.splice(vUrlIdx, 1);
                }
            },
            addVideoFile(mIdx, cIdx) {
                this.newModules[mIdx].contents[cIdx].videoFiles.push('');
            },
            removeVideoFile(mIdx, cIdx, vFileIdx) {
                if (this.newModules[mIdx].contents[cIdx].videoFiles.length > 1) {
                    this.newModules[mIdx].contents[cIdx].videoFiles.splice(vFileIdx, 1);
                }
            },
            addVideoResource(mIdx, cIdx) {
                this.newModules[mIdx].contents[cIdx].resources.push({ type: 'url', url: '' });
            },
            removeVideoResource(mIdx, cIdx, rIdx) {
                if (this.newModules[mIdx].contents[cIdx].resources.length > 1) {
                    this.newModules[mIdx].contents[cIdx].resources.splice(rIdx, 1);
                }
            },
            addFileUrl(mIdx, cIdx) {
                this.newModules[mIdx].contents[cIdx].fileUrls.push('');
            },
            removeFileUrl(mIdx, cIdx, fUrlIdx) {
                if (this.newModules[mIdx].contents[cIdx].fileUrls.length > 1) {
                    this.newModules[mIdx].contents[cIdx].fileUrls.splice(fUrlIdx, 1);
                }
            },
            addFileFile(mIdx, cIdx) {
                this.newModules[mIdx].contents[cIdx].fileFiles.push('');
            },
            removeFileFile(mIdx, cIdx, fFileIdx) {
                if (this.newModules[mIdx].contents[cIdx].fileFiles.length > 1) {
                    this.newModules[mIdx].contents[cIdx].fileFiles.splice(fFileIdx, 1);
                }
            },
            addQuizOption(mIdx, cIdx) {
                const content = this.newModules[mIdx].contents[cIdx];
                content.quiz_options.push({ id: content.nextOptionId++, value: '' });
            },
            removeQuizOption(mIdx, cIdx, oIdx) {
                const content = this.newModules[mIdx].contents[cIdx];
                if (content.quiz_options.length > 1) {
                    content.quiz_options.splice(oIdx, 1);
                }
                if (content.quiz_answer_id && !content.quiz_options.find(opt => opt.id === content.quiz_answer_id)) {
                    content.quiz_answer_id = null;
                }
            },
            newContent() {
                return {
                    title: '',
                    type: 'video',
                    videoSource: 'url',
                    videoUrls: [''],
                    videoFiles: [''],
                    resources: [{ type: 'url', url: '' }],
                    fileUrls: [''],
                    fileFiles: [''],
                    quiz_question: '',
                    quiz_options: [{ id: 1, value: '' }],
                    quiz_answer_id: null,
                    nextOptionId: 2
                };
            },
            init() {
                // Track all form changes
                this.$watch('newModules', () => this.hasUnsavedChanges = true, { deep: true });
                this.$watch('newDemoVideos', () => this.hasUnsavedChanges = true, { deep: true });
                this.$watch('existingModules', () => this.hasUnsavedChanges = true, { deep: true });
                this.$watch('existingDemoVideos', () => this.hasUnsavedChanges = true, { deep: true });

                // Add event listener for beforeunload
                window.addEventListener('beforeunload', (e) => {
                    if (this.hasUnsavedChanges) {
                        e.preventDefault();
                        e.returnValue = '';
                    }
                });
            },
            hasUnsavedChanges: false,
            isSaving: false,
            saveProgress: 0,
            showDeleteModal: false,
            deleteType: null,
            deleteIndex: null,
            deleteModuleIndex: null,
            deleteModalMessage: '',
            deleteItemId: null,
            confirmDelete(type, index, moduleIndex = null) {
                this.deleteType = type;
                this.deleteIndex = index;
                this.deleteModuleIndex = moduleIndex;

                switch(type) {
                    case 'demoVideo':
                        if (!this.existingDemoVideos || !this.existingDemoVideos[index]) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Demo Video Already Deleted or Not Found',
                                timer: 1500,
                                showConfirmButton: false
                            });
                            return;
                        }
                        this.deleteModalMessage = 'Are you sure you want to delete this demo video?';
                        this.deleteItemId = this.existingDemoVideos[index].id;
                        break;
                    case 'module':
                        if (!this.existingModules || !this.existingModules[index]) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Module Already Deleted or Not Found',
                                timer: 1500,
                                showConfirmButton: false
                            });
                            return;
                        }
                        this.deleteModalMessage = 'Are you sure you want to delete this module? This will also delete all its contents.';
                        this.deleteItemId = this.existingModules[index].id;
                        break;
                    case 'content':
                        this.deleteModalMessage = 'Are you sure you want to delete this content?';
                        this.deleteModuleIndex = this.existingModules.findIndex(m => m.id == moduleIndex);
                        this.deleteIndex = this.existingModules[this.deleteModuleIndex].contents.findIndex(c => c.id == index);
                        this.deleteItemId = index;
                        break;
                }
                
                this.showDeleteModal = true;
            },
            async confirmDeleteAction() {
                try {
                    let url = '';
                    let data = {};

                    switch(this.deleteType) {
                        case 'demoVideo':
                            url = `/mentor/courses/{{ $course->id }}/demo-videos/delete`;
                            data = { demo_video_id: this.deleteItemId };
                            break;
                        case 'module':
                            url = `/mentor/courses/{{ $course->id }}/modules/delete`;
                            data = { module_id: this.deleteItemId };
                            break;
                        case 'content':
                            url = `/mentor/courses/{{ $course->id }}/modules/contents/delete`;
                            data = { 
                                module_id: this.existingModules[this.deleteModuleIndex].id,
                                content_id: this.deleteItemId 
                            };
                            break;
                    }

                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();

                    if (result.success) {
                        if (this.deleteType === 'module') {
                            this.existingModules.splice(this.deleteIndex, 1);
                        } else if (this.deleteType === 'demoVideo') {
                            this.existingDemoVideos.splice(this.deleteIndex, 1);
                        } else if (this.deleteType === 'content') {
                            this.existingModules[this.deleteModuleIndex].contents.splice(this.deleteIndex, 1);
                        }

                        // Stylish success popup
                        Swal.fire({
                          icon: 'success',
                          title: 'Deleted!',
                          text: 'Item deleted successfully',
                          timer: 1500,
                          showConfirmButton: false
                        });
                    } else {
                        throw new Error(result.message || 'Failed to delete item');
                    }
                } catch (error) {
                    alert(error.message || 'An error occurred while deleting the item');
                } finally {
                    this.showDeleteModal = false;
                }
            },
            openVideoPopup(url) {
                if (!url) return;
                
                let popup = document.createElement('div');
                popup.style.position = 'fixed';
                popup.style.top = '0';
                popup.style.left = '0';
                popup.style.width = '100vw';
                popup.style.height = '100vh';
                popup.style.background = 'rgba(0,0,0,0.7)';
                popup.style.display = 'flex';
                popup.style.alignItems = 'center';
                popup.style.justifyContent = 'center';
                popup.style.zIndex = '9999';
                
                popup.innerHTML = `
                    <div style="background:#fff;padding:20px;border-radius:8px;max-width:90vw;max-height:90vh;position:relative;">
                        <button onclick="this.closest('div').parentNode.remove()" style="position:absolute;top:10px;right:10px;font-size:20px;">&times;</button>
                        <video src="${url}" controls style="max-width:80vw;max-height:70vh;"></video>
                    </div>
                `;
                document.body.appendChild(popup);
            },
            submitForm(event) {
                const form = event.target;

                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                this.isSaving = true;
                this.saveProgress = 0;
                this.hasUnsavedChanges = false;

                const formData = new FormData(form);

                const xhr = new XMLHttpRequest();

                xhr.upload.addEventListener('progress', (e) => {
                    if (e.lengthComputable) {
                        this.saveProgress = Math.round((e.loaded / e.total) * 100);
                    } else {
                        this.saveProgress = -1;
                    }
                });

                xhr.addEventListener('load', () => {
                    this.isSaving = false;
                    if (xhr.status >= 200 && xhr.status < 300) {
                        this.saveProgress = 100;
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.redirect) {
                                window.location.href = response.redirect;
                            } else if (response.message) {
                                alert(response.message);
                                window.location.href = '/mentor/courses';
                            } else {
                                window.location.href = '/mentor/courses';
                            }
                        } catch (e) {
                            window.location.href = '/mentor/courses';
                        }
                    } else {
                        this.saveProgress = 0;
                        console.error('Upload failed. Status:', xhr.status, 'Response:', xhr.responseText);
                        let errorMessage = 'Course update failed.';
                        try {
                            const errorResponse = JSON.parse(xhr.responseText);
                            if (errorResponse.message) {
                                errorMessage += ' ' + errorResponse.message;
                            } else if (errorResponse.errors) {
                                errorMessage += ' Validation errors:';
                                for (const field in errorResponse.errors) {
                                    errorMessage += '\n - ' + errorResponse.errors[field].join(', ');
                                }
                            }
                        } catch (e) {
                            errorMessage += ' Unable to parse server response.';
                        }
                        alert(errorMessage);
                        this.hasUnsavedChanges = true;
                    }
                });

                xhr.addEventListener('error', () => {
                    this.isSaving = false;
                    this.saveProgress = 0;
                    console.error('Network error during upload.', xhr);
                    alert('A network error occurred during upload. Please check your connection.');
                    this.hasUnsavedChanges = true;
                });

                xhr.addEventListener('abort', () => {
                    this.isSaving = false;
                    this.saveProgress = 0;
                    console.warn('Upload was cancelled.');
                    alert('Upload was cancelled.');
                    this.hasUnsavedChanges = true;
                });

                xhr.open(form.method, form.action);
                xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
                xhr.send(formData);
            }
        }
    }
</script>
@endpush

@push('styles')
<style>
    /* Smooth transitions for all interactive elements */
    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 300ms;
    }

    /* Hover effects for cards */
    .hover\:shadow-lg:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    /* Smooth collapse animation */
    [x-show] {
        transition: all 0.3s ease-in-out;
    }

    /* Floating button animation */
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-5px); }
        100% { transform: translateY(0px); }
    }

    .fixed.bottom-8.right-8 button {
        animation: float 3s ease-in-out infinite;
    }

    /* Modal animations */
    .fixed.inset-0 {
        animation: fadeIn 0.2s ease-out;
    }

    .fixed.inset-0 > div {
        animation: slideIn 0.2s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideIn {
        from { transform: translateY(-20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
</style>
@endpush
