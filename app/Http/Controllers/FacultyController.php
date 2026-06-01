<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Module;
use App\Models\Department;
use App\Models\ModuleEnrollment;
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

        // Get all courses where this faculty has modules (via pivot)
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
                        ->whereHas('courses', function ($q) use ($selectedCourseId) {
                            $q->where('courses.id', $selectedCourseId);
                        })
                        ->where('course_code', $selectedCourseCode)
                        ->latest()
                        ->paginate(9)
                        ->appends(['course_id' => $selectedCourseId, 'course_code' => $selectedCourseCode]);
                } else {
                    // Level 2: Show course code cards for this course
                    $courseCodeGroups = Module::where('user_id', $user->id)
                        ->whereHas('courses', function ($q) use ($selectedCourseId) {
                            $q->where('courses.id', $selectedCourseId);
                        })
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

        // Get the currently selected course IDs from the pivot
        $selectedCourseIds = $module->courses()->pluck('courses.id')->toArray();

        // Get enrolled students for this course code
        $enrolledStudents = ModuleEnrollment::where('course_code', $module->course_code)
            ->where('enrolled_by', Auth::id())
            ->with(['student:id,id_number,first_name,middle_initial,last_name,email,department_id,course_id',
                     'student.department:id,department_name',
                     'student.course:id,course_name'])
            ->get();

        return view('faculty.partials.edit_module', [
            'module' => $module,
            'courses' => $courses,
            'departments' => $departments,
            'selectedCourseIds' => $selectedCourseIds,
            'enrolledStudents' => $enrolledStudents,
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
                'isMajor' => 'required|in:0,1',
                'semester' => 'required|in:1st,2nd',
                'course_ids' => 'required|array|min:1',
                'course_ids.*' => 'exists:courses,id',
                'department_id' => 'required|exists:departments,id',
                'file' => 'nullable|mimes:pdf|max:204800',
                'enrolled_students' => 'required|array|min:1',
                'enrolled_students.*' => 'exists:users,id',
            ], [
                'course_code.required' => 'Please enter a course code.',
                'course_ids.required' => 'Please select at least one degree program.',
                'course_ids.min' => 'Please select at least one degree program.',
                'file.mimes' => 'The file must be a PDF.',
                'file.max' => 'The file size must not exceed 200MB.',
                'enrolled_students.required' => 'Please enroll at least one student.',
                'enrolled_students.min' => 'Please enroll at least one student.',
            ]);

            $cleanData = [
                'course_code' => strip_tags($validatedData['course_code']),
                'title' => strip_tags($validatedData['title']),
                'isMajor' => strip_tags($validatedData['isMajor']),
                'semester' => strip_tags($validatedData['semester']),
                'department_id' => strip_tags($validatedData['department_id']),
                'status' => 'pending',
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

            // Sync courses (degree programs) via pivot
            $module->courses()->sync($validatedData['course_ids']);

            // Handle student enrollments — sync for this course code
            $oldCourseCode = $module->getOriginal('course_code') ?? $cleanData['course_code'];
            $newCourseCode = $cleanData['course_code'];

            // Remove old enrollments by this faculty for the old course code
            ModuleEnrollment::where('enrolled_by', Auth::id())
                ->where('course_code', $oldCourseCode)
                ->delete();

            // Create new enrollments
            if (!empty($validatedData['enrolled_students'])) {
                foreach ($validatedData['enrolled_students'] as $studentId) {
                    ModuleEnrollment::firstOrCreate([
                        'user_id' => $studentId,
                        'course_code' => $newCourseCode,
                    ], [
                        'enrolled_by' => Auth::id(),
                    ]);
                }
            }

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
