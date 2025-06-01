<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistItems = Auth::user()->wishlists()->with('course')->latest()->paginate(10);
        return view('student.wishlist', compact('wishlistItems'));
    }

    public function add(Course $course)
    {
        if (Auth::user()->wishlists()->where('course_id', $course->id)->exists()) {
            return redirect()->back()->with('error', 'Course already in wishlist.');
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'course_id' => $course->id,
        ]);

        return redirect()->back()->with('success', 'Course added to wishlist!');
    }

    public function remove(Course $course)
    {
        Wishlist::where('user_id', Auth::id())
                ->where('course_id', $course->id)
                ->delete();

        return redirect()->back()->with('success', 'Course removed from wishlist.');
    }

     // Method to toggle wishlist status via AJAX
    public function toggle(Course $course)
    {
        $wishlistItem = Wishlist::where('user_id', Auth::id())
                                ->where('course_id', $course->id)
                                ->first();

        if ($wishlistItem) {
            $wishlistItem->delete();
            return response()->json(['status' => 'removed', 'message' => 'Course removed from wishlist.']);
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'course_id' => $course->id,
            ]);
            return response()->json(['status' => 'added', 'message' => 'Course added to wishlist!']);
        }
    }
}
