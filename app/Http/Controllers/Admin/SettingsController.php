<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }
    public function update(Request $request)
    {
        // Save settings logic here (for now, just flash message)
        return back()->with('success', 'Settings updated.');
    }
} 