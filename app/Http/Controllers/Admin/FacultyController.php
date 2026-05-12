<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Module;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\Controller;

class FacultyController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $faculties = User::where('usertype', 'faculty')
            ->with('department')
            ->withCount('modules')
            ->paginate(10);

        return view('admin.admin_manage_faculty', compact('faculties'));
    }

    public function create()
    {
        $departments = Department::all();

        return view('admin.partials.admin_add_faculty', [
            'departments' => $departments,
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
            'department_id'     => $request->department_id,
            'profile_picture'   => $imageName,
            'password'          => Hash::make($request->password),
            'usertype'          => 'faculty',
        ]);

        event(new Registered($user));

        return redirect()->route('admin.faculties')->with('success', 'Faculty added successfully.');
    }

    public function edit(User $faculty)
    {
        $departments = Department::all();

        return view('admin.partials.admin_edit_faculty', [
            'faculty'     => $faculty,
            'departments' => $departments,
        ]);
    }

    public function update(Request $request, User $faculty)
    {
        $validatedData = $request->validate([
            'first_name'      => 'required|string|max:255',
            'middle_initial'  => 'nullable|string|max:10',
            'last_name'       => 'required|string|max:255',
            'email'           => 'required|string|email|max:255|unique:users,email,' . $faculty->id,
            'department_id'   => 'required|exists:departments,id',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $faculty->first_name    = $validatedData['first_name'];
        $faculty->middle_initial = $validatedData['middle_initial'];
        $faculty->last_name     = $validatedData['last_name'];
        $faculty->email         = $validatedData['email'];
        $faculty->department_id = $validatedData['department_id'];

        if ($request->hasFile('profile_picture')) {
            if ($faculty->profile_picture && file_exists(public_path('images/' . $faculty->profile_picture))) {
                unlink(public_path('images/' . $faculty->profile_picture));
            }

            $image     = $request->file('profile_picture');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $faculty->profile_picture = $imageName;
        }

        $faculty->save();

        return redirect()->route('admin.faculties')->with('success', 'Faculty information updated successfully.');
    }

    public function destroy(Request $request)
    {
        try {
            $faculty = User::where('id', $request->faculty_id)
                ->where('usertype', 'faculty')
                ->first();

            if (! $faculty) {
                return back()->with('error', 'Faculty not found.');
            }

            Log::info('Admin attempting to delete faculty: ' . $faculty->id . ' - ' . $faculty->name);

            Module::where('user_id', $faculty->id)->delete();

            $faculty->delete();

            return back()->with('success', 'Faculty deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting faculty: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete faculty. ' . $e->getMessage());
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
        if (!$header || count($header) < 4) {
            fclose($handle);
            return back()->with('error', 'Invalid CSV format.');
        }

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            try {
                // Assuming standard format: id_number, first_name, last_name, email, department_id, password
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
                    'password' => Hash::make(trim($data[5] ?? 'password123')),
                    'usertype' => 'faculty',
                ]);

                event(new Registered($user));
                $successCount++;
            } catch (\Exception $e) {
                Log::error('Failed to import faculty from CSV: ' . $e->getMessage());
                $failedCount++;
            }
        }
        fclose($handle);

        $msg = "Imported $successCount faculties successfully.";
        if ($failedCount > 0) $msg .= " Failed to import $failedCount rows (likely duplicates or invalid format).";

        return back()->with('success', $msg);
    }
}
