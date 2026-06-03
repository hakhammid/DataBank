<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Course;
use App\Models\Department;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\Controller;

class StudentController extends Controller
{
    public function index()
    {
        $students = [];
        if (Auth::check()) {
            $students = (new User())->allStudent();
        }
        return view('admin.admin_manage_student', ['students' => $students]);
    }

    public function create()
    {
        $departments = Department::all();
        $courses     = Course::all();

        return view('admin.partials.admin_add_student', [
            'departments' => $departments,
            'courses'     => $courses,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_number' => ['required', 'string', 'max:255', 'unique:users,id_number'],
            'first_name'        => ['required', 'string', 'max:255'],
            'last_name'         => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'department_id' => ['required', 'exists:departments,id'],
            'course_id'     => ['required', 'exists:courses,id'],
            'profile_photo' => ['nullable', 'image', 'max:2048', 'mimes:jpeg,png,jpg,gif'],
            'password'      => ['required', Rules\Password::defaults()],
        ]);

        $imageName = null;

        if ($request->hasFile('profile_photo')) {
            $image = $request->file('profile_photo');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
        }

        $user = User::create([
            'id_number'         => $request->id_number,
            'first_name'        => $request->first_name,
            'middle_initial'    => $request->middle_initial,
            'last_name'         => $request->last_name,
            'email'             => $request->email,
            'department_id'   => $request->department_id,
            'course_id'       => $request->course_id,
            'profile_picture' => $imageName,
            'password'        => Hash::make($request->password),
            'usertype'        => 'student',
        ]);

        event(new Registered($user));

        if ($request->input('save_action') === 'add_another') {
            return redirect()->route('admin.student.create')->with('success', 'Student added successfully.');
        }

        return redirect()->route('admin.students')->with('success', 'Student added successfully.');
    }

    public function edit(User $student)
    {
        $departments = Department::all();
        $courses     = Course::all();

        return view('admin.partials.admin_edit_student', [
            'student'     => $student,
            'departments' => $departments,
            'courses'     => $courses,
        ]);
    }

    public function update(Request $request, User $student)
    {
        $validatedData = $request->validate([
            'first_name'      => 'required|string|max:255',
            'middle_initial'  => 'nullable|string|max:10',
            'last_name'       => 'required|string|max:255',
            'email'           => 'required|string|email|max:255|unique:users,email,' . $student->id,
            'department_id'   => 'required|exists:departments,id',
            'course_id'       => 'required|exists:courses,id',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $student->first_name    = $validatedData['first_name'];
        $student->middle_initial = $validatedData['middle_initial'];
        $student->last_name     = $validatedData['last_name'];
        $student->email         = $validatedData['email'];
        $student->department_id = $validatedData['department_id'];
        $student->course_id     = $validatedData['course_id'];

        if ($request->hasFile('profile_picture')) {
            if ($student->profile_picture && file_exists(public_path('images/' . $student->profile_picture))) {
                unlink(public_path('images/' . $student->profile_picture));
            }

            $image     = $request->file('profile_picture');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $student->profile_picture = $imageName;
        }

        $student->save();

        return redirect()->route('admin.students')->with('success', 'Student information updated successfully.');
    }

    public function destroy(Request $request)
    {
        try {
            $student = User::where('id', $request->student_id)
                ->where('usertype', 'student')
                ->first();

            if (! $student) {
                return back()->with('error', 'Student not found.');
            }

            Log::info('Admin attempting to delete student: ' . $student->id . ' - ' . $student->name);

            // With SoftDeletes, modules are not automatically soft-deleted unless cascaded, 
            // but we can manually soft-delete them if needed, or leave them. Let's cascade soft delete.
            Module::where('user_id', $student->id)->delete();

            $student->delete();

            return back()->with('success', 'Student deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting student: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete student. ' . $e->getMessage());
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getPathname(), "r");
        
        $header = fgetcsv($handle, 1000, ",");
        $successCount = 0;
        $failedCount = 0;

        // Basic header validation
        if (!$header || count($header) < 5) {
            fclose($handle);
            return back()->with('error', 'Invalid CSV format.');
        }

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            try {
                // Assuming standard format: id_number, first_name, last_name, email, department_id, course_id, password
                $idNumber = trim($data[0] ?? '');
                $email = strtolower(trim($data[3] ?? ''));
                
                if (empty($email) || empty($idNumber) || User::where('email', $email)->orWhere('id_number', $idNumber)->exists()) {
                    $failedCount++;
                    continue;
                }

                $user = User::create([
                    'id_number' => $idNumber,
                    'first_name' => trim($data[1] ?? ''),
                    'last_name' => trim($data[2] ?? ''),
                    'email' => $email,
                    'department_id' => trim($data[4] ?? null),
                    'course_id' => trim($data[5] ?? null),
                    'password' => Hash::make(trim($data[6] ?? 'password123')),
                    'usertype' => 'student',
                ]);

                event(new Registered($user));
                $successCount++;
            } catch (\Exception $e) {
                Log::error('Failed to import student from CSV: ' . $e->getMessage());
                $failedCount++;
            }
        }
        fclose($handle);

        $msg = "Imported $successCount students successfully.";
        if ($failedCount > 0) $msg .= " Failed to import $failedCount rows (likely duplicates or invalid format).";

        return back()->with('success', $msg);
    }
}
