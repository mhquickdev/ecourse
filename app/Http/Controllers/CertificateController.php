<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function verify($uuid)
    {
        $certificate = Certificate::where('uuid', $uuid)->with(['user', 'course.mentor'])->first();

        if (!$certificate) {
            // Handle not found case (e.g., show a 404 page or a custom error view)
            abort(404, 'Certificate not found.');
        }

        // Show the certificate verification view
        return view('certificates.verify', compact('certificate'));
    }
} 