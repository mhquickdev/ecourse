<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with(['user', 'category']);

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', '%' . $search . '%');
        }

        $courses = $query->latest()->paginate(10)->withQueryString();

        return view('admin.courses.index', compact('courses'));
    }
    public function edit(Course $course)
    {
        $categories = Category::all();
        $users = User::all();
        return view('admin.courses.edit', compact('course', 'categories', 'users'));
    }
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required',
            'status' => 'required|in:draft,published,archived',
            'category_id' => 'required|exists:categories,id',
        ]);
        $course->update($request->only(['title', 'status', 'category_id']));
        return redirect()->route('admin.courses.index')->with('success', 'Course updated.');
    }
    public function destroy(Course $course)
    {
        $course->delete();
        return back()->with('success', 'Course deleted.');
    }
} 