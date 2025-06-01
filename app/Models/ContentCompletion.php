<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CourseModule;

class ContentCompletion extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'module_id',
        'content_id',
        'is_completed',
        'completed_at'
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function module()
    {
        return $this->belongsTo(CourseModule::class);
    }

    public function content()
    {
        return $this->belongsTo(CourseContent::class, 'content_id');
    }
} 