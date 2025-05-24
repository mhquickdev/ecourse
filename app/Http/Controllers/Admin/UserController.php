<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = User::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users', 'search'));
    }

    public function students(Request $request)
    {
        $search = $request->input('search');
        $studentRoleId = Role::where('name', 'student')->first()->id ?? null;

        $query = User::query()->where('role_id', $studentRoleId);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $students = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users.students.index', compact('students', 'search'));
    }

    public function mentors(Request $request)
    {
        $search = $request->input('search');
        $mentorRoleId = Role::where('name', 'mentor')->first()->id ?? null;

        $query = User::query()->where('role_id', $mentorRoleId);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $mentors = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users.mentors.index', compact('mentors', 'search'));
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }
} 