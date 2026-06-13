<!DOCTYPE html>
<html>
<head>
    <title>Repository Activity Summary Report - {{ config('constants.APP_TITLE') }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 6mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #fff;
            color: #111;
            font-family: Arial, sans-serif;
            font-size: 7.8pt;
            line-height: 1.28;
        }

        .report-container {
            width: 100%;
        }

        .report-header {
            display: grid;
            grid-template-columns: 48px 1fr 230px;
            gap: 8px;
            align-items: center;
            margin-bottom: 6px;
            padding-bottom: 6px;
            border-bottom: 1.5px solid #111;
        }

        .report-header img {
            width: 42px;
            height: auto;
        }

        .institution {
            font-family: "Times New Roman", Times, serif;
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .report-title {
            font-size: 10.5pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .report-meta {
            color: #444;
            font-size: 7pt;
            text-align: right;
        }

        .scope-box {
            margin-bottom: 6px;
            padding: 5px 7px;
            border: 1px solid #999;
            background: #fafafa;
            font-size: 7.3pt;
        }

        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 4px;
            margin-bottom: 6px;
        }

        .kpi {
            min-height: 36px;
            padding: 5px 4px;
            border: 1px solid #bbb;
            text-align: center;
        }

        .kpi-value {
            display: block;
            font-size: 14pt;
            font-weight: bold;
        }

        .kpi-label {
            display: block;
            margin-top: 1px;
            color: #555;
            font-size: 6.7pt;
            text-transform: uppercase;
        }

        .main-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px;
        }

        .section {
            margin-bottom: 5px;
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .section-heading {
            margin: 0 0 3px;
            padding: 2px 5px;
            border: 1px solid #cfcfcf;
            background: #eeeeee;
            color: #111;
            font-size: 7.4pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #d0d0d0;
            padding: 3.5px 4.5px;
            vertical-align: top;
            overflow-wrap: normal;
            word-break: normal;
            hyphens: none;
        }

        th {
            background: #f6f6f6;
            font-size: 6.8pt;
            font-weight: bold;
            text-align: left;
            text-transform: uppercase;
        }

        td {
            font-size: 7.1pt;
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .font-bold {
            font-weight: bold;
        }

        .muted {
            color: #666;
        }

        .no-wrap {
            white-space: nowrap;
            overflow-wrap: normal;
            word-break: normal;
        }

        .fit-text {
            display: block;
            white-space: normal;
            overflow: visible;
            text-overflow: clip;
            overflow-wrap: normal;
            word-break: normal;
            hyphens: none;
            line-height: 1.18;
        }

        .tiny {
            font-size: 6.6pt;
        }

        .report-footer {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            margin-top: 5px;
            padding-top: 4px;
            border-top: 1px solid #bbb;
            color: #555;
            font-size: 6.5pt;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    @php
        $hasFilters = !empty(array_filter($filters ?? []));
        $selectedDepartment = !empty($filters['department_id'] ?? null)
            ? $departments->firstWhere('id', $filters['department_id'])
            : null;
        $selectedCourse = !empty($filters['course_id'] ?? null)
            ? $courses->firstWhere('id', $filters['course_id'])
            : null;
        $selectedFaculty = !empty($filters['faculty_id'] ?? null)
            ? $faculties->firstWhere('id', $filters['faculty_id'])
            : null;

        $facultyRows = collect($facultyUploadSummary ?? [])->sortByDesc('total_modules')->take(5)->values();
        $departmentRows = collect($departmentBreakdown ?? [])->sortByDesc('total_modules')->take(5)->values();
        $activityRows = collect($activityFeed ?? [])->take(5)->values();
        $moduleRows = collect($topModules ?? [])->take(6)->values();
        $studentRows = collect($studentDownloads ?? [])->flatMap(function ($programs, $deptName) {
            return collect($programs)->flatMap(function ($program) use ($deptName) {
                return collect($program['students'] ?? [])->map(function ($student) use ($deptName, $program) {
                    return [
                        'department' => $deptName,
                        'program' => $program['degree_program'] ?? 'N/A',
                        'name' => $student['name'] ?? 'Unknown',
                        'id_number' => $student['id_number'] ?? 'N/A',
                        'download_count' => $student['download_count'] ?? 0,
                        'last_download' => $student['last_download'] ?? null,
                    ];
                });
            });
        })->sortByDesc('download_count')->take(5)->values();
    @endphp

    <div class="report-container">
        <div class="report-header">
            <img src="{{ asset('logo/MSU-LOGO.jpg') }}" alt="MSU Logo">
            <div>
                <div class="institution">Mindanao State University - Maguindanao</div>
                <div class="report-title">Repository Activity Summary Report</div>
                <div class="tiny">MODUBANK: A Digital Module Repository and Management System</div>
            </div>
            <div class="report-meta">
                Generated: {{ now()->format('M d, Y h:i A') }}<br>
                Prepared by: {{ auth()->user()?->name ?? 'Administrator' }}<br>
                Report ID: SUM-{{ now()->format('Ymd-His') }}
            </div>
        </div>

        <div class="scope-box">
            <strong>Coverage:</strong> This report summarizes module uploads, downloads, views, users, departments, programs, and recent repository activity.
            <strong>Filters:</strong>
            @if($hasFilters)
                @if($selectedDepartment) Department: {{ $selectedDepartment->department_name }}; @endif
                @if($selectedCourse) Program: {{ $selectedCourse->course_name }}; @endif
                @if(!empty($filters['semester'] ?? null)) Semester: {{ $filters['semester'] }}; @endif
                @if($selectedFaculty) Faculty: {{ $selectedFaculty->name }}; @endif
            @else
                Complete repository scope.
            @endif
        </div>

        <div class="kpi-grid">
            <div class="kpi"><span class="kpi-value">{{ number_format($totalModules) }}</span><span class="kpi-label">Uploads</span></div>
            <div class="kpi"><span class="kpi-value">{{ number_format($totalDownloads) }}</span><span class="kpi-label">Downloads</span></div>
            <div class="kpi"><span class="kpi-value">{{ number_format($totalViews ?? 0) }}</span><span class="kpi-label">Views</span></div>
            <div class="kpi"><span class="kpi-value">{{ number_format($totalUsers ?? 0) }}</span><span class="kpi-label">Users</span></div>
            <div class="kpi"><span class="kpi-value">{{ number_format($totalDepartments) }}</span><span class="kpi-label">Departments</span></div>
            <div class="kpi"><span class="kpi-value">{{ number_format($totalCourses) }}</span><span class="kpi-label">Programs</span></div>
        </div>

        <div class="main-grid">
            <div>
                <div class="section">
                    <div class="section-heading">Student Download Activity</div>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 24%;">Student</th>
                                <th style="width: 13%;" class="no-wrap">ID Number</th>
                                <th style="width: 42%;">Program</th>
                                <th style="width: 12%;" class="text-right no-wrap">Downloads</th>
                                <th style="width: 9%;" class="no-wrap">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($studentRows as $student)
                                <tr>
                                    <td class="font-bold"><span class="fit-text">{{ $student['name'] }}</span></td>
                                    <td class="no-wrap">{{ $student['id_number'] }}</td>
                                    <td><span class="fit-text">{{ $student['program'] }}</span></td>
                                    <td class="text-right">{{ number_format($student['download_count']) }}</td>
                                    <td class="no-wrap">{{ $student['last_download'] ? \Carbon\Carbon::parse($student['last_download'])->format('M d') : 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center">No student download activity.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="section">
                    <div class="section-heading">Top Faculty Upload Summary</div>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 24%;">Faculty</th>
                                <th style="width: 43%;">Department</th>
                                <th style="width: 10%;" class="text-right no-wrap">Uploads</th>
                                <th style="width: 8%;" class="text-right no-wrap">Views</th>
                                <th style="width: 15%;" class="text-right no-wrap">Downloads</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($facultyRows as $faculty)
                                <tr>
                                    <td class="font-bold"><span class="fit-text">{{ $faculty['faculty_name'] }}</span></td>
                                    <td><span class="fit-text">{{ $faculty['department'] }}</span></td>
                                    <td class="text-right">{{ number_format($faculty['total_modules']) }}</td>
                                    <td class="text-right">{{ number_format($faculty['total_views'] ?? 0) }}</td>
                                    <td class="text-right">{{ number_format($faculty['total_downloads'] ?? 0) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center">No faculty uploads.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="section">
                    <div class="section-heading">Department and Program Coverage</div>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 33%;">Department</th>
                                <th style="width: 12%;" class="text-right no-wrap">Modules</th>
                                <th style="width: 10%;" class="text-right no-wrap">Views</th>
                                <th style="width: 14%;" class="text-right no-wrap">Downloads</th>
                                <th style="width: 31%;">Programs</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($departmentRows as $dept)
                                <tr>
                                    <td class="font-bold"><span class="fit-text">{{ $dept['department_name'] }}</span></td>
                                    <td class="text-right">{{ number_format($dept['total_modules']) }}</td>
                                    <td class="text-right">{{ number_format($dept['total_views'] ?? 0) }}</td>
                                    <td class="text-right">{{ number_format($dept['total_downloads'] ?? 0) }}</td>
                                    <td><span class="fit-text">{{ collect($dept['degree_programs'])->filter()->pluck('course_name')->take(2)->implode(', ') ?: 'N/A' }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center">No department data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                <div class="section">
                    <div class="section-heading">Recent Upload and Download Activity</div>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 15%;">Action</th>
                                <th style="width: 22%;">Actor</th>
                                <th style="width: 37%;">Module</th>
                                <th style="width: 11%;" class="no-wrap">Code</th>
                                <th style="width: 15%;" class="no-wrap">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activityRows as $activity)
                                <tr>
                                    <td class="font-bold"><span class="fit-text">{{ str_replace('File ', '', $activity['label']) }}</span></td>
                                    <td><span class="fit-text">{{ $activity['actor'] }} <span class="muted">({{ ucfirst($activity['role']) }})</span></span></td>
                                    <td><span class="fit-text">{{ $activity['module_title'] }}</span></td>
                                    <td class="no-wrap">{{ $activity['course_code'] }}</td>
                                    <td class="no-wrap">{{ \Carbon\Carbon::parse($activity['occurred_at'])->format('M d h:i A') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center">No activity.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="section">
                    <div class="section-heading">Top Module Engagement</div>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 6%;" class="text-center">#</th>
                                <th style="width: 40%;">Module</th>
                                <th style="width: 12%;" class="no-wrap">Code</th>
                                <th style="width: 20%;">Uploader</th>
                                <th style="width: 8%;" class="text-right no-wrap">Views</th>
                                <th style="width: 14%;" class="text-right no-wrap">Downloads</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($moduleRows as $index => $module)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td class="font-bold"><span class="fit-text">{{ $module->title }}</span></td>
                                    <td class="no-wrap">{{ $module->course_code }}</td>
                                    <td><span class="fit-text">{{ $module->user->name ?? 'Unknown' }}</span></td>
                                    <td class="text-right">{{ number_format($module->number_of_views ?? 0) }}</td>
                                    <td class="text-right">{{ number_format($module->module_downloads_count ?? 0) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center">No module data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <div class="report-footer">
            <span>&copy; {{ date('Y') }} Mindanao State University - MODUBANK</span>
            <span>One-page summary. Detailed records remain available in the admin dashboard.</span>
            <span>Generated by: {{ auth()->user()?->name ?? 'Administrator' }}</span>
        </div>
    </div>
</body>
</html>
