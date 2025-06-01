<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Fetch enrollments for the user
        $enrollments = $user->enrollments()->latest()->get();

        // You might want to process enrollments to extract transaction details
        // and pass a collection of transaction-like objects to the view.
        // For now, let's pass enrollments directly and handle in the view.

        return view('student.transactions.index', compact('enrollments'));
    }
} 