<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Category;
use App\Models\CourseModule;
use App\Models\Enrollment;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'preview_image',
        'price',
        'is_free',
        'category_id',
        'status'
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'price' => 'decimal:2'
    ];

    /**
     * Get the user (mentor) that owns the course.
     */
    public function mentor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function modules()
    {
        return $this->hasMany(CourseModule::class)->orderBy('order');
    }

    public function demoVideos()
    {
        return $this->hasMany(CourseDemoVideo::class)->orderBy('order');
    }

    public function tags()
    {
        return $this->hasMany(CourseTag::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function enrolledStudents()
    {
        return $this->belongsToMany(User::class, 'enrollments')
            ->withPivot(['enrolled_at', 'completed_at', 'payment_status'])
            ->withTimestamps();
    }
}
