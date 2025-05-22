<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseModule extends Model
{
    protected $fillable = ['title', 'description', 'course_id'];

    public function contents()
    {
        return $this->hasMany(CourseContent::class, 'module_id');
    }

    protected static function boot()
    {
        parent::boot();

        // Delete associated content files when a module is deleting
        static::deleting(function ($module) {
            foreach ($module->contents as $content) {
                // This will trigger the deleting event on CourseContent
                $content->delete();
            }
        });
    }
}
