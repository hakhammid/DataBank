<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Course;
use App\Models\Module;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Services\ReportService;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function summary(Request $request)
    {
        $data = $this->reportService->getSummaryData($request);
        return view('admin.reports.summary', $data);
    }

    public function printSummary(Request $request)
    {
        $data = $this->reportService->getSummaryData($request);
        return view('admin.print-layouts.summary-report', $data);
    }

    public function individual(Request $request, Course $course)
    {
        $data = $this->reportService->getIndividualData($request, $course);
        return view('admin.reports.individual', $data);
    }

    public function printIndividual(Request $request, Course $course)
    {
        $data = $this->reportService->getIndividualData($request, $course);
        // Map modules to allModules since print layout expects collections not paginators
        $data['modules'] = $data['allModules']; 
        return view('admin.print-layouts.individual-report', $data);
    }

    public function printAllStudents()
    {
        $students = User::where('usertype', 'student')->get();
        return view('admin.print-layouts.students',  compact('students'));
    }

    public function printAllModules()
    {
        $modules = Module::with(['user' => function ($query) {
            $query->select('id', 'first_name', 'middle_initial', 'last_name', 'profile_picture');
        }])
            ->latest()
            ->get();

        return view('admin.print-layouts.modules',  compact('modules'));
    }

    public function printAllFaculties()
    {
        $faculties = User::where('usertype', 'faculty')->get();
        return view('admin.print-layouts.faculties',  compact('faculties'));
    }

    public function printAllDepartments()
    {
        $departments = Department::all();
        return view('admin.print-layouts.departments',  compact('departments'));
    }

    public function printAllCourses()
    {
        $courses = Course::all();
        return view('admin.print-layouts.degree_programs',  compact('courses'));
    }

    public function printDepartmentsModule()
    {
        $moduleCount  = Module::count();
        $departments = Department::withCount('modules')->get();

        return view('admin.print-layouts.dashboard', [
            'moduleCount' => $moduleCount,
            'departments' => $departments,
        ]);
    }
}
