<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class PublicMentorController extends Controller
{
    public function index()
    {
        $mentors = User::where('role_id', 3)->withCount('courses')->get();
        return view('pages.all-mentors', compact('mentors'));
    }
}
