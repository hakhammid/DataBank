<?php

namespace App\Services;

use App\Models\User;
use App\Models\Course;
use App\Models\Module;
use App\Models\Department;
use Illuminate\Http\Request;

class ReportService
{
    public function getSummaryData(Request $request)
    {
        $departments = Department::all();
        $courses = Course::all();
        $faculties = User::where('usertype', 'faculty')->get();

        // Build base query with filters
        $moduleQuery = Module::with(['user', 'department', 'course']);

        if ($request->filled('department_id')) {
            $moduleQuery->where('department_id', $request->department_id);
        }
        if ($request->filled('course_id')) {
            $moduleQuery->where('course_id', $request->course_id);
        }
        if ($request->filled('semester')) {
            $moduleQuery->where('semester', $request->semester);
        }
        if ($request->filled('faculty_id')) {
            $moduleQuery->where('user_id', $request->faculty_id);
        }

        $modules = $moduleQuery->get();

        $totalModules = $modules->count();
        $totalFaculty = $modules->pluck('user_id')->unique()->count();

        // Faculty Upload Summary
        $facultyUploadSummary = $modules->where('user.usertype', 'faculty')
            ->groupBy('user_id')
            ->map(function ($facultyModules) {
                $faculty = $facultyModules->first()->user;
                $courseBreakdown = $facultyModules->groupBy('course_code')->map(function ($codeModules) {
                    return [
                        'course_code' => $codeModules->first()->course_code,
                        'course_name' => $codeModules->first()->course?->course_name ?? 'N/A',
                        'count' => $codeModules->count(),
                    ];
                })->values();

                return [
                    'faculty_id' => $faculty->id ?? null,
                    'faculty_name' => $faculty->name ?? 'Unknown',
                    'department' => $faculty->department?->department_name ?? 'N/A',
                    'total_modules' => $facultyModules->count(),
                    'course_breakdown' => $courseBreakdown,
                ];
            })->values();

        // Department breakdown
        $departmentBreakdown = $modules->groupBy('department_id')
            ->map(function ($deptModules) {
                $dept = $deptModules->first()->department;
                return [
                    'department_name' => $dept?->department_name ?? 'N/A',
                    'total_modules' => $deptModules->count(),
                    'degree_programs' => $deptModules->pluck('course')->unique('id')->filter()->values(),
                ];
            })->values();

        // Student download data
        $downloadQuery = \App\Models\ModuleDownload::with(['user.department', 'user.course', 'module.user', 'module.department', 'module.course']);

        if ($request->filled('department_id')) {
            $downloadQuery->whereHas('module', fn($q) => $q->where('department_id', $request->department_id));
        }
        if ($request->filled('course_id')) {
            $downloadQuery->whereHas('module', fn($q) => $q->where('course_id', $request->course_id));
        }
        if ($request->filled('semester')) {
            $downloadQuery->whereHas('module', fn($q) => $q->where('semester', $request->semester));
        }
        if ($request->filled('faculty_id')) {
            $downloadQuery->whereHas('module', fn($q) => $q->where('user_id', $request->faculty_id));
        }

        $downloads = $downloadQuery->get();
        $totalDownloads = $downloads->count();

        $studentDownloads = $downloads->groupBy(fn($d) => $d->user?->department?->department_name ?? 'Unknown')
            ->map(function ($deptDownloads, $deptName) {
                return $deptDownloads->groupBy(fn($d) => $d->user?->course?->course_name ?? 'Unknown')
                    ->map(function ($courseDownloads, $courseName) {
                        return [
                            'degree_program' => $courseName,
                            'students' => $courseDownloads->groupBy('user_id')->map(function ($studentDownloads) {
                                $student = $studentDownloads->first()->user;
                                return [
                                    'name' => $student?->name ?? 'Unknown',
                                    'id_number' => $student?->id_number ?? 'N/A',
                                    'download_count' => $studentDownloads->count(),
                                    'last_download' => $studentDownloads->max('downloaded_at'),
                                ];
                            })->values(),
                        ];
                    })->values();
            });

        return [
            'departments' => $departments,
            'courses' => $courses,
            'faculties' => $faculties,
            'totalModules' => $totalModules,
            'totalFaculty' => $totalFaculty,
            'totalDownloads' => $totalDownloads,
            'totalStudents' => User::where('usertype', 'student')->count(),
            'totalDepartments' => Department::count(),
            'totalCourses' => Course::count(),
            'facultyUploadSummary' => $facultyUploadSummary,
            'departmentBreakdown' => $departmentBreakdown,
            'studentDownloads' => $studentDownloads,
            'filters' => $request->only(['department_id', 'course_id', 'semester', 'faculty_id']),
        ];
    }

    public function getIndividualData(Request $request, Course $course)
    {
        $semester = $request->input('semester');

        $modulesQuery = Module::where('course_id', $course->id)
            ->with(['user' => function ($query) {
                $query->select('id', 'first_name', 'middle_initial', 'last_name', 'usertype', 'profile_picture');
            }])
            ->with(['department' => function ($query) {
                $query->select('id', 'department_name');
            }])
            ->withCount('moduleDownloads');

        if ($semester) {
            $modulesQuery->where('semester', $semester);
        }

        $modulesPaginated = clone $modulesQuery;
        $modules = $modulesPaginated->orderBy('created_at', 'desc')->paginate(15);
        $allModules = $modulesQuery->orderBy('created_at', 'desc')->get();

        $statsQuery = function () use ($course, $semester) {
            $q = Module::where('course_id', $course->id);
            if ($semester) $q->where('semester', $semester);
            return $q;
        };

        $stats = [
            'total_modules' => $statsQuery()->count(),
            'major_modules' => $statsQuery()->where('isMajor', 1)->count(),
            'minor_modules' => $statsQuery()->where('isMajor', 0)->count(),
            'total_views' => $statsQuery()->sum('number_of_views'),
            'total_downloads' => $statsQuery()
                ->withCount('moduleDownloads')
                ->get()
                ->sum('module_downloads_count'),
            'uploaders' => $statsQuery()
                ->distinct('user_id')
                ->count('user_id'),
        ];

        $uploaders = User::whereIn('id', function ($query) use ($course, $semester) {
            $q = $query->select('user_id')
                ->from('modules')
                ->where('course_id', $course->id);
            if ($semester) $q->where('semester', $semester);
            $q->distinct();
        })
            ->withCount(['modules' => function ($query) use ($course, $semester) {
                $query->where('course_id', $course->id);
                if ($semester) $query->where('semester', $semester);
            }])
            ->get();

        return [
            'course' => $course,
            'modules' => $modules,
            'allModules' => $allModules,
            'stats' => $stats,
            'uploaders' => $uploaders,
            'semester' => $semester,
        ];
    }
}
