<?php

namespace App\Http\Controllers\Admin;

use App\Models\Course;
use App\Models\Module;
use App\Models\Department;
use App\Models\ModuleEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use App\Services\NotificationService;

class ModuleController extends Controller
{
    public function index(Request $request)
    {
        $query = Module::with(['user' => function ($q) {
            $q->select('id', 'first_name', 'middle_initial', 'last_name', 'profile_picture');
        }, 'department', 'courses']);

        // Apply filters
        if ($request->filled('course_code')) {
            $query->where('course_code', $request->course_code);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('course_id')) {
            $query->whereHas('courses', function ($q) use ($request) {
                $q->where('courses.id', $request->course_id);
            });
        }

        $publishedQuery = clone $query;
        $publishedQuery->where('status', 'published');
        $publishedModules = $publishedQuery->latest()->paginate(10, ['*'], 'published_page')->appends($request->query());

        $pendingQuery = clone $query;
        $pendingQuery->where('status', 'pending');
        $pendingModules = $pendingQuery->latest()->paginate(10, ['*'], 'pending_page')->appends($request->query());

        $rejectedQuery = clone $query;
        $rejectedQuery->where('status', 'rejected');
        $rejectedModules = $rejectedQuery->latest()->paginate(10, ['*'], 'rejected_page')->appends($request->query());
        // Get filter options
        $courseCodes = Module::select('course_code')->distinct()->orderBy('course_code')->pluck('course_code');
        $departments = Department::orderBy('department_name')->get();
        $courses = Course::orderBy('course_name')->get();

        if ($publishedModules->isNotEmpty()) {
            $firstModule = $publishedModules->first();
            Log::info('Module Data:', [
                'module_id'      => $firstModule->id,
                'user_id'        => $firstModule->user_id,
                'has_user'       => $firstModule->user ? true : false,
                'user_name'      => $firstModule->user ? $firstModule->user->name : 'No user',
                'user_profile'   => $firstModule->user ? $firstModule->user->profile_picture : 'No profile',
            ]);
        }
        return view('admin.admin_manage_module', [
            'publishedModules' => $publishedModules,
            'pendingModules'   => $pendingModules,
            'rejectedModules'  => $rejectedModules,
            'courseCodes'      => $courseCodes,
            'departments'      => $departments,
            'courses'          => $courses,
        ]);
    }

    public function create()
    {
        $courses = Course::all();
        $departments = Department::all();

        return view('admin.partials.admin_add_module', [
            'courses' => $courses,
            'departments' => $departments
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'course_code'   => 'required|string|max:255',
                'title'         => 'required|string|max:255',
                'isMajor'       => 'required|in:0,1',
                'semester'      => 'required|in:1st,2nd',
                'course_ids'    => 'required|array|min:1',
                'course_ids.*'  => 'exists:courses,id',
                'department_id' => 'required|exists:departments,id',
                'file'          => 'required|mimes:pdf|max:204800',
                'enrolled_students' => 'required|array|min:1',
                'enrolled_students.*' => 'exists:users,id',
            ], [
                'course_code.required' => 'Please enter a course code.',
                'course_ids.required'  => 'Please select at least one degree program.',
                'course_ids.min'       => 'Please select at least one degree program.',
                'file.required' => 'Please upload a PDF file.',
                'file.mimes'    => 'The file must be a PDF.',
                'file.max'      => 'The file size must not exceed 200MB.',
                'enrolled_students.required' => 'Please enroll at least one student.',
                'enrolled_students.min'      => 'Please enroll at least one student.',
            ]);

            $cleanData = [
                'course_code'   => strip_tags($validatedData['course_code']),
                'title'         => strip_tags($validatedData['title']),
                'isMajor'       => strip_tags($validatedData['isMajor']),
                'semester'      => strip_tags($validatedData['semester']),
                'department_id' => strip_tags($validatedData['department_id']),
                'user_id'       => Auth::user()->id,
                'status'        => 'published'
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

            // Notify eligible students about the new published module
            NotificationService::notifyStudentsOfNewModule($module);

            $request->session()->forget('file_info');

            return redirect()->route('admin.modules')->with('success', 'Module created successfully');
        } catch (ValidationException $e) {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $request->session()->put('file_info', [
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'type' => $file->getMimeType()
                ]);
            }

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating module: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to create module. Please try again.')
                ->withInput();
        }
    }

    public function storeMultiple(Request $request)
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
                'course_ids.required'  => 'Please select at least one degree program.',
                'course_ids.min'       => 'Please select at least one degree program.',
                'files.required' => 'Please upload at least one PDF file.',
                'files.min' => 'Please upload at least one PDF file.',
                'files.*.mimes' => 'All files must be PDFs.',
                'files.*.max' => 'Each file size must not exceed 200MB.',
                'enrolled_students.required' => 'Please enroll at least one student.',
                'enrolled_students.min'      => 'Please enroll at least one student.',
            ]);

            DB::beginTransaction();

            try {
                $uploadedCount = 0;
                $failedFiles = [];
                $baseTitle = strip_tags($validatedData['title']);

                $sharedCourseCode = strip_tags($validatedData['course_code']);

                foreach ($request->file('files') as $index => $file) {
                    try {
                        $fileName = time() . '_' . $index . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $file->move(public_path('files'), $fileName);

                        $module = Module::create([
                            'course_code' => $sharedCourseCode,
                            'title' => $baseTitle,
                            'file' => $fileName,
                            'isMajor' => strip_tags($validatedData['isMajor']),
                            'semester' => strip_tags($validatedData['semester']),
                            'department_id' => strip_tags($validatedData['department_id']),
                            'user_id' => Auth::user()->id,
                            'status' => 'published'
                        ]);

                        // Sync courses (degree programs) via pivot
                        $module->courses()->sync($validatedData['course_ids']);

                        $uploadedCount++;
                    } catch (\Exception $e) {
                        $failedFiles[] = $file->getClientOriginalName();
                        Log::error('Failed to upload file: ' . $file->getClientOriginalName() . ' - ' . $e->getMessage());
                    }
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

                // Notify eligible students about the new published modules
                // Run after commit so the modules exist in the DB
                $allModules = Module::where('course_code', $sharedCourseCode)
                    ->where('user_id', Auth::id())
                    ->latest()
                    ->take($uploadedCount)
                    ->get();
                foreach ($allModules as $mod) {
                    NotificationService::notifyStudentsOfNewModule($mod);
                }

                $message = "Successfully created $uploadedCount modules.";
                if (!empty($failedFiles)) {
                    $message .= " Failed to upload: " . implode(', ', $failedFiles);
                }

                return redirect()->route('admin.modules')->with(empty($failedFiles) ? 'success' : 'warning', $message);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error in storeMultiple: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to process files. Please try again.')
                ->withInput();
        }
    }

    public function edit(Module $module)
    {
        $courses = Course::all();
        $departments = Department::all();

        // Get the currently selected course IDs from the pivot
        $selectedCourseIds = $module->courses()->pluck('courses.id')->toArray();

        // Get enrolled students for this course code
        $enrolledStudents = ModuleEnrollment::where('course_code', $module->course_code)
            ->with(['student:id,id_number,first_name,middle_initial,last_name,email,department_id,course_id',
                     'student.department:id,department_name',
                     'student.course:id,course_name'])
            ->get();

        return view('admin.partials.admin_edit_module', [
            'module'  => $module,
            'courses' => $courses,
            'departments' => $departments,
            'selectedCourseIds' => $selectedCourseIds,
            'enrolledStudents' => $enrolledStudents,
        ]);
    }

    public function update(Request $request, Module $module)
    {
        try {
            if (Auth::user()->usertype !== 'admin') {
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
                'course_ids.required'  => 'Please select at least one degree program.',
                'course_ids.min'       => 'Please select at least one degree program.',
                'file.mimes' => 'The file must be a PDF.',
                'file.max' => 'The file size must not exceed 200MB.',
                'enrolled_students.required' => 'Please enroll at least one student.',
                'enrolled_students.min'      => 'Please enroll at least one student.',
            ]);

            $cleanData = [
                'course_code' => strip_tags($validatedData['course_code']),
                'title' => strip_tags($validatedData['title']),
                'isMajor' => strip_tags($validatedData['isMajor']),
                'semester' => strip_tags($validatedData['semester']),
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

            $oldCourseCode = $module->course_code;
            $module->update($cleanData);

            // Sync courses (degree programs) via pivot
            $module->courses()->sync($validatedData['course_ids']);

            // Handle student enrollments
            ModuleEnrollment::where('course_code', $oldCourseCode)->delete();

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

            return redirect()->route('admin.modules')->with('success', 'Module updated successfully.');
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating module by admin: ' . $e->getMessage());
            return back()
                ->with('error', 'An error occurred while updating the module. Please try again.')
                ->withInput();
        }
    }

    public function updateStatus(Request $request, Module $module)
    {
        $validated = $request->validate([
            'status' => 'required|in:published,rejected'
        ]);

        $module->update([
            'status' => $validated['status']
        ]);

        // Notify students when a module is published (approved)
        if ($validated['status'] === 'published') {
            NotificationService::notifyStudentsOfNewModule($module);
            NotificationService::notifyFacultyOfPublishedModule($module);
        }

        $statusText = $validated['status'] === 'published' ? 'published' : 'rejected';
        return back()->with('success', "Module status updated to {$statusText} successfully.");
    }
}
