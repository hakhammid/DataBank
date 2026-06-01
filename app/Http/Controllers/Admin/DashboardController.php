<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Module;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $studentCount = User::where('usertype', 'student')->count();
        $facultyCount = User::where('usertype', 'faculty')->count();
        $moduleCount  = Module::count();

        $departments = Department::withCount('modules')->orderBy('modules_count', 'desc')->get();

        return view('admin.admin_home', [
            'studentCount'    => $studentCount,
            'facultyCount'    => $facultyCount,
            'moduleCount'     => $moduleCount,
            'departmentsData' => $departments,
        ]);
    }

    public function showProfile()
    {
        return view('admin.admin_profile');
    }

    public function changePasswordView()
    {
        return view('admin.partials.admin_change_password');
    }

    public function updateAdmin(Request $request, User $admin)
    {
        try {
            $validatedData = $request->validate([
                'first_name'  => 'required|string|max:255',
                'middle_initial' => 'nullable|string|max:10',
                'last_name'   => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $admin->id,
            ]);

            $admin->first_name     = $validatedData['first_name'];
            $admin->middle_initial = $validatedData['middle_initial'];
            $admin->last_name      = $validatedData['last_name'];
            $admin->email          = $validatedData['email'];

            if ($request->hasFile('profile_photo')) {
                if ($admin->profile_picture && file_exists(public_path('images/' . $admin->profile_picture))) {
                    unlink(public_path('images/' . $admin->profile_picture));
                }

                $image     = $request->file('profile_photo');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);
                $admin->profile_picture = $imageName;
            }

            $admin->save();

            return redirect()->back()
                ->with('success', 'Admin information updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating admin: ' . $e->getMessage());
            return back()->with('error', 'Failed to update admin information. Please try again.');
        }
    }

    public function deletePhoto(User $admin)
    {
        try {
            if ($admin->profile_picture && file_exists(public_path('images/' . $admin->profile_picture))) {
                unlink(public_path('images/' . $admin->profile_picture));
                $admin->profile_picture = null;
                $admin->save();
            }

            return redirect()->back()
                ->with('success', 'Profile picture deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting profile picture: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete profile picture. Please try again.');
        }
    }
}
