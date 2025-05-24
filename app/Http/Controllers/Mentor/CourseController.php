<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = auth()->user()->courses()->latest()->get();
        return view('mentor.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('mentor.courses.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'preview_image' => 'required|image|max:2048',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required_if:is_free,false|numeric|min:0',
            'tags' => 'nullable|string',
            'demo_videos.*.file' => 'nullable|file|max:102400',
            'demo_videos.*.url' => 'required_if:demo_videos.*.type,youtube|nullable|url',
            'modules.*.contents.*.video_files.*' => 'nullable|file|max:102400',
            'modules.*.contents.*.file_files.*' => 'nullable|file|max:102400',
            'modules.*.contents.*.resources.*.file' => 'nullable|file|max:102400',
            'status' => 'required|in:draft,published,archived',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('preview_image') && $request->file('preview_image')->isValid()) {
            $imagePath = $request->file('preview_image')->store('course-images', 'public');
        }

        // Create course
        $course = Course::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'preview_image' => $imagePath,
            'price' => $request->is_free ? 0 : $request->price,
            'is_free' => $request->boolean('is_free'),
            'category_id' => $request->category_id,
            'status' => $request->status,
        ]);

        // Handle tags
        if ($request->tags) {
            $tags = array_map('trim', explode(',', $request->tags));
            foreach ($tags as $tag) {
                $tagSlug = Str::slug($tag);
                $course->tags()->firstOrCreate(
                    ['slug' => $tagSlug],
                    ['name' => $tag]
                );
            }
        }

        // Handle demo videos
        if ($request->has('demo_videos')) {
            foreach ($request->demo_videos as $vIdx => $video) {
                $filePath = null;
                if (($video['type'] ?? null) === 'hosted' && $request->hasFile("demo_videos.$vIdx.file")) {
                    $file = $request->file("demo_videos.$vIdx.file");
                    if ($file && $file->isValid()) {
                        $filePath = $file->store('course-demo-videos', 'public');
                    }
                }
                $course->demoVideos()->create([
                    'title' => $video['title'] ?? '',
                    'video_url' => $video['type'] === 'youtube' ? ($video['url'] ?? null) : null,
                    'type' => $video['type'],
                    'file_path' => $filePath,
                ]);
            }
        }

        // Handle modules and contents
        if ($request->has('modules')) {
            foreach ($request->modules as $mIdx => $moduleData) {
                $module = $course->modules()->create([
                    'title' => $moduleData['title'] ?? '',
                    'description' => $moduleData['description'] ?? null,
                ]);
                if (isset($moduleData['contents'])) {
                    foreach ($moduleData['contents'] as $cIdx => $contentData) {
                        // Always handle video_urls
                        $videoUrls = [];
                        if (isset($contentData['video_urls']) && is_array($contentData['video_urls'])) {
                            $videoUrls = array_filter($contentData['video_urls']);
                        }
                        // Always handle video_files
                        $videoFiles = [];
                        if ($request->hasFile("modules.$mIdx.contents.$cIdx.video_files")) {
                            foreach ($request->file("modules.$mIdx.contents.$cIdx.video_files") as $file) {
                                if ($file && $file->isValid()) {
                                    $videoFiles[] = $file->store('course-content-videos', 'public');
                                }
                            }
                        }
                        // Always handle file_urls
                        $fileUrls = [];
                        if (isset($contentData['file_urls']) && is_array($contentData['file_urls'])) {
                            $fileUrls = array_filter($contentData['file_urls']);
                        }
                        // Always handle file_files
                        $fileFiles = [];
                        if ($request->hasFile("modules.$mIdx.contents.$cIdx.file_files")) {
                            foreach ($request->file("modules.$mIdx.contents.$cIdx.file_files") as $file) {
                                if ($file && $file->isValid()) {
                                    $fileFiles[] = $file->store('course-content-files', 'public');
                                }
                            }
                        }
                        // --- RESOURCES ---
                        $resources = [];
                        if (isset($contentData['resources']) && is_array($contentData['resources'])) {
                            foreach ($contentData['resources'] as $rIdx => $resource) {
                                $file = $request->file("modules.$mIdx.contents.$cIdx.resources.$rIdx.file");
                                
                                Log::info('Resource file debug', [
                                    'file' => $file,
                                    'resource' => $resource
                                ]);
                                if ($file && $file->isValid()) {
                                    $storedPath = $file->store('course-content-resources', 'public');
                                    $resources[] = [
                                        'type' => 'file',
                                        'file' => $storedPath
                                    ];
                                    Log::info('Resource file stored', ['path' => $storedPath]);
                                } elseif (!empty($resource['url'])) {
                                    $resources[] = [
                                        'type' => $resource['type'] ?? 'url',
                                        'url' => $resource['url']
                                    ];
                                    Log::info('Resource url stored', ['url' => $resource['url']]);
                                }
                            }
                        }
                        // --- QUIZ ---
                        $quizOptions = [];
                        $quizAnswer = null; // Initialize quizAnswer to null

                        if (isset($contentData['quiz_options']) && is_array($contentData['quiz_options'])) {
                            // The frontend now sends objects with id and value
                            $rawQuizOptions = $contentData['quiz_options'];

                            // Get the selected answer ID from the request
                            $selectedAnswerId = $contentData['quiz_answer_id'] ?? null;

                            // Find the option with the matching ID and get its value
                            $selectedOption = null;
                            if ($selectedAnswerId !== null) {
                                foreach ($rawQuizOptions as $option) {
                                    // Ensure 'id' and 'value' keys exist and compare IDs as strings
                                    if (isset($option['id']) && (string)$option['id'] === (string)$selectedAnswerId) {
                                        $selectedOption = $option;
                                        break; // Found the answer
                                    }
                                }
                            }

                            // Set quizAnswer from the found option's value
                            if ($selectedOption && isset($selectedOption['value']) && $selectedOption['value'] !== '') {
                                $quizAnswer = $selectedOption['value'];
                            }

                            // Convert raw quiz_options to a flat array of values for saving in DB
                            $quizOptions = array_map(fn($opt) => $opt['value'] ?? '', $rawQuizOptions);
                        }


                        // --- FINAL DEBUG LOG ---
                        Log::info('Saving content', [
                            'resources' => $resources,
                            'quiz_answer' => $quizAnswer, // This should now be the correct value
                            'quiz_options' => $quizOptions, // Save the values array
                        ]);

                        // Save content
                        $module->contents()->create([
                            'title' => $contentData['title'] ?? '',
                            'type' => $contentData['type'] ?? '',
                            'video_source' => $contentData['videoSource'] ?? null,
                            'video_urls' => $videoUrls ? json_encode($videoUrls) : null,
                            'video_files' => $videoFiles ? json_encode($videoFiles) : null,
                            'file_urls' => $fileUrls ? json_encode($fileUrls) : null,
                            'file_files' => $fileFiles ? json_encode($fileFiles) : null,
                            'quiz_question' => $contentData['quiz_question'] ?? null,
                            'quiz_options' => !empty($quizOptions) ? json_encode($quizOptions) : null, // Save values
                            'quiz_answer' => $quizAnswer, // Save the extracted value
                            'resources' => !empty($resources) ? json_encode($resources) : null,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('mentor.courses.index')
            ->with('success', 'Course created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        $this->authorize('update', $course);
        $categories = Category::all();
        return view('mentor.courses.edit', compact('course', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        // Debug log the incoming request data
        Log::info('Course update request data:', [
            'all_data' => $request->all(),
            'has_new_modules' => $request->has('new_modules'),
            'new_modules' => $request->input('new_modules'),
        ]);

        try {
            DB::beginTransaction();

            // Handle image upload if new image is provided
            if ($request->hasFile('preview_image')) {
                // Delete old image
                Storage::disk('public')->delete($course->preview_image);
                $imagePath = $request->file('preview_image')->store('course-images', 'public');
            } else {
                $imagePath = $course->preview_image;
            }

            // Update course basic info
            $course->update([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'description' => $request->description,
                'preview_image' => $imagePath,
                'price' => $request->is_free ? 0 : $request->price,
                'is_free' => $request->boolean('is_free'),
                'category_id' => $request->category_id,
                'status' => $request->status,
            ]);

            // Handle tags
            if ($request->tags) {
                $course->tags()->delete(); // Remove existing tags
                $tags = array_map('trim', explode(',', $request->tags));
                foreach ($tags as $tag) {
                    $tagSlug = Str::slug($tag);
                    $course->tags()->firstOrCreate(
                        ['slug' => $tagSlug],
                        ['name' => $tag]
                    );
                }
            }

            // Handle demo videos
            if ($request->has('new_demo_videos')) {
                foreach ($request->new_demo_videos as $vIdx => $video) {
                    $filePath = null;
                    if (($video['type'] ?? null) === 'hosted' && $request->hasFile("new_demo_videos.$vIdx.file")) {
                        $file = $request->file("new_demo_videos.$vIdx.file");
                        if ($file && $file->isValid()) {
                            $filePath = $file->store('course-demo-videos', 'public');
                        }
                    }
                    $course->demoVideos()->create([
                        'title' => $video['title'] ?? '',
                        'video_url' => $video['type'] === 'youtube' ? ($video['url'] ?? null) : null,
                        'type' => $video['type'],
                        'file_path' => $filePath,
                    ]);
                }
            }

            // Handle new modules and contents
            if ($request->has('new_modules')) {
                Log::info('Starting to process new modules');
                foreach ($request->new_modules as $mIdx => $moduleData) {
                    Log::info('Processing module:', [
                        'index' => $mIdx,
                        'data' => $moduleData
                    ]);

                    $module = $course->modules()->create([
                        'title' => $moduleData['title'] ?? '',
                        'description' => $moduleData['description'] ?? null,
                    ]);

                    Log::info('Created module:', [
                        'module_id' => $module->id,
                        'title' => $module->title
                    ]);

                    if (isset($moduleData['contents'])) {
                        foreach ($moduleData['contents'] as $cIdx => $contentData) {
                            Log::info('Processing content:', [
                                'module_id' => $module->id,
                                'content_index' => $cIdx,
                                'data' => $contentData
                            ]);

                            // Handle video content
                            $videoUrls = [];
                            if (isset($contentData['video_urls']) && is_array($contentData['video_urls'])) {
                                $videoUrls = array_filter($contentData['video_urls']);
                            }

                            $videoFiles = [];
                            if ($request->hasFile("new_modules.$mIdx.contents.$cIdx.video_files")) {
                                foreach ($request->file("new_modules.$mIdx.contents.$cIdx.video_files") as $file) {
                                    if ($file && $file->isValid()) {
                                        $videoFiles[] = $file->store('course-content-videos', 'public');
                                    }
                                }
                            }

                            // Handle file content
                            $fileUrls = [];
                            if (isset($contentData['file_urls']) && is_array($contentData['file_urls'])) {
                                $fileUrls = array_filter($contentData['file_urls']);
                            }

                            $fileFiles = [];
                            if ($request->hasFile("new_modules.$mIdx.contents.$cIdx.file_files")) {
                                foreach ($request->file("new_modules.$mIdx.contents.$cIdx.file_files") as $file) {
                                    if ($file && $file->isValid()) {
                                        $fileFiles[] = $file->store('course-content-files', 'public');
                                    }
                                }
                            }

                            // Handle resources
                            $resources = [];
                            if (isset($contentData['resources']) && is_array($contentData['resources'])) {
                                foreach ($contentData['resources'] as $rIdx => $resource) {
                                    $file = $request->file("new_modules.$mIdx.contents.$cIdx.resources.$rIdx.file");
                                    if ($file && $file->isValid()) {
                                        $storedPath = $file->store('course-content-resources', 'public');
                                        $resources[] = [
                                            'type' => 'file',
                                            'file' => $storedPath
                                        ];
                                    } elseif (!empty($resource['url'])) {
                                        $resources[] = [
                                            'type' => $resource['type'] ?? 'url',
                                            'url' => $resource['url']
                                        ];
                                    }
                                }
                            }

                            // Handle quiz
                            $quizOptions = [];
                            $quizAnswer = null;

                            if (isset($contentData['quiz_options']) && is_array($contentData['quiz_options'])) {
                                $rawQuizOptions = $contentData['quiz_options'];
                                $selectedAnswerId = $contentData['quiz_answer_id'] ?? null;

                                $selectedOption = null;
                                if ($selectedAnswerId !== null) {
                                    foreach ($rawQuizOptions as $option) {
                                        if (isset($option['id']) && (string)$option['id'] === (string)$selectedAnswerId) {
                                            $selectedOption = $option;
                                            break;
                                        }
                                    }
                                }

                                if ($selectedOption && isset($selectedOption['value']) && $selectedOption['value'] !== '') {
                                    $quizAnswer = $selectedOption['value'];
                                }

                                $quizOptions = array_map(fn($opt) => $opt['value'] ?? '', $rawQuizOptions);
                            }

                            // Create content
                            $module->contents()->create([
                                'title' => $contentData['title'] ?? '',
                                'type' => $contentData['type'] ?? '',
                                'video_source' => $contentData['videoSource'] ?? null,
                                'video_urls' => $videoUrls ? json_encode($videoUrls) : null,
                                'video_files' => $videoFiles ? json_encode($videoFiles) : null,
                                'file_urls' => $fileUrls ? json_encode($fileUrls) : null,
                                'file_files' => $fileFiles ? json_encode($fileFiles) : null,
                                'quiz_question' => $contentData['quiz_question'] ?? null,
                                'quiz_options' => !empty($quizOptions) ? json_encode($quizOptions) : null,
                                'quiz_answer' => $quizAnswer,
                                'resources' => !empty($resources) ? json_encode($resources) : null,
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            Log::info('Course update completed successfully');

            return redirect()->route('mentor.courses.index')
                ->with('success', 'Course updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Course update failed: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Failed to update course. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);

        // Delete course image
        Storage::disk('public')->delete($course->preview_image);

        // Delete demo video files
        foreach ($course->demoVideos as $video) {
            if ($video->file_path) {
                Storage::disk('public')->delete($video->file_path);
            }
        }

        // Delete module content files
        foreach ($course->modules as $module) {
            foreach ($module->contents as $content) {
                // Video files
                if ($content->video_files) {
                    foreach (json_decode($content->video_files, true) ?? [] as $file) {
                        Storage::disk('public')->delete($file);
                    }
                }
                // File files
                if ($content->file_files) {
                    foreach (json_decode($content->file_files, true) ?? [] as $file) {
                        Storage::disk('public')->delete($file);
                    }
                }
                // Resource files
                if ($content->resources) {
                    foreach (json_decode($content->resources, true) ?? [] as $resource) {
                        if (isset($resource['type']) && $resource['type'] === 'file' && !empty($resource['file'])) {
                            Storage::disk('public')->delete($resource['file']);
                        }
                    }
                }
            }
        }

        // Delete course and related data
        $course->delete();

        return redirect()->route('mentor.courses.index')
            ->with('success', 'Course deleted successfully.');
    }

    /**
     * Remove a demo video from the course.
     */
    public function deleteDemoVideo(Request $request, Course $course)
    {
        $this->authorize('update', $course);
        
        try {
            $demoVideo = $course->demoVideos()->findOrFail($request->demo_video_id);
            
            // Delete the file if it exists
            if ($demoVideo->file_path) {
                Storage::disk('public')->delete($demoVideo->file_path);
            }
            
            $demoVideo->delete();
            
            return response()->json(['success' => true, 'message' => 'Demo video deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete demo video'], 500);
        }
    }

    /**
     * Remove a module from the course.
     */
    public function deleteModule(Request $request, Course $course)
    {
        $this->authorize('update', $course);
        
        try {
            $module = $course->modules()->findOrFail($request->module_id);
            
            // Delete all content files associated with this module
            foreach ($module->contents as $content) {
                // Delete video files
                if ($content->video_files) {
                    foreach (json_decode($content->video_files, true) ?? [] as $file) {
                        Storage::disk('public')->delete($file);
                    }
                }
                
                // Delete file files
                if ($content->file_files) {
                    foreach (json_decode($content->file_files, true) ?? [] as $file) {
                        Storage::disk('public')->delete($file);
                    }
                }
                
                // Delete resource files
                if ($content->resources) {
                    foreach (json_decode($content->resources, true) ?? [] as $resource) {
                        if (isset($resource['type']) && $resource['type'] === 'file' && !empty($resource['file'])) {
                            Storage::disk('public')->delete($resource['file']);
                        }
                    }
                }
            }
            
            $module->delete();
            
            return response()->json(['success' => true, 'message' => 'Module deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete module'], 500);
        }
    }

    /**
     * Remove a content from a module.
     */
    public function deleteContent(Request $request, Course $course)
    {
        $this->authorize('update', $course);
        
        try {
            $module = $course->modules()->findOrFail($request->module_id);
            $content = $module->contents()->findOrFail($request->content_id);
            
            // Delete video files
            if ($content->video_files) {
                foreach (json_decode($content->video_files, true) ?? [] as $file) {
                    Storage::disk('public')->delete($file);
                }
            }
            
            // Delete file files
            if ($content->file_files) {
                foreach (json_decode($content->file_files, true) ?? [] as $file) {
                    Storage::disk('public')->delete($file);
                }
            }
            
            // Delete resource files
            if ($content->resources) {
                foreach (json_decode($content->resources, true) ?? [] as $resource) {
                    if (isset($resource['type']) && $resource['type'] === 'file' && !empty($resource['file'])) {
                        Storage::disk('public')->delete($resource['file']);
                    }
                }
            }
            
            $content->delete();
            
            return response()->json(['success' => true, 'message' => 'Content deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete content'], 500);
        }
    }
}

