<?php

namespace App\Http\Controllers\Admin;

use App\Models\Course;
use App\Models\Module;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;

class ModuleController extends Controller
{
    public function index(Request $request)
    {
        $query = Module::with(['user' => function ($q) {
            $q->select('id', 'first_name', 'middle_initial', 'last_name', 'profile_picture');
        }, 'department', 'course']);

        // Apply filters
        if ($request->filled('course_code')) {
            $query->where('course_code', $request->course_code);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        $modules = $query->latest()->paginate(10)->appends($request->query());

        // Get filter options
        $courseCodes = Module::select('course_code')->distinct()->orderBy('course_code')->pluck('course_code');
        $departments = Department::orderBy('department_name')->get();
        $courses = Course::orderBy('course_name')->get();

        if ($modules->isNotEmpty()) {
            $firstModule = $modules->first();
            Log::info('Module Data:', [
                'module_id'      => $firstModule->id,
                'user_id'        => $firstModule->user_id,
                'has_user'       => $firstModule->user ? true : false,
                'user_name'      => $firstModule->user ? $firstModule->user->name : 'No user',
                'user_profile'   => $firstModule->user ? $firstModule->user->profile_picture : 'No profile',
            ]);
        }
        return view('admin.admin_manage_module', [
            'modules'     => $modules,
            'courseCodes' => $courseCodes,
            'departments' => $departments,
            'courses'     => $courses,
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
                'course_id'     => 'required|exists:courses,id',
                'department_id' => 'required|exists:departments,id',
                'file'          => 'required|mimes:pdf|max:204800'
            ], [
                'course_code.required' => 'Please enter a course code.',
                'file.required' => 'Please upload a PDF file.',
                'file.mimes'    => 'The file must be a PDF.',
                'file.max'      => 'The file size must not exceed 200MB.',
            ]);

            $cleanData = [
                'course_code'   => strip_tags($validatedData['course_code']),
                'title'         => strip_tags($validatedData['title']),
                'isMajor'       => strip_tags($validatedData['isMajor']),
                'semester'      => strip_tags($validatedData['semester']),
                'course_id'     => strip_tags($validatedData['course_id']),
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

            Module::create($cleanData);

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
            Log::error('Error creating multiple modules: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to create modules. Please try again.')
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
                'course_id' => 'required|exists:courses,id',
                'department_id' => 'required|exists:departments,id',
                'files' => 'required|array|min:1',
                'files.*' => 'required|mimes:pdf|max:204800'
            ], [
                'course_code.required' => 'Please enter a course code.',
                'files.required' => 'Please upload at least one PDF file.',
                'files.min' => 'Please upload at least one PDF file.',
                'files.*.mimes' => 'All files must be PDFs.',
                'files.*.max' => 'Each file size must not exceed 200MB.',
            ]);

            \Illuminate\Support\Facades\DB::beginTransaction();

            try {
                $uploadedCount = 0;
                $failedFiles = [];
                $baseTitle = strip_tags($validatedData['title']);

                $sharedCourseCode = strip_tags($validatedData['course_code']);

                foreach ($request->file('files') as $index => $file) {
                    try {
                        $fileName = time() . '_' . $index . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $file->move(public_path('files'), $fileName);

                        Module::create([
                            'course_code' => $sharedCourseCode,
                            'title' => $baseTitle,
                            'file' => $fileName,
                            'isMajor' => strip_tags($validatedData['isMajor']),
                            'semester' => strip_tags($validatedData['semester']),
                            'course_id' => strip_tags($validatedData['course_id']),
                            'department_id' => strip_tags($validatedData['department_id']),
                            'user_id' => Auth::user()->id,
                            'status' => 'published'
                        ]);

                        $uploadedCount++;
                    } catch (\Exception $e) {
                        $failedFiles[] = $file->getClientOriginalName();
                        Log::error('Failed to upload file: ' . $file->getClientOriginalName() . ' - ' . $e->getMessage());
                    }
                }

                \Illuminate\Support\Facades\DB::commit();

                $message = "Successfully created $uploadedCount modules.";
                if (!empty($failedFiles)) {
                    $message .= " Failed to upload: " . implode(', ', $failedFiles);
                }

                return redirect()->route('admin.modules')->with(empty($failedFiles) ? 'success' : 'warning', $message);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\DB::rollBack();
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

        return view('admin.partials.admin_edit_module', [
            'module'  => $module,
            'courses' => $courses,
            'departments' => $departments
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
                'isMajor' => strip_tags($validatedData['isMajor']),
                'semester' => strip_tags($validatedData['semester']),
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

            return redirect()->route('admin.modules')->with('success', 'Module updated successfully.');
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

    public function updateStatus(Request $request, Module $module)
    {
        $validated = $request->validate([
            'status' => 'required|in:published,rejected'
        ]);

        $module->update([
            'status' => $validated['status']
        ]);

        $statusText = $validated['status'] === 'published' ? 'published' : 'rejected';
        return back()->with('success', "Module status updated to {$statusText} successfully.");
    }
}
