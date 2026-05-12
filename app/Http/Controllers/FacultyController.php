<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Module;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class FacultyController extends Controller
{
    /**
     * Display faculty's modules
     */
    public function homeView(Request $request)
{
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    /** @var User $user */
    $user = Auth::user();

    // Get selected course and course_code from query parameters
    $selectedCourseId = $request->query('course_id');
    $selectedCourseCode = $request->query('course_code');

    // Get all courses where this faculty has modules
    $coursesWithModules = Course::whereHas('modules', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->withCount(['modules' => function ($query) use ($user) {
            $query->where('user_id', $user->id);
        }])
        ->orderBy('course_name')
        ->get();

    $modules = collect();
    $selectedCourse = null;
    $courseCodeGroups = collect();

    if ($selectedCourseId) {
        $selectedCourse = Course::find($selectedCourseId);

        if ($selectedCourse) {
            if ($selectedCourseCode) {
                // Level 3: Show modules for specific course code
                $modules = $user->facultyModule()
                    ->where('course_id', $selectedCourseId)
                    ->where('course_code', $selectedCourseCode)
                    ->latest()
                    ->paginate(9)
                    ->appends(['course_id' => $selectedCourseId, 'course_code' => $selectedCourseCode]);
            } else {
                // Level 2: Show course code cards for this course
                $courseCodeGroups = Module::where('user_id', $user->id)
                    ->where('course_id', $selectedCourseId)
                    ->select('course_code')
                    ->selectRaw('COUNT(*) as modules_count')
                    ->groupBy('course_code')
                    ->orderBy('course_code')
                    ->get();
            }
        }
    }

    return view('faculty.faculty_home', [
        'coursesWithModules' => $coursesWithModules,
        'modules' => $modules,
        'selectedCourse' => $selectedCourse,
        'selectedCourseCode' => $selectedCourseCode,
        'courseCodeGroups' => $courseCodeGroups,
    ]);
}

    /**
     * Display faculty profile
     */
    public function showProfile()
    {
        $departments = Department::all();

        return view(
            'faculty.faculty_profile',
            [
                'departments' => $departments
            ]
        );
    }

    public function changePasswordView()
    {
        return view('faculty.partials.change_password_view');
    }

    public function editModuleView(Module $module)
    {
        // Check if user is authorized to edit this module
        if (Auth::user()->id !== $module->user_id) {
            return redirect()->back()->with('error', 'You are not authorized to edit this module.');
        }

        $courses = Course::all();
        $departments = Department::all();

        return view('faculty.partials.edit_module', [
            'module' => $module,
            'courses' => $courses,
            'departments' => $departments,
        ]);
    }

    public function updateModule(Module $module, Request $request)
    {
        try {
            if (Auth::user()->id !== $module->user_id) {
                return redirect()->back()->with('error', 'You are not authorized to edit this module.');
            }

            $validatedData = $request->validate([
                'course_code' => 'required|string|max:255',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'isMajor' => 'required|in:0,1',
                'semester' => 'required|in:1st,2nd',
                'status' => 'required|in:draft,published',
                'course_id' => 'required|exists:courses,id',
                'department_id' => 'required|exists:departments,id',
                'file' => 'nullable|mimes:pdf|max:204800'
            ], [
                'course_code.required' => 'Please enter a course code.',
                'file.mimes' => 'The file must be a PDF.',
                'file.max' => 'The file size must not exceed 200MB.',
            ]);

            $cleanData = [
                'course_code' => strip_tags($validatedData['course_code']),
                'title' => strip_tags($validatedData['title']),
                'description' => strip_tags($validatedData['description'] ?? ''),
                'isMajor' => strip_tags($validatedData['isMajor']),
                'semester' => strip_tags($validatedData['semester']),
                'status' => strip_tags($validatedData['status']),
                'course_id' => strip_tags($validatedData['course_id']),
                'department_id' => strip_tags($validatedData['department_id']),
            ];

            if ($request->hasFile('file')) {
                $oldFilePath = public_path('files/' . $module->file);
                if ($module->file && file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }

                $fileName = time() . '_' . uniqid() . '.' . $request->file('file')->getClientOriginalExtension();
                $request->file('file')->move(public_path('files'), $fileName);
                $cleanData['file'] = $fileName;
            }

            $module->update($cleanData);

            return redirect()->route('faculty.home')->with('success', 'Module updated successfully.');
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating module by faculty: ' . $e->getMessage());
            return back()
                ->with('error', 'An error occurred while updating the module. Please try again.')
                ->withInput();
        }
    }
}
