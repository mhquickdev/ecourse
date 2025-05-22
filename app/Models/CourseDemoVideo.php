<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseDemoVideo extends Model
{
    protected $fillable = ['title', 'video_url', 'type', 'file_path', 'course_id'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
