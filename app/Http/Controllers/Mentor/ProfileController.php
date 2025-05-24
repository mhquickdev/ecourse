<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $mentor = Auth::user();
        return view('mentor.profile', compact('mentor'));
    }

    public function update(Request $request)
    {
        $mentor = Auth::user();

        // Password reset logic
        if ($request->filled('current_password') || $request->filled('new_password') || $request->filled('new_password_confirmation')) {
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|min:8|confirmed',
            ]);
            if (!Hash::check($request->current_password, $mentor->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
            $mentor->password = bcrypt($request->new_password);
            $mentor->save();
            return back()->with('success', 'Password updated successfully.');
        }

        // Profile update logic
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $mentor->id,
            'phone' => 'required|string|max:255',
            'bio' => 'required|string',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        $mentor->first_name = $validated['first_name'];
        $mentor->last_name = $validated['last_name'];
        $mentor->username = $validated['username'];
        $mentor->phone = $validated['phone'];
        $mentor->bio = $validated['bio'];

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile-images', 'public');
            $mentor->profile_image = $path;
        }

        // Save education and experience as arrays (let Eloquent handle JSON)
        $mentor->education = array_values((array) $request->input('education', []));
        $mentor->experience = array_values((array) $request->input('experience', []));
        $mentor->skills = array_values((array) $request->input('skills', []));

        $mentor->save();

        return redirect()->route('mentor.profile')->with('success', 'Profile updated successfully.');
    }
} 