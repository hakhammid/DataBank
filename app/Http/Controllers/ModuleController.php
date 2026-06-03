<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Department;
use App\Models\Module;
use App\Models\ModuleEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ModuleController extends Controller
{
    public function browseModules()
    {
        return view('student.student_home');
    }



    public function deleteModule(Module $module, Request $request)
    {
        try {
            if (!Auth::user()->user_type === 'admin' && Auth::user()->id !== $module->user_id) {
                return response()->json([
                    'message' => 'Unauthorized to delete this module'
                ], 403);
            }

            if (file_exists(public_path('files/' . $module->file))) {
                unlink(public_path('files/' . $module->file));
            }

            $module->delete();

            $redirectPath = $request->query('redirect_to');

            if ($redirectPath) {
                return redirect($redirectPath)->with('success', 'Module deleted successfully');
            } else if (Auth::user()->is_admin) {
                return redirect('admin/modules')->with('success', 'Module deleted successfully');
            } else {
                return redirect()->back()->with('success', 'Module deleted successfully');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete module. Please try again.');
        }
    }

    public function createModuleView(Request $request)
    {
        $courses = Course::all();
        $departments = Department::all();

        $prefill = null;
        $prefillCourseCode = $request->query('course_code');
        $prefillCourseId = $request->query('course_id');

        if ($prefillCourseCode && Auth::check()) {
            /** @var User $user */
            $user = Auth::user();

            // Find an existing module by this faculty with this course code
            $existingModule = Module::where('user_id', $user->id)
                ->where('course_code', $prefillCourseCode)
                ->first();

            if ($existingModule) {
                // Get degree program IDs from the existing module's pivot
                $prefillCourseIds = $existingModule->courses()->pluck('courses.id')->toArray();

                // Get enrolled students for this course code by this faculty
                $prefillStudents = ModuleEnrollment::where('course_code', $prefillCourseCode)
                    ->where('enrolled_by', $user->id)
                    ->with(['student:id,id_number,first_name,middle_initial,last_name,email,department_id,course_id',
                             'student.department:id,department_name',
                             'student.course:id,course_name'])
                    ->get()
                    ->map(function ($enrollment) {
                        $s = $enrollment->student;
                        if (!$s) return null;
                        return [
                            'id' => $s->id,
                            'id_number' => $s->id_number,
                            'name' => $s->name,
                            'email' => $s->email,
                            'department' => $s->department ? $s->department->department_name : 'N/A',
                            'course' => $s->course ? $s->course->course_name : 'N/A',
                        ];
                    })
                    ->filter()
                    ->values();

                $prefill = [
                    'course_code' => $prefillCourseCode,
                    'course_id' => $prefillCourseId,
                    'department_id' => $existingModule->department_id,
                    'course_ids' => $prefillCourseIds,
                    'isMajor' => $existingModule->isMajor,
                    'semester' => $existingModule->semester,
                    'students' => $prefillStudents,
                ];
            }
        }

        return view('faculty.partials.add_new_module', [
            'courses' => $courses,
            'departments' => $departments,
            'prefill' => $prefill,
        ]);
    }

    /**
     * Show the form for creating multiple modules
     */
    public function createMultipleModuleView()
    {
        $courses = Course::all();
        $departments = Department::all();

        return view('faculty.partials.add_new_module', [
            'courses' => $courses,
            'departments' => $departments,
            'prefill' => null,
        ]);
    }

    public function createModule(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'course_code' => 'required|string|max:255',
                'title' => 'required|string|max:255',
                'isMajor' => 'required|in:0,1',
                'semester' => 'required|in:1st,2nd',
                'course_ids' => 'required|array|min:1',
                'course_ids.*' => 'exists:courses,id',
                'department_id' => 'required|exists:departments,id',
                'file' => 'required|mimes:pdf|max:204800',
                'enrolled_students' => 'required|array|min:1',
                'enrolled_students.*' => 'exists:users,id',
            ], [
                'course_code.required' => 'Please enter a course code.',
                'course_ids.required' => 'Please select at least one degree program.',
                'course_ids.min' => 'Please select at least one degree program.',
                'file.required' => 'Please upload a PDF file.',
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
                'user_id' => Auth::user()->id
            ];

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('files'), $fileName);
                $cleanData['file'] = $fileName;
            }

            if (isset($cleanData['file'])) {
                session()->flash('success', 'Module created successfully');
            } else {
                session()->flash('error', 'Failed to upload file');
                return redirect()->back()->withInput();
            }

            $module = Module::create($cleanData);

            // Sync courses (degree programs) via pivot
            $module->courses()->sync($validatedData['course_ids']);

            // Handle student enrollments
            if (!empty($validatedData['enrolled_students'])) {
                foreach ($validatedData['enrolled_students'] as $studentId) {
                    ModuleEnrollment::firstOrCreate([
                        'user_id' => $studentId,
                        'course_code' => $cleanData['course_code'],
                    ], [
                        'enrolled_by' => Auth::id(),
                    ]);
                }
            }

            return redirect()->route('faculty.home')->with('success', 'Module created successfully');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create module. Please try again.')
                ->withInput();
        }
    }

    /**
     * Create multiple modules with the same course code
     */
    public function createMultipleModules(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'course_code' => 'required|string|max:255',
                'title' => 'required|string|max:255',
                'isMajor' => 'required|in:0,1',
                'semester' => 'required|in:1st,2nd',
                'course_ids' => 'required|array|min:1',
                'course_ids.*' => 'exists:courses,id',
                'department_id' => 'required|exists:departments,id',
                'files' => 'required|array|min:1',
                'files.*' => 'required|mimes:pdf|max:204800',
                'enrolled_students' => 'required|array|min:1',
                'enrolled_students.*' => 'exists:users,id',
            ], [
                'course_code.required' => 'Please enter a course code.',
                'course_ids.required' => 'Please select at least one degree program.',
                'course_ids.min' => 'Please select at least one degree program.',
                'files.required' => 'Please upload at least one PDF file.',
                'files.min' => 'Please upload at least one PDF file.',
                'files.*.mimes' => 'All files must be PDFs.',
                'files.*.max' => 'Each file size must not exceed 200MB.',
                'enrolled_students.required' => 'Please enroll at least one student.',
                'enrolled_students.min' => 'Please enroll at least one student.',
            ]);

            DB::beginTransaction();

            try {
                $uploadedCount = 0;
                $failedFiles = [];
                $baseTitle = strip_tags($validatedData['title']);

                // Use the course code provided by the teacher
                $sharedCourseCode = strip_tags($validatedData['course_code']);

                foreach ($request->file('files') as $index => $file) {
                    try {
                        $fileName = time() . '_' . $index . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $file->move(public_path('files'), $fileName);

                        // All modules use the same title and course code
                        $module = Module::create([
                            'course_code' => $sharedCourseCode,
                            'title' => $baseTitle,
                            'file' => $fileName,
                            'isMajor' => strip_tags($validatedData['isMajor']),
                            'semester' => strip_tags($validatedData['semester']),
                            'department_id' => strip_tags($validatedData['department_id']),
                            'user_id' => Auth::user()->id
                        ]);

                        // Sync courses (degree programs) via pivot
                        $module->courses()->sync($validatedData['course_ids']);

                        $uploadedCount++;
                    } catch (\Exception $e) {
                        $failedFiles[] = $file->getClientOriginalName();
                        Log::error('Failed to upload file: ' . $file->getClientOriginalName() . ' - ' . $e->getMessage());
                    }
                }

                if ($uploadedCount === 0) {
                    DB::rollBack();
                    return redirect()->back()
                        ->with('error', 'Failed to create any modules. Please try again.')
                        ->withInput();
                }

                // Handle student enrollments (once for the course code)
                if (!empty($validatedData['enrolled_students'])) {
                    foreach ($validatedData['enrolled_students'] as $studentId) {
                        ModuleEnrollment::firstOrCreate([
                            'user_id' => $studentId,
                            'course_code' => $sharedCourseCode,
                        ], [
                            'enrolled_by' => Auth::id(),
                        ]);
                    }
                }

                DB::commit();

                $message = "$uploadedCount module" . ($uploadedCount > 1 ? 's' : '') . " created successfully with course code: $sharedCourseCode";
                if (count($failedFiles) > 0) {
                    $message .= ". Failed to upload: " . implode(', ', $failedFiles);
                }

                return redirect()->route('faculty.home')->with('success', $message);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating multiple modules: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to create modules. Please try again.')
                ->withInput();
        }
    }


    public function viewModule(Module $module, Request $request)
    {
        if (Auth::user()->usertype === 'student' && $module->status !== 'published') {
            abort(403, 'This module is pending approval and is not viewable by students.');
        }

        $module->increment('number_of_views');

        $source = $request->query('source', 'student');

        $downloadsToday = Auth::user()->moduleDownloads()
            ->whereDate('downloaded_at', now())
            ->count();
        $remainingQuota = max(0, 5 - $downloadsToday);

        return view('student.view-module', compact('module', 'source', 'remainingQuota'));
    }

    /**
     * Search students for enrollment (AJAX).
     */
    public function searchStudents(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $students = User::where('usertype', 'student')
            ->where(function ($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%")
                  ->orWhere('id_number', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->with(['department:id,department_name', 'course:id,course_name'])
            ->select('id', 'id_number', 'first_name', 'middle_initial', 'last_name', 'email', 'department_id', 'course_id')
            ->limit(20)
            ->get()
            ->map(function ($student) {
                return [
                    'id' => $student->id,
                    'id_number' => $student->id_number,
                    'name' => $student->name,
                    'email' => $student->email,
                    'department' => $student->department ? $student->department->department_name : 'N/A',
                    'course' => $student->course ? $student->course->course_name : 'N/A',
                ];
            });

        return response()->json($students);
    }

    /**
     * Enroll students into a course code.
     */
    public function enrollStudents(Request $request)
    {
        $validated = $request->validate([
            'course_code' => 'required|string|max:255',
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:users,id',
        ]);

        $enrolled = 0;
        foreach ($validated['student_ids'] as $studentId) {
            $enrollment = ModuleEnrollment::firstOrCreate([
                'user_id' => $studentId,
                'course_code' => $validated['course_code'],
            ], [
                'enrolled_by' => Auth::id(),
            ]);

            if ($enrollment->wasRecentlyCreated) {
                $enrolled++;
            }
        }

        return response()->json([
            'message' => "$enrolled student(s) enrolled successfully.",
            'enrolled_count' => $enrolled,
        ]);
    }

    /**
     * Remove a student enrollment.
     */
    public function removeEnrollment(ModuleEnrollment $enrollment)
    {
        $enrollment->delete();

        return response()->json([
            'message' => 'Enrollment removed successfully.',
        ]);
    }
}
