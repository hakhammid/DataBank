<?php

namespace App\Http\Controllers\Admin;

use App\Models\Course;
use App\Models\Department;
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
        $departments = Department::all();

        return view('admin.partials.admin_add_degree_program', [
            'departments' => $departments,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'degree_program' => ['required', 'string', 'max:500'],
            'department_id'  => ['required', 'exists:departments,id'],
        ]);

        Course::create([
            'course_name'   => $request->degree_program,
            'department_id' => $request->department_id,
        ]);

        return redirect()->route('admin.degree-program')
            ->with('success', 'Degree program created successfully.');
    }

    public function edit(Course $course)
    {
        $departments = Department::all();

        return view('admin.partials.admin_edit_degree_program', [
            'course'      => $course,
            'departments' => $departments,
        ]);
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'degree_program' => ['required', 'string', 'max:500'],
            'department_id'  => ['required', 'exists:departments,id'],
        ]);

        $course->course_name   = $request->degree_program;
        $course->department_id = $request->department_id;
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

    /**
     * API: Get courses (degree programs) by department ID.
     */
    public function getByDepartment(Department $department)
    {
        $courses = $department->courses()->select('id', 'course_name')->get();

        return response()->json($courses);
    }
}
