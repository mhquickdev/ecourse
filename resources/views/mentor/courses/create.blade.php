@extends('layouts.mentor')

<script>
    function courseFormAlpine(csrfTokenValue) {
        return {
            isDirty: false,
            isLoading: false,
            uploadProgress: 0,
            csrfToken: csrfTokenValue,
            status: 'draft',
            demoVideos: [{
                title: '',
                url: '',
                type: 'youtube'
            }],
            modules: [{
                title: '',
                description: '',
                open: true,
                contents: [{
                    title: '',
                    type: 'video',
                    videoSource: 'url',
                    videoUrls: [''],
                    videoFiles: [''],
                    resources: [{
                        type: 'url',
                        url: ''
                    }],
                    fileUrls: [''],
                    fileFiles: [''],
                    quiz_question: '',
                    quiz_options: [{
                        id: 1,
                        value: ''
                    }],
                    quiz_answer_id: null,
                    nextOptionId: 2
                }]
            }],
            addDemoVideo() {
                this.demoVideos.push({
                    title: '',
                    url: '',
                    type: 'youtube'
                });
            },
            removeDemoVideo(idx) {
                if (this.demoVideos.length > 1) this.demoVideos.splice(idx, 1);
            },
            addModule() {
                this.modules.push({
                    title: '',
                    description: '',
                    open: true,
                    contents: [this.newContent()]
                });
            },
            removeModule(mIdx) {
                if (this.modules.length > 1) this.modules.splice(mIdx, 1);
            },
            addContent(mIdx) {
                this.modules[mIdx].contents.push(this.newContent());
            },
            removeContent(mIdx, cIdx) {
                if (this.modules[mIdx].contents.length > 1) this.modules[mIdx].contents.splice(cIdx, 1);
            },
            addQuizOption(mIdx, cIdx) {
                const content = this.modules[mIdx].contents[cIdx];
                content.quiz_options.push({
                    id: content.nextOptionId++,
                    value: ''
                });
            },
            removeQuizOption(mIdx, cIdx, oIdx) {
                const content = this.modules[mIdx].contents[cIdx];
                if (content.quiz_options.length > 1) content.quiz_options.splice(oIdx, 1);
                // If the removed option was selected, clear the answer
                if (content.quiz_answer_id && !content.quiz_options.find(opt => opt.id === content.quiz_answer_id)) {
                    content.quiz_answer_id = null;
                }
            },
            addVideoResource(mIdx, cIdx) {
                this.modules[mIdx].contents[cIdx].resources.push({
                    type: 'url',
                    url: ''
                });
            },
            removeVideoResource(mIdx, cIdx, rIdx) {
                if (this.modules[mIdx].contents[cIdx].resources.length > 1) this.modules[mIdx].contents[cIdx].resources
                    .splice(rIdx, 1);
            },
            addFileUrl(mIdx, cIdx) {
                this.modules[mIdx].contents[cIdx].fileUrls.push('');
            },
            removeFileUrl(mIdx, cIdx, fUrlIdx) {
                if (this.modules[mIdx].contents[cIdx].fileUrls.length > 1) this.modules[mIdx].contents[cIdx].fileUrls
                    .splice(fUrlIdx, 1);
            },
            addFileFile(mIdx, cIdx) {
                this.modules[mIdx].contents[cIdx].fileFiles.push('');
            },
            removeFileFile(mIdx, cIdx, fFileIdx) {
                if (this.modules[mIdx].contents[cIdx].fileFiles.length > 1) this.modules[mIdx].contents[cIdx].fileFiles
                    .splice(fFileIdx, 1);
            },
            newContent() {
                return {
                    title: '',
                    type: 'video',
                    videoSource: 'url',
                    videoUrls: [''],
                    videoFiles: [''],
                    resources: [{
                        type: 'url',
                        url: ''
                    }],
                    fileUrls: [''],
                    fileFiles: [''],
                    quiz_question: '',
                    quiz_options: [{
                        id: 1,
                        value: ''
                    }],
                    quiz_answer_id: null,
                    nextOptionId: 2
                };
            },
            debugModules() {
                console.log('Modules:', JSON.parse(JSON.stringify(this.modules)));
                alert('Modules data logged to console.');
            },
            init() {
                window.addEventListener('beforeunload', (event) => {
                    if (this.isDirty || this.isLoading) {
                        event.preventDefault();
                        event.returnValue = '';
                    }
                });
            },
            submitForm(event) {
                const form = event.target;

                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                this.isLoading = true;
                this.uploadProgress = 0;
                this.isDirty = false;

                const formData = new FormData(form);

                const xhr = new XMLHttpRequest();

                xhr.upload.addEventListener('progress', (e) => {
                    if (e.lengthComputable) {
                        this.uploadProgress = Math.round((e.loaded / e.total) * 100);
                    } else {
                        this.uploadProgress = -1;
                    }
                });

                xhr.addEventListener('load', () => {
                    this.isLoading = false;
                    if (xhr.status >= 200 && xhr.status < 300) {
                        this.uploadProgress = 100;
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
                        this.uploadProgress = 0;
                        console.error('Upload failed. Status:', xhr.status, 'Response:', xhr.responseText);
                        let errorMessage = 'Course creation failed.';
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
                        this.isDirty = true;
                    }
                });

                xhr.addEventListener('error', () => {
                    this.isLoading = false;
                    this.uploadProgress = 0;
                    console.error('Network error during upload.', xhr);
                    alert('A network error occurred during upload. Please check your connection.');
                    this.isDirty = true;
                });

                xhr.addEventListener('abort', () => {
                    this.isLoading = false;
                    this.uploadProgress = 0;
                    console.warn('Upload aborted.', xhr);
                    alert('Upload was cancelled.');
                    this.isDirty = true;
                });

                xhr.open(form.method, form.action);

                xhr.setRequestHeader('X-CSRF-TOKEN', this.csrfToken);

                xhr.send(formData);
            },
            tagsInput: '',
            watchTagsInput() {
                this.$watch('tagsInput', (value) => {
                    const initialTags = document.getElementById('tags').value;
                    if (value !== initialTags) {
                        this.isDirty = true;
                    }
                });
            }
        }
    }
</script>

@section('content')
    @php
        $uploadMax = ini_get('upload_max_filesize');
        $postMax = ini_get('post_max_size');
        function sizeToBytes($size)
        {
            $unit = strtolower(substr($size, -1));
            $bytes = (int) $size;
            switch ($unit) {
                case 'g':
                    $bytes *= 1024;
                case 'm':
                    $bytes *= 1024;
                case 'k':
                    $bytes *= 1024;
            }
            return $bytes;
        }
        $minRequired = 256 * 1024 * 1024; // 256MB in bytes
    @endphp
    @if (sizeToBytes($uploadMax) < $minRequired || sizeToBytes($postMax) < $minRequired)
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            <strong>Warning:</strong> Your server's file upload limit is too low for large video uploads.<br>
            <span>Current <code>upload_max_filesize</code>: <strong>{{ $uploadMax }}</strong>,
                <code>post_max_size</code>: <strong>{{ $postMax }}</strong>. Minimum recommended:
                <strong>256M</strong>.<br>
                Please increase these values in your <code>php.ini</code> and restart your web server for best
                results.</span>
        </div>
    @endif
    @push('meta')
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endpush
    <div class="p-6 flex flex-col lg:flex-row gap-6" x-data="courseFormAlpine('{{ csrf_token() }}')" x-init="init(); watchTagsInput();">
        <form action="{{ route('mentor.courses.store') }}" method="POST" enctype="multipart/form-data"
            class="flex flex-col lg:flex-row gap-6 w-full" @change="isDirty = true" @submit.prevent="submitForm">
            @csrf

            <!-- Left Sticky Sidebar -->
            <div class="w-full lg:w-1/3 xl:w-1/4 lg:sticky lg:top-0 lg:h-screen lg:overflow-y-auto bg-white rounded-xl shadow-sm p-6 space-y-6 flex-shrink-0">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Basic Information</h2>

                <!-- Course Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Course Title</label>
                    <input type="text" name="title" id="title" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Preview Image -->
                <div>
                    <label for="preview_image" class="block text-sm font-medium text-gray-700 mb-1">Preview
                        Image</label>
                    <input type="file" name="preview_image" id="preview_image" accept="image/*" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Course
                        Description</label>
                    <textarea name="description" id="description" rows="6" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select name="category_id" id="category_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select a category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Price -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                        <input type="number" name="price" id="price" step="0.01" min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex items-center">
                        <label class="inline-flex items-center mt-6">
                            <input type="checkbox" name="is_free" class="form-checkbox h-5 w-5 text-blue-600">
                            <span class="ml-2 text-gray-700">Make this course free</span>
                        </label>
                    </div>
                </div>

                <!-- Tags -->
                <div>
                    <label for="tags" class="block text-sm font-medium text-gray-700 mb-1">Tags (comma
                        separated)</label>
                    <input type="text" name="tags" id="tags" x-model="tagsInput"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="e.g. web development, javascript, php">
                </div>

                <!-- Demo Videos -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Demo Videos</label>
                    <template x-for="(video, vIdx) in demoVideos" :key="vIdx">
                        <div class="demo-video-entry flex flex-col md:flex-row gap-2 md:gap-4 mb-2 items-start md:items-center">
                            <input type="text" :name="`demo_videos[${vIdx}][title]`" x-model="video.title"
                                placeholder="Video Title"
                                class="w-full md:flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <template x-if="video.type === 'youtube'">
                                <input type="text" :name="`demo_videos[${vIdx}][url]`" x-model="video.url"
                                    placeholder="Video URL"
                                    class="w-full md:flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </template>
                            <template x-if="video.type === 'hosted'">
                                <input type="file" :name="`demo_videos[${vIdx}][file]`"
                                    class="w-full md:flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </template>
                            <select :name="`demo_videos[${vIdx}][type]`" x-model="video.type"
                                class="w-full md:w-auto px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="youtube">YouTube</option>
                                <option value="hosted">Hosted</option>
                            </select>
                            <button type="button" class="text-red-600 hover:text-red-800 ml-0 md:ml-2"
                                @click="removeDemoVideo(vIdx)"><i class="fas fa-trash"></i></button>
                        </div>
                    </template>
                    <button type="button" class="mt-2 text-sm text-blue-600 hover:text-blue-800" @click="addDemoVideo">
                        + Add Another Demo Video
                    </button>
                </div>

                <!-- Course Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Course Status</label>
                    <select name="status" id="status" x-model="status" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>

                <!-- Loading/Progress Bar (moved to sidebar for context) -->
                <div x-show="isLoading" class="mt-6">
                    <div class="text-sm font-medium text-gray-700 mb-1">Uploading... <span x-text="uploadProgress + '%'"></span></div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-300 ease-linear" :style="`width: ${uploadProgress}%;`"></div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        :disabled="isLoading">
                        <span x-show="!isLoading">Create Course</span>
                        <span x-show="isLoading">Creating...</span>
                    </button>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="w-full lg:w-2/3 xl:w-3/4 bg-white rounded-xl shadow-sm p-6 space-y-6">
                <!-- Course Modules -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Course Modules</label>
                    <template x-for="(module, mIdx) in modules" :key="mIdx">
                        <div class="module-entry bg-gray-50 p-4 rounded-lg mb-4">
                            <div class="flex justify-between items-center mb-4 cursor-pointer"
                                @click="module.open = !module.open">
                                <h3 class="font-medium">Module <span x-text="mIdx + 1"></span>: <span
                                        x-text="module.title || 'Untitled' "></span></h3>
                                <div class="flex items-center gap-2">
                                    <button type="button" class="text-red-600 hover:text-red-800"
                                        @click.stop="removeModule(mIdx)"><i class="fas fa-trash"></i></button>
                                    <button type="button" class="text-gray-600 hover:text-gray-800"
                                        @click.stop="module.open = !module.open">
                                        <i :class="module.open ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i>
                                    </button>
                                </div>
                            </div>
                            <div x-show="module.open" class="space-y-4">
                                <input type="text" :name="`modules[${mIdx}][title]`" x-model="module.title"
                                    placeholder="Module Title" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <textarea :name="`modules[${mIdx}][description]`" x-model="module.description" placeholder="Module Description"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                <div class="contents-container space-y-4">
                                    <template x-for="(content, cIdx) in module.contents" :key="cIdx">
                                        <div class="content-entry bg-white p-4 rounded-lg border">
                                            <div class="flex justify-between items-center mb-4">
                                                <h4 class="font-medium">Content <span x-text="cIdx + 1"></span></h4>
                                                <button type="button" class="text-red-600 hover:text-red-800"
                                                    @click="removeContent(mIdx, cIdx)"><i
                                                        class="fas fa-trash"></i></button>
                                            </div>
                                            <div class="space-y-4">
                                                <input type="text"
                                                    :name="`modules[${mIdx}][contents][${cIdx}][title]`"
                                                    x-model="content.title" placeholder="Content Title" required
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                <select :name="`modules[${mIdx}][contents][${cIdx}][type]`"
                                                    x-model="content.type" required
                                                    class="content-type w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                    <option value="video">Video</option>
                                                    <option value="quiz">Quiz</option>
                                                    <option value="file">File</option>
                                                </select>
                                                <!-- Video: URL or File, plus multiple Resources -->
                                                <template x-if="content.type === 'video'">
                                                    <div>
                                                        <label
                                                            class="block text-xs font-medium text-gray-600 mb-1">Video
                                                            Source</label>
                                                        <select x-model="content.videoSource"
                                                            class="mb-2 px-2 py-1 border rounded">
                                                            <option value="url">Video URL</option>
                                                            <option value="file">Video File</option>
                                                        </select>
                                                        <template x-if="content.videoSource === 'url'">
                                                            <template x-for="(url, vUrlIdx) in content.videoUrls"
                                                                :key="vUrlIdx">
                                                                <div class="flex items-center gap-2 mb-2">
                                                                    <input type="text"
                                                                        :name="`modules[${mIdx}][contents][${cIdx}][video_urls][]`"
                                                                        x-model="content.videoUrls[vUrlIdx]"
                                                                        placeholder="Video URL"
                                                                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                                    <button type="button"
                                                                        class="text-red-600 hover:text-red-800"
                                                                        @click="removeVideoUrl(mIdx, cIdx, vUrlIdx)"><i
                                                                            class="fas fa-minus"></i></button>
                                                                </div>
                                                            </template>
                                                            <button type="button"
                                                                class="text-sm text-blue-600 hover:text-blue-800 mb-2"
                                                                @click="addVideoUrl(mIdx, cIdx)">+ Add Video
                                                                URL</button>
                                                        </template>
                                                        <template x-if="content.videoSource === 'file'">
                                                            <template x-for="(file, vFileIdx) in content.videoFiles"
                                                                :key="vFileIdx">
                                                                <div class="flex items-center gap-2 mb-2">
                                                                    <input type="file"
                                                                        :name="`modules[${mIdx}][contents][${cIdx}][video_files][]`"
                                                                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                                    <button type="button"
                                                                        class="text-red-600 hover:text-red-800"
                                                                        @click="removeVideoFile(mIdx, cIdx, vFileIdx)"><i
                                                                            class="fas fa-minus"></i></button>
                                                                </div>
                                                            </template>
                                                            <button type="button"
                                                                class="text-sm text-blue-600 hover:text-blue-800 mb-2"
                                                                @click="addVideoFile(mIdx, cIdx)">+ Add Video
                                                                File</button>
                                                        </template>
                                                        <!-- Multiple Resources for Video -->
                                                        <div class="mt-4">
                                                            <label
                                                                class="block text-xs font-medium text-gray-600 mb-1">Resources
                                                                (optional)</label>
                                                            <template x-for="(resource, rIdx) in content.resources"
                                                                :key="rIdx">
                                                                <div class="flex items-center gap-2 mb-2">
                                                                    <select x-model="resource.type"
                                                                        class="px-2 py-1 border rounded">
                                                                        <option value="url">URL</option>
                                                                        <option value="file">File</option>
                                                                    </select>
                                                                    <template x-if="resource.type === 'url'">
                                                                        <input type="text"
                                                                            :name="`modules[${mIdx}][contents][${cIdx}][resources][${rIdx}][url]`"
                                                                            x-model="resource.url"
                                                                            placeholder="Resource URL"
                                                                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                                    </template>
                                                                    <template x-if="resource.type === 'file'">
                                                                        <input type="file"
                                                                            :name="`modules[${mIdx}][contents][${cIdx}][resources][${rIdx}][file]`"
                                                                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                                    </template>
                                                                    <button type="button"
                                                                        class="text-red-600 hover:text-red-800"
                                                                        @click="removeVideoResource(mIdx, cIdx, rIdx)"><i
                                                                            class="fas fa-minus"></i></button>
                                                                </div>
                                                            </template>
                                                            <button type="button"
                                                                class="text-sm text-blue-600 hover:text-blue-800 mb-2"
                                                                @click="addVideoResource(mIdx, cIdx)">+ Add
                                                                Resource</button>
                                                        </div>
                                                    </div>
                                                </template>
                                                <!-- File (multiple URLs and uploads) -->
                                                <template x-if="content.type === 'file'">
                                                    <div>
                                                        <label
                                                            class="block text-xs font-medium text-gray-600 mb-1">File
                                                            URLs</label>
                                                        <template x-for="(url, fUrlIdx) in content.fileUrls"
                                                            :key="fUrlIdx">
                                                            <div class="flex items-center gap-2 mb-2">
                                                                <input type="text"
                                                                    :name="`modules[${mIdx}][contents][${cIdx}][file_urls][]`"
                                                                    x-model="content.fileUrls[fUrlIdx]"
                                                                    placeholder="File URL"
                                                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                                <button type="button"
                                                                    class="text-red-600 hover:text-red-800"
                                                                    @click="removeFileUrl(mIdx, cIdx, fUrlIdx)"><i
                                                                        class="fas fa-minus"></i></button>
                                                            </div>
                                                        </template>
                                                        <button type="button"
                                                            class="text-sm text-blue-600 hover:text-blue-800 mb-2"
                                                            @click="addFileUrl(mIdx, cIdx)">+ Add File URL</button>
                                                        <label
                                                            class="block text-xs font-medium text-gray-600 mb-1 mt-2">File
                                                            Uploads</label>
                                                        <template x-for="(file, fFileIdx) in content.fileFiles"
                                                            :key="fFileIdx">
                                                            <div class="flex items-center gap-2 mb-2">
                                                                <input type="file"
                                                                    :name="`modules[${mIdx}][contents][${cIdx}][file_files][]`"
                                                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                                <button type="button"
                                                                    class="text-red-600 hover:text-red-800"
                                                                    @click="removeFileFile(mIdx, cIdx, fFileIdx)"><i
                                                                        class="fas fa-minus"></i></button>
                                                            </div>
                                                        </template>
                                                        <button type="button"
                                                            class="text-sm text-blue-600 hover:text-blue-800 mb-2"
                                                            @click="addFileFile(mIdx, cIdx)">+ Add File Upload</button>
                                                    </div>
                                                </template>
                                                <!-- Quiz -->
                                                <template x-if="content.type === 'quiz'">
                                                    <div class="quiz-fields space-y-2">
                                                        <input type="text"
                                                            :name="`modules[${mIdx}][contents][${cIdx}][quiz_question]`"
                                                            x-model="content.quiz_question"
                                                            placeholder="Quiz Question"
                                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 mb-2">
                                                        <template x-for="(option, oIdx) in content.quiz_options"
                                                            :key="option.id">
                                                            <div class="flex gap-2 mb-2 quiz-option-row">
                                                                <input type="text"
                                                                    :name="`modules[${mIdx}][contents][${cIdx}][quiz_options][${oIdx}][value]`"
                                                                    x-model="option.value"
                                                                    placeholder="Option"
                                                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                                <input type="hidden"
                                                                    :name="`modules[${mIdx}][contents][${cIdx}][quiz_options][${oIdx}][id]`"
                                                                    :value="option.id">
                                                                <input type="radio"
                                                                    :name="`modules[${mIdx}][contents][${cIdx}][quiz_answer_id]`"
                                                                    :value="option.id"
                                                                    x-model="content.quiz_answer_id">
                                                                <button type="button"
                                                                    class="text-red-600 hover:text-red-800 ml-2"
                                                                    @click="removeQuizOption(mIdx, cIdx, oIdx)">Remove</button>
                                                            </div>
                                                        </template>
                                                        <!-- Hidden input for the selected answer value -->
                                                        <input type="hidden"
                                                            :name="`modules[${mIdx}][contents][${cIdx}][quiz_answer]`"
                                                            :value="content.quiz_options.find(opt => opt.id === content.quiz_answer_id)?.value || ''">
                                                        <button type="button"
                                                            class="text-sm text-blue-600 hover:text-blue-800 mb-2"
                                                            @click="addQuizOption(mIdx, cIdx)">+ Add Option</button>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                <button type="button" class="add-content text-sm text-blue-600 hover:text-blue-800"
                                    @click="addContent(mIdx)">
                                    + Add Content
                                </button>
                            </div>
                        </div>
                    </template>
                    <button type="button" id="add-module" class="mt-4 text-sm text-blue-600 hover:text-blue-800"
                        @click="addModule()">
                        + Add Module
                    </button>
                </div>
            </div>
        </form>
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
        </script>
    @endpush
@endsection
 