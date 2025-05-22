<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CourseContent extends Model
{
    protected $fillable = [
        'title', 'type', 'module_id', 'video_source', 'video_urls', 'video_files', 'file_urls', 'file_files', 'quiz_question', 'quiz_options', 'quiz_answer', 'resources'
    ];

    public function module()
    {
        return $this->belongsTo(CourseModule::class, 'module_id');
    }

    protected static function boot()
    {
        parent::boot();

        // Delete associated files when content is deleting
        static::deleting(function ($content) {
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
        });
    }
}
