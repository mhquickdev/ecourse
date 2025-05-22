@extends('layouts.mentor')

@section('content')
<div class="p-6">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Edit Course: {{ $course->title }}</h2>
            <div class="flex items-center gap-4">
                <form action="{{ route('mentor.courses.destroy', $course) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2" onclick="return confirm('Are you sure you want to delete this course?')">
                        <i class="fas fa-trash mr-2"></i> Delete Course
                    </button>
                </form>
                <a href="{{ route('mentor.courses.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Courses
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Course Details -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Course Details</h3>
                    <form action="{{ route('mentor.courses.update', $course) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Course Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $course->title) }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="preview_image" class="block text-sm font-medium text-gray-700 mb-1">Preview Image</label>
                            <input type="file" name="preview_image" id="preview_image" accept="image/*"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @if($course->preview_image)
                                <img src="{{ Storage::url($course->preview_image) }}" alt="Current preview" class="mt-2 w-full h-32 object-cover rounded-lg">
                            @endif
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea name="description" id="description" rows="4" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $course->description) }}</textarea>
                        </div>

                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select name="category_id" id="category_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $course->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                                <input type="number" name="price" id="price" step="0.01" min="0" value="{{ old('price', $course->price) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="flex items-center">
                                <label class="inline-flex items-center mt-6">
                                    <input type="checkbox" name="is_free" class="form-checkbox h-5 w-5 text-blue-600" {{ $course->is_free ? 'checked' : '' }}>
                                    <span class="ml-2 text-gray-700">Make this course free</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label for="tags" class="block text-sm font-medium text-gray-700 mb-1">Tags (comma separated)</label>
                            <input type="text" name="tags" id="tags" value="{{ old('tags', $course->tags->pluck('name')->join(', ')) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="e.g. web development, javascript, php">
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" id="status" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="draft" {{ $course->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ $course->status === 'published' ? 'selected' : '' }}>Published</option>
                                <option value="archived" {{ $course->status === 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Course Modules -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Course Modules</h3>
                        <button type="button" id="add-module" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <i class="fas fa-plus mr-2"></i> Add Module
                        </button>
                    </div>

                    <div id="modules-container" class="space-y-6">
                        @foreach($course->modules as $module)
                            <div class="module-entry bg-gray-50 p-4 rounded-lg">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="font-medium">Module {{ $loop->iteration }}</h4>
                                    <button type="button" class="text-red-600 hover:text-red-800 remove-module">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <div class="space-y-4">
                                    <input type="text" name="modules[{{ $loop->index }}][title]" value="{{ $module->title }}" placeholder="Module Title" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <textarea name="modules[{{ $loop->index }}][description]" placeholder="Module Description"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ $module->description }}</textarea>
                                    
                                    <div class="contents-container space-y-4">
                                        @foreach($module->contents as $content)
                                            <div class="content-entry bg-white p-4 rounded-lg border">
                                                <div class="flex justify-between items-center mb-4">
                                                    <h5 class="font-medium">Content {{ $loop->iteration }}</h5>
                                                    <button type="button" class="text-red-600 hover:text-red-800 remove-content">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                                <div class="space-y-4">
                                                    <input type="text" name="modules[{{ $loop->parent->index }}][contents][{{ $loop->index }}][title]" value="{{ $content->title }}" placeholder="Content Title" required
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                    <select name="modules[{{ $loop->parent->index }}][contents][{{ $loop->index }}][type]" required
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                        <option value="video" {{ $content->type === 'video' ? 'selected' : '' }}>Video</option>
                                                        <option value="quiz" {{ $content->type === 'quiz' ? 'selected' : '' }}>Quiz</option>
                                                        <option value="file" {{ $content->type === 'file' ? 'selected' : '' }}>File</option>
                                                        <option value="resource" {{ $content->type === 'resource' ? 'selected' : '' }}>Resource</option>
                                                    </select>
                                                    <input type="text" name="modules[{{ $loop->parent->index }}][contents][{{ $loop->index }}][content_url]" value="{{ $content->content_url }}" placeholder="Content URL"
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                    @if($content->file_path)
                                                        <div class="text-sm text-gray-500">
                                                            Current file: {{ basename($content->file_path) }}
                                                        </div>
                                                    @endif
                                                    <input type="file" name="modules[{{ $loop->parent->index }}][contents][{{ $loop->index }}][file]" 
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" class="add-content text-sm text-blue-600 hover:text-blue-800">
                                        + Add Content
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Demo Videos Display --}}
        @if($course->demoVideos && $course->demoVideos->count())
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-2">Demo Videos</h3>
                <ul>
                    @foreach($course->demoVideos as $video)
                        <li class="mb-2">
                            <strong>{{ $video->title }}</strong> ({{ ucfirst($video->type) }})<br>
                            @if($video->type === 'youtube' && $video->video_url)
                                <a href="{{ $video->video_url }}" target="_blank" class="text-blue-600 underline">Watch on YouTube</a>
                            @elseif($video->type === 'hosted' && $video->file_path)
                                <a href="{{ Storage::url($video->file_path) }}" target="_blank" class="text-blue-600 underline">Download/View File</a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Modules and Contents Display --}}
        @if($course->modules && $course->modules->count())
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-2">Modules</h3>
                @foreach($course->modules as $module)
                    <div class="mb-4 p-4 bg-gray-50 rounded">
                        <h4 class="font-bold mb-1">{{ $module->title }}</h4>
                        <div class="mb-2 text-gray-700">{{ $module->description }}</div>
                        @if($module->contents && $module->contents->count())
                            <ul>
                                @foreach($module->contents as $content)
                                    <li class="mb-2">
                                        <strong>{{ $content->title }}</strong> ({{ ucfirst($content->type) }})<br>
                                        @if($content->type === 'video' && $content->video_urls)
                                            @foreach(json_decode($content->video_urls, true) ?? [] as $url)
                                                <a href="{{ $url }}" target="_blank" class="text-blue-600 underline">Video URL</a><br>
                                            @endforeach
                                        @endif
                                        @if($content->video_files)
                                            @foreach(json_decode($content->video_files, true) ?? [] as $file)
                                                <a href="{{ Storage::url($file) }}" target="_blank" class="text-blue-600 underline">Video File</a><br>
                                            @endforeach
                                        @endif
                                        @if($content->file_urls)
                                            @foreach(json_decode($content->file_urls, true) ?? [] as $url)
                                                <a href="{{ $url }}" target="_blank" class="text-blue-600 underline">File URL</a><br>
                                            @endforeach
                                        @endif
                                        @if($content->file_files)
                                            @foreach(json_decode($content->file_files, true) ?? [] as $file)
                                                <a href="{{ Storage::url($file) }}" target="_blank" class="text-blue-600 underline">File Upload</a><br>
                                            @endforeach
                                        @endif
                                        @if($content->resources)
                                            @foreach(json_decode($content->resources, true) ?? [] as $resource)
                                                @if(isset($resource['type']) && $resource['type'] === 'url' && !empty($resource['url']))
                                                    <a href="{{ $resource['url'] }}" target="_blank" class="text-blue-600 underline">Resource URL</a><br>
                                                @elseif(isset($resource['type']) && $resource['type'] === 'file' && !empty($resource['file']))
                                                    <a href="{{ Storage::url($resource['file']) }}" target="_blank" class="text-blue-600 underline">Resource File</a><br>
                                                @endif
                                            @endforeach
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#description',
        height: 300,
        plugins: 'link image code table lists',
        toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code'
    });

    // Modules Management
    let moduleCount = {{ $course->modules->count() }};
    document.getElementById('add-module').addEventListener('click', function() {
        const container = document.getElementById('modules-container');
        const newModule = document.createElement('div');
        newModule.className = 'module-entry bg-gray-50 p-4 rounded-lg';
        newModule.innerHTML = `
            <div class="flex justify-between items-center mb-4">
                <h4 class="font-medium">Module ${moduleCount + 1}</h4>
                <button type="button" class="text-red-600 hover:text-red-800 remove-module">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class="space-y-4">
                <input type="text" name="modules[${moduleCount}][title]" placeholder="Module Title" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <textarea name="modules[${moduleCount}][description]" placeholder="Module Description"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                
                <div class="contents-container space-y-4">
                    <div class="content-entry bg-white p-4 rounded-lg border">
                        <div class="flex justify-between items-center mb-4">
                            <h5 class="font-medium">Content 1</h5>
                            <button type="button" class="text-red-600 hover:text-red-800 remove-content">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <div class="space-y-4">
                            <input type="text" name="modules[${moduleCount}][contents][0][title]" placeholder="Content Title" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <select name="modules[${moduleCount}][contents][0][type]" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="video">Video</option>
                                <option value="quiz">Quiz</option>
                                <option value="file">File</option>
                                <option value="resource">Resource</option>
                            </select>
                            <input type="text" name="modules[${moduleCount}][contents][0][content_url]" placeholder="Content URL"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <input type="file" name="modules[${moduleCount}][contents][0][file]" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>
                <button type="button" class="add-content text-sm text-blue-600 hover:text-blue-800">
                    + Add Content
                </button>
            </div>
        `;
        container.appendChild(newModule);
        moduleCount++;
    });

    // Content Management
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-content')) {
            const module = e.target.closest('.module-entry');
            const contentsContainer = module.querySelector('.contents-container');
            const contentCount = contentsContainer.children.length;
            const moduleIndex = Array.from(module.parentElement.children).indexOf(module);
            
            const newContent = document.createElement('div');
            newContent.className = 'content-entry bg-white p-4 rounded-lg border';
            newContent.innerHTML = `
                <div class="flex justify-between items-center mb-4">
                    <h5 class="font-medium">Content ${contentCount + 1}</h5>
                    <button type="button" class="text-red-600 hover:text-red-800 remove-content">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="space-y-4">
                    <input type="text" name="modules[${moduleIndex}][contents][${contentCount}][title]" placeholder="Content Title" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <select name="modules[${moduleIndex}][contents][${contentCount}][type]" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="video">Video</option>
                        <option value="quiz">Quiz</option>
                        <option value="file">File</option>
                        <option value="resource">Resource</option>
                    </select>
                    <input type="text" name="modules[${moduleIndex}][contents][${contentCount}][content_url]" placeholder="Content URL"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <input type="file" name="modules[${moduleIndex}][contents][${contentCount}][file]" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            `;
            contentsContainer.appendChild(newContent);
        }
    });

    // Remove Module
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-module')) {
            e.target.closest('.module-entry').remove();
        }
    });

    // Remove Content
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-content')) {
            e.target.closest('.content-entry').remove();
        }
    });
</script>
@endpush
@endsection 