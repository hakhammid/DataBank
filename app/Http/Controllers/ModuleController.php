<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Department;
use App\Models\Module;
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

    public function createModuleView()
    {
        $courses = Course::all();
        $departments = Department::all();

        return view('faculty.partials.add_new_module', [
            'courses' => $courses,
            'departments' => $departments
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
            'departments' => $departments
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
                'course_id' => 'required|exists:courses,id',
                'department_id' => 'required|exists:departments,id',
                'file' => 'required|mimes:pdf|max:204800'
            ], [
                'course_code.required' => 'Please enter a course code.',
                'file.required' => 'Please upload a PDF file.',
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

            Module::create($cleanData);

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
                        Module::create([
                            'course_code' => $sharedCourseCode,
                            'title' => $baseTitle,
                            'file' => $fileName,
                            'isMajor' => strip_tags($validatedData['isMajor']),
                            'semester' => strip_tags($validatedData['semester']),
                            'course_id' => strip_tags($validatedData['course_id']),
                            'department_id' => strip_tags($validatedData['department_id']),
                            'user_id' => Auth::user()->id
                        ]);

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

    /**
     * Generate exactly like Google Classroom codes
     * 6-7 characters, alphanumeric, uppercase
     */
    private function generateGoogleClassroomCode(): string
    {
        $length = rand(6, 7);
        $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $charactersLength = strlen($characters);

        do {
            $code = '';
            for ($i = 0; $i < $length; $i++) {
                $code .= $characters[rand(0, $charactersLength - 1)];
            }

            $exists = Module::where('course_code', $code)->exists();
        } while ($exists);

        return $code;
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
}
