<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Display student profile
     */
    public function showProfile()
    {
        return view('student.student_profile', ['user' => Auth::user()]);
    }

    /**
     * Handle back navigation based on referer
     */
    public function back(Request $request)
    {
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        $referer = $request->headers->get('referer');
        
        return redirect()->route('student');
    }

    public function changePasswordView() {
        return view('student.partials.student_change_password');
    }
} 