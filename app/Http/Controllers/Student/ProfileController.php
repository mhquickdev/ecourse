<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $student = Auth::user();
        return view('student.profile', compact('student'));
    }

    public function update(Request $request)
    {
        $student = Auth::user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $student->id,
            'phone' => 'required|string|max:255',
            'dob' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'bio' => 'required|string',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        $student->name = $validated['name'];
        $student->username = $validated['username'];
        $student->phone = $validated['phone'];
        $student->dob = $validated['dob'];
        $student->gender = $validated['gender'];
        $student->bio = $validated['bio'];

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile-images', 'public');
            $student->profile_image = $path;
        }

        $student->save();

        return redirect()->route('student.profile')->with('success', 'Profile updated successfully.');
    }
} 