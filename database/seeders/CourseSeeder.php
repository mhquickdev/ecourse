<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\User;
use App\Models\Category;
use App\Models\CourseModule;
use App\Models\CourseContent;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure a mentor user and a category exist
        $mentor = User::where('role_id', 3)->first(); // assuming 3 is mentor
        if (!$mentor) {
            $mentor = User::factory()->create([
                'name' => 'Mentor User',
                'email' => 'mentor@example.com',
                'role_id' => 3,
            ]);
        }

        $category = Category::first() ?? Category::create([
            'name' => 'Programming',
            'slug' => 'programming',
            'description' => 'Programming courses',
        ]);

        $course = Course::create([
            'user_id' => $mentor->id,
            'title' => 'Sample Course',
            'slug' => 'sample-course',
            'description' => 'This is a sample course description.',
            'preview_image' => 'courses/sample.jpg',
            'price' => 49.99,
            'is_free' => false,
            'category_id' => $category->id,
            'status' => 'published',
        ]);

        $module = CourseModule::create([
            'course_id' => $course->id,
            'title' => 'Introduction',
            'description' => 'Introductory module',
            'order' => 1,
        ]);

        CourseContent::create([
            'module_id' => $module->id,
            'title' => 'Welcome Video',
            'description' => 'Welcome to the course!',
            'type' => 'video',
            'content_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'order' => 1,
            'is_free_preview' => true,
        ]);
    }
} 