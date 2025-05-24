<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with(['user', 'category']);

        // Filter: Category
        if ($request->filled('category')) {
            $query->whereIn('category_id', (array)$request->input('category'));
        }
        // Filter: Instructor
        if ($request->filled('instructor')) {
            $query->whereIn('user_id', (array)$request->input('instructor'));
        }
        // Filter: Price (free/paid)
        if ($request->filled('price')) {
            if ($request->input('price') === 'free') {
                $query->where('is_free', true);
            } elseif ($request->input('price') === 'paid') {
                $query->where('is_free', false);
            }
        }
        // Filter: Price Range
        if ($request->filled('range')) {
            $range = floatval($request->input('range'));
            if ($range > 0) {
                $query->where('price', '<=', $range);
            }
        }
        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhereHas('category', function($q2) use ($search) {
                      $q2->where('name', 'like', "%$search%") ;
                  });
            });
        }
        // Sorting
        if ($request->filled('sort')) {
            if ($request->input('sort') === 'price_asc') {
                $query->orderBy('price', 'asc');
            } elseif ($request->input('sort') === 'price_desc') {
                $query->orderBy('price', 'desc');
            } else {
                $query->latest();
            }
        } else {
            $query->latest();
        }

        $courses = $query->paginate(6)->withQueryString();

        $categories = Category::withCount('courses')->get();
        $instructors = User::whereHas('courses')->withCount('courses')->get();

        // AJAX support
        if ($request->ajax() || $request->input('ajax')) {
            $html = view('courses.partials.grid', compact('courses'))->render();
            $count_text = 'Showing ' . $courses->firstItem() . '-' . $courses->lastItem() . ' of ' . $courses->total() . ' results';
            return response()->json([
                'html' => $html,
                'count_text' => $count_text,
            ]);
        }

        return view('courses.index', compact('courses', 'categories', 'instructors'));
    }

    public function show(Course $course)
    {
        $course->load(['user', 'category', 'modules.contents', 'demoVideos']);
        $relatedCourses = Course::with(['user', 'category'])
            ->where('category_id', $course->category_id)
            ->where('id', '!=', $course->id)
            ->inRandomOrder()->limit(3)->get();
        $authorCourses = Course::with(['user', 'category'])
            ->where('user_id', $course->user_id)
            ->where('id', '!=', $course->id)
            ->inRandomOrder()->limit(3)->get();
        return view('courses.show', compact('course', 'relatedCourses', 'authorCourses'));
    }
} 