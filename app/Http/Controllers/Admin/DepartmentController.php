<?php

namespace App\Http\Controllers\Admin;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = [];
        if (Auth::check()) {
            $departments = (new Department())->allDepartments();
        }

        return view('admin.admin_manage_department', ['departments' => $departments]);
    }

    public function create()
    {
        return view('admin.partials.admin_add_department');
    }

    public function store(Request $request)
    {
        $request->validate([
            'department_name' => ['required', 'string', 'max:500'],
            'department_logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $imageName = null;

        if ($request->hasFile('department_logo')) {
            $image     = $request->file('department_logo');
            $imageName = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
        }

        Department::create([
            'department_name' => $request->department_name,
            'department_logo' => $imageName,
        ]);

        return redirect()->route('admin.departments')
            ->with('success', 'Department created successfully.');
    }

    public function edit(Department $department)
    {
        return view('admin.partials.admin_edit_department', ['department' => $department]);
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'department_name' => ['required', 'string', 'max:500'],
        ]);

        if ($request->hasFile('department_logo')) {
            if ($department->department_logo && file_exists(public_path('images/' . $department->department_logo))) {
                unlink(public_path('images/' . $department->department_logo));
            }

            $image     = $request->file('department_logo');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $department->department_logo = $imageName;
        }

        $department->department_name = $request->department_name;
        $department->save();

        return redirect()->route('admin.departments')
            ->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        try {
            $department->delete();
            return back()->with('success', 'Department deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting department: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete department. ' . $e->getMessage());
        }
    }
}
