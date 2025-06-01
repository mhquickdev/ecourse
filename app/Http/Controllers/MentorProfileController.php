<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class MentorProfileController extends Controller
{
    public function show(User $mentor)
    {
        // Ensure the user is actually a mentor (or has courses)
        if (!$mentor->courses()->count()) {
            abort(404);
        }

        return view('pages.mentor-profile', compact('mentor'));
    }
}
