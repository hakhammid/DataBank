<?php

namespace App\Services;

use App\Models\User;
use App\Models\Course;
use App\Models\Module;
use App\Models\Department;
use App\Models\ModuleDownload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReportService
{
    public function getSummaryData(Request $request)
    {
        $departments = Department::all();
        $courses = Course::all();
        $faculties = User::where('usertype', 'faculty')->get();

        // Build base query with filters
        $moduleQuery = Module::with(['user', 'department', 'courses'])
            ->withCount('moduleDownloads');

        if ($request->filled('department_id')) {
            $moduleQuery->where('department_id', $request->department_id);
        }
        if ($request->filled('course_id')) {
            $moduleQuery->whereHas('courses', function($q) use ($request) {
                $q->where('courses.id', $request->course_id);
            });
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
        $totalViews = $modules->sum('number_of_views');

        // Faculty Upload Summary
        $facultyUploadSummary = $modules->where('user.usertype', 'faculty')
            ->groupBy('user_id')
            ->map(function ($facultyModules) {
                $faculty = $facultyModules->first()->user;
                $courseBreakdown = $facultyModules->groupBy('course_code')->map(function ($codeModules) {
                    return [
                        'course_code' => $codeModules->first()->course_code,
                        'course_name' => $codeModules->first()->courses->first()?->course_name ?? 'N/A',
                        'count' => $codeModules->count(),
                        'views' => $codeModules->sum('number_of_views'),
                        'downloads' => $codeModules->sum('module_downloads_count'),
                    ];
                })->values();

                return [
                    'faculty_id' => $faculty->id ?? null,
                    'faculty_name' => $faculty->name ?? 'Unknown',
                    'department' => $faculty->department?->department_name ?? 'N/A',
                    'total_modules' => $facultyModules->count(),
                    'total_views' => $facultyModules->sum('number_of_views'),
                    'total_downloads' => $facultyModules->sum('module_downloads_count'),
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
                    'total_views' => $deptModules->sum('number_of_views'),
                    'total_downloads' => $deptModules->sum('module_downloads_count'),
                    'degree_programs' => $deptModules->flatMap->courses->unique('id')->values(),
                ];
            })->values();

        // Student download data
        $downloadQuery = ModuleDownload::with(['user.department', 'user.course', 'module.user', 'module.department', 'module.courses']);

        if ($request->filled('department_id')) {
            $downloadQuery->whereHas('module', fn($q) => $q->where('department_id', $request->department_id));
        }
        if ($request->filled('course_id')) {
            $downloadQuery->whereHas('module.courses', fn($q) => $q->where('courses.id', $request->course_id));
        }
        if ($request->filled('semester')) {
            $downloadQuery->whereHas('module', fn($q) => $q->where('semester', $request->semester));
        }
        if ($request->filled('faculty_id')) {
            $downloadQuery->whereHas('module', fn($q) => $q->where('user_id', $request->faculty_id));
        }

        $downloads = $downloadQuery->get();
        $totalDownloads = $downloads->count();

        $studentDownloads = $this->buildStudentDownloads($downloads);
        $downloadActivityByRole = $this->buildDownloadActivityByRole($downloads);
        $activityFeed = $this->buildActivityFeed($modules, $downloads, 5);
        $topModules = $modules->sortByDesc(function ($module) {
            return ((int) $module->number_of_views) + ((int) $module->module_downloads_count);
        })->take(10)->values();
        $recentUploads = $modules->sortByDesc('created_at')->take(8)->values();
        $recentDownloads = $downloads->sortByDesc('downloaded_at')->take(8)->values();
        $roleBreakdown = User::selectRaw('usertype, COUNT(*) as total')
            ->whereIn('usertype', ['faculty', 'student'])
            ->groupBy('usertype')
            ->orderBy('usertype')
            ->get()
            ->map(function ($role) {
                return [
                    'role' => $role->usertype ?: 'unknown',
                    'total' => (int) $role->total,
                ];
            });
        $sessionActivity = $this->getSessionActivity();

        return [
            'departments' => $departments,
            'courses' => $courses,
            'faculties' => $faculties,
            'totalModules' => $totalModules,
            'totalFaculty' => $totalFaculty,
            'totalDownloads' => $totalDownloads,
            'totalViews' => $totalViews,
            'totalStudents' => User::where('usertype', 'student')->count(),
            'totalDepartments' => Department::count(),
            'totalCourses' => Course::count(),
            'totalUsers' => User::count(),
            'facultyUploadSummary' => $facultyUploadSummary,
            'departmentBreakdown' => $departmentBreakdown,
            'studentDownloads' => $studentDownloads,
            'downloadActivityByRole' => $downloadActivityByRole,
            'activityFeed' => $activityFeed,
            'topModules' => $topModules,
            'recentUploads' => $recentUploads,
            'recentDownloads' => $recentDownloads,
            'roleBreakdown' => $roleBreakdown,
            'activeUsers' => $sessionActivity['activeUsers'],
            'activeUsersByRole' => $sessionActivity['activeUsersByRole'],
            'recentUserActivity' => $sessionActivity['recentUserActivity'],
            'filters' => $request->only(['department_id', 'course_id', 'semester', 'faculty_id']),
        ];
    }

    public function getIndividualData(Request $request, Course $course)
    {
        $semester = $request->input('semester');

        $modulesQuery = Module::whereHas('courses', function ($q) use ($course) {
                $q->where('courses.id', $course->id);
            })
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
        $modules = $modulesPaginated->orderBy('created_at', 'desc')->paginate(10);
        $allModules = $modulesQuery->orderBy('created_at', 'desc')->get();

        $statsQuery = function () use ($course, $semester) {
            $q = Module::whereHas('courses', function ($q2) use ($course) {
                $q2->where('courses.id', $course->id);
            });
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
            $q = $query->select('modules.user_id')
                ->from('modules')
                ->join('module_courses', 'modules.id', '=', 'module_courses.module_id')
                ->where('module_courses.course_id', $course->id);
            if ($semester) $q->where('modules.semester', $semester);
            $q->distinct();
        })
            ->withCount(['modules' => function ($query) use ($course, $semester) {
                $query->whereHas('courses', function ($q2) use ($course) {
                    $q2->where('courses.id', $course->id);
                });
                if ($semester) $query->where('semester', $semester);
            }])
            ->get();

        $downloadQuery = ModuleDownload::with(['user.department', 'user.course', 'module.user', 'module.department', 'module.courses'])
            ->whereHas('module.courses', function ($q) use ($course) {
                $q->where('courses.id', $course->id);
            });

        if ($semester) {
            $downloadQuery->whereHas('module', function ($q) use ($semester) {
                $q->where('semester', $semester);
            });
        }

        $downloads = $downloadQuery->get();
        $downloadActivityByRole = $this->buildDownloadActivityByRole($downloads);
        $studentDownloads = $this->buildStudentDownloads($downloads);
        $activityFeed = $this->buildActivityFeed($allModules, $downloads, 12);
        $topModules = $allModules->sortByDesc(function ($module) {
            return ((int) $module->number_of_views) + ((int) $module->module_downloads_count);
        })->take(10)->values();
        $courseCodeBreakdown = $allModules->groupBy('course_code')
            ->sortKeys()
            ->map(function ($courseCodeModules, $courseCode) {
                return [
                    'course_code' => $courseCode,
                    'modules' => $courseCodeModules->count(),
                    'major_modules' => $courseCodeModules->where('isMajor', 1)->count(),
                    'minor_modules' => $courseCodeModules->where('isMajor', 0)->count(),
                    'views' => $courseCodeModules->sum('number_of_views'),
                    'downloads' => $courseCodeModules->sum('module_downloads_count'),
                    'latest_upload' => $courseCodeModules->max('created_at'),
                ];
            })->values();
        $downloaders = $downloads->groupBy('user_id')
            ->map(function ($userDownloads) {
                $user = $userDownloads->first()->user;

                return [
                    'name' => $user?->name ?? 'Unknown',
                    'id_number' => $user?->id_number ?? 'N/A',
                    'role' => $user?->usertype ?? 'unknown',
                    'department' => $user?->department?->department_name ?? 'N/A',
                    'course' => $user?->course?->course_name ?? 'N/A',
                    'download_count' => $userDownloads->count(),
                    'last_download' => $userDownloads->max('downloaded_at'),
                ];
            })
            ->sortByDesc('download_count')
            ->take(10)
            ->values();

        return [
            'course' => $course,
            'modules' => $modules,
            'allModules' => $allModules,
            'stats' => $stats,
            'uploaders' => $uploaders,
            'downloads' => $downloads,
            'downloadActivityByRole' => $downloadActivityByRole,
            'studentDownloads' => $studentDownloads,
            'activityFeed' => $activityFeed,
            'topModules' => $topModules,
            'courseCodeBreakdown' => $courseCodeBreakdown,
            'downloaders' => $downloaders,
            'semester' => $semester,
        ];
    }

    private function buildStudentDownloads($downloads)
    {
        return $downloads->groupBy(fn($d) => $d->user?->department?->department_name ?? 'Unknown')
            ->map(function ($deptDownloads) {
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
                            })->sortByDesc('download_count')->values(),
                        ];
                    })->values();
            });
    }

    private function buildDownloadActivityByRole($downloads)
    {
        return $downloads->groupBy(fn($download) => $download->user?->usertype ?? 'unknown')
            ->map(function ($roleDownloads, $role) {
                return [
                    'role' => $role,
                    'downloads' => $roleDownloads->count(),
                    'unique_users' => $roleDownloads->pluck('user_id')->unique()->count(),
                    'last_activity' => $roleDownloads->max('downloaded_at'),
                ];
            })
            ->sortByDesc('downloads')
            ->values();
    }

    private function buildActivityFeed($uploads, $downloads, int $limit)
    {
        $uploadActivity = $uploads->map(function ($module) {
            return [
                'type' => 'upload',
                'label' => 'File uploaded',
                'actor' => $module->user?->name ?? 'Unknown',
                'role' => $module->user?->usertype ?? 'unknown',
                'module_title' => $module->title,
                'course_code' => $module->course_code,
                'department' => $module->department?->department_name ?? 'N/A',
                'occurred_at' => $module->created_at,
            ];
        });

        $downloadActivity = $downloads->map(function ($download) {
            return [
                'type' => 'download',
                'label' => 'File downloaded',
                'actor' => $download->user?->name ?? 'Unknown',
                'role' => $download->user?->usertype ?? 'unknown',
                'module_title' => $download->module?->title ?? 'Deleted module',
                'course_code' => $download->module?->course_code ?? 'N/A',
                'department' => $download->module?->department?->department_name ?? 'N/A',
                'occurred_at' => $download->downloaded_at,
            ];
        });

        return $uploadActivity
            ->merge($downloadActivity)
            ->filter(fn($activity) => !empty($activity['occurred_at']))
            ->sortByDesc('occurred_at')
            ->take($limit)
            ->values();
    }

    private function getSessionActivity(): array
    {
        $sessions = collect();

        if (Schema::hasTable('sessions')) {
            $sessions = DB::table('sessions')
                ->leftJoin('users', 'sessions.user_id', '=', 'users.id')
                ->whereNotNull('sessions.user_id')
                ->whereIn('users.usertype', ['faculty', 'student'])
                ->select([
                    'sessions.user_id',
                    'sessions.ip_address',
                    'sessions.last_activity',
                    'users.first_name',
                    'users.middle_initial',
                    'users.last_name',
                    'users.id_number',
                    'users.usertype',
                ])
                ->orderByDesc('sessions.last_activity')
                ->get();
        }

        $activeCutoff = now()->subMinutes((int) config('session.lifetime', 120))->timestamp;
        $activeSessions = $sessions->where('last_activity', '>=', $activeCutoff);

        $recentUserActivity = $this->getRecentAccessLogActivity();

        if ($recentUserActivity->isEmpty()) {
            $recentUserActivity = $this->buildRecentSessionActivity($sessions);
        }

        return [
            'activeUsers' => $activeSessions->pluck('user_id')->unique()->count(),
            'activeUsersByRole' => $activeSessions->groupBy(fn($session) => $session->usertype ?? 'unknown')
                ->map(function ($roleSessions, $role) {
                    return [
                        'role' => $role,
                        'total' => $roleSessions->pluck('user_id')->unique()->count(),
                    ];
                })
                ->values(),
            'recentUserActivity' => $recentUserActivity,
        ];
    }

    private function getRecentAccessLogActivity()
    {
        if (! Schema::hasTable('user_access_logs')) {
            return collect();
        }

        return DB::table('user_access_logs')
            ->whereIn('usertype', ['faculty', 'student'])
            ->select([
                'name',
                'id_number',
                'usertype',
                'ip_address',
                'login_at',
                'last_seen_at',
                'logout_at',
            ])
            ->orderByDesc('last_seen_at')
            ->orderByDesc('login_at')
            ->take(5)
            ->get()
            ->map(function ($log) {
                return [
                    'name' => $log->name ?: 'Unknown',
                    'id_number' => $log->id_number ?? 'N/A',
                    'role' => $log->usertype ?? 'unknown',
                    'ip_address' => $log->ip_address ?? 'N/A',
                    'login_at' => $log->login_at ? \Carbon\Carbon::parse($log->login_at) : null,
                    'last_seen_at' => $log->last_seen_at ? \Carbon\Carbon::parse($log->last_seen_at) : null,
                    'logout_at' => $log->logout_at ? \Carbon\Carbon::parse($log->logout_at) : null,
                ];
            })
            ->values();
    }

    private function buildRecentSessionActivity($sessions)
    {
        return $sessions->unique('user_id')
            ->take(5)
            ->map(function ($session) {
                $middle = !empty($session->middle_initial) ? $session->middle_initial . '. ' : '';
                $name = trim(($session->first_name ?? '') . ' ' . $middle . ($session->last_name ?? ''));

                return [
                    'name' => $name ?: 'Unknown',
                    'id_number' => $session->id_number ?? 'N/A',
                    'role' => $session->usertype ?? 'unknown',
                    'ip_address' => $session->ip_address ?? 'N/A',
                    'login_at' => null,
                    'last_seen_at' => $session->last_activity
                        ? \Carbon\Carbon::createFromTimestamp($session->last_activity)
                        : null,
                    'logout_at' => null,
                ];
            })
            ->values();
    }
}
