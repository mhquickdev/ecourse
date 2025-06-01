<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdvancedCourseApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'application_message',
        'status',
        'course_link',
        'instruction',
        'rejection_note',
    ];

    // 🔹 Relationship to User
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    // 🔹 Relationship to Course
    public function course()
    {
        return $this->belongsTo(\App\Models\Course::class, 'course_id');
    }
}