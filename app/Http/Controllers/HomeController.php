<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index() {
        if (Auth::check()) {
            $usertype = Auth::user()->usertype;
            if($usertype == 'admin') {
                return redirect()->route('admin.dashboard');
            } else if($usertype == 'faculty') {
                // return view('faculty.faculty_home');
                return redirect()->route('faculty.home');
            } else {
                return redirect()->route('student');
            }
        } else {
            return redirect()->route('login'); 
        }
    }
}
