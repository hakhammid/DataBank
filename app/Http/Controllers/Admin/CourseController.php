<?php

namespace App\Http\Controllers\Admin;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class CourseController extends Controller
{
    public function index()
    {
        $courses = [];
        if (Auth::check()) {
            $courses = (new Course())->allCourses();
        }

        return view('admin.admin_manage_course', ['courses' => $courses]);
    }

    public function create()
    {
        return view('admin.partials.admin_add_degree_program');
    }

    public function store(Request $request)
    {
        $request->validate([
            'degree_program' => ['required', 'string', 'max:500'],
        ]);

        Course::create([
            'course_name' => $request->degree_program,
        ]);

        return redirect()->route('admin.degree-program')
            ->with('success', 'Degree program created successfully.');
    }

    public function edit(Course $course)
    {
        return view('admin.partials.admin_edit_degree_program', ['course' => $course]);
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'degree_program' => ['required', 'string', 'max:500'],
        ]);

        $course->course_name = $request->degree_program;
        $course->save();

        return redirect()->route('admin.degree-program')
            ->with('success', 'Degree program updated successfully.');
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('admin.degree-program')
            ->with('success', 'Degree program deleted successfully.');
    }
}
