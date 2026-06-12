<!DOCTYPE html>
<html>
<head>
    <title>General Summary Report - {{ config('constants.APP_TITLE') }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm;
            @bottom-center {
                content: "Page " counter(page) " of " counter(pages);
                font-size: 10pt;
                font-family: Arial, sans-serif;
            }
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 0;
        }

        .report-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
        }

        .report-header img {
            width: 60px;
            height: auto;
        }

        .report-title {
            font-size: 14pt;
            font-weight: bold;
            margin: 10px 0;
            text-transform: uppercase;
        }

        .report-meta {
            font-size: 9pt;
            margin-top: 5px;
        }

        .filter-info {
            font-size: 9pt;
            margin-bottom: 20px;
            padding: 5px;
            border: 1px dashed #000;
        }

        .section-heading {
            font-size: 12pt;
            font-weight: bold;
            margin: 20px 0 10px 0;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
        }

        .stats-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .stats-table td {
            text-align: center;
            padding: 10px;
            border: 1px solid #000;
        }

        .stat-value {
            font-size: 16pt;
            font-weight: bold;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 9pt;
        }

        .data-table th, .data-table td {
            padding: 5px;
            border: 1px solid #000;
            text-align: left;
            vertical-align: top;
        }

        .data-table th {
            font-weight: bold;
            background-color: #f0f0f0;
        }

        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        .font-bold { font-weight: bold; }

        .total-row td {
            font-weight: bold;
            background-color: #f0f0f0;
        }

        .sub-label {
            font-size: 11pt;
            font-weight: bold;
            margin: 15px 0 5px 0;
            text-decoration: underline;
        }

        .sub-label-indent {
            font-size: 10pt;
            font-weight: bold;
            margin: 10px 0 5px 15px;
        }

        .program-list {
            margin: 0;
            padding-left: 20px;
            list-style-type: circle;
        }

        .report-footer {
            margin-top: 30px;
            border-top: 1px solid #000;
            padding-top: 10px;
            font-size: 8pt;
            text-align: center;
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

    <div class="report-header">
        <img src="{{ asset('logo/MSU-LOGO.jpg') }}" alt="MSU Logo">
        <div>Republic of the Philippines</div>
        <div style="font-size: 14pt; font-weight: bold;">Mindanao State University</div>
        <div>Maguindanao</div>
        <div class="report-title">General Summary Report</div>
        <div class="report-meta">
            Date Generated: {{ now()->format('F d, Y - h:i A') }} | Prepared by: {{ Auth::user()->name }}
        </div>
    </div>

    @php
        $hasFilters = !empty($filters) && array_filter($filters);
    @endphp
    @if($hasFilters)
    <div class="filter-info">
        <strong>Active Filters:</strong>
        @if(!empty($filters['department_id']))
            | Department: {{ $departments->firstWhere('id', $filters['department_id'])->department_name ?? '-' }}
        @endif
        @if(!empty($filters['course_id']))
            | Program: {{ $courses->firstWhere('id', $filters['course_id'])->course_name ?? '-' }}
        @endif
        @if(!empty($filters['semester']))
            | Semester: {{ $filters['semester'] }}
        @endif
        @if(!empty($filters['faculty_id']))
            | Faculty: {{ $faculties->firstWhere('id', $filters['faculty_id'])->name ?? '-' }}
        @endif
    </div>
    @endif

    <div class="section-heading">I. Summary of Figures</div>
    <table class="stats-table">
        <tr>
            <td>
                <div class="stat-value">{{ number_format($totalModules) }}</div>
                <div>Total Modules</div>
            </td>
            <td>
                <div class="stat-value">{{ number_format($totalFaculty) }}</div>
                <div>Faculty Contributors</div>
            </td>
            <td>
                <div class="stat-value">{{ number_format($totalDownloads) }}</div>
                <div>Total Downloads</div>
            </td>
            <td>
                <div class="stat-value">{{ number_format($totalStudents) }}</div>
                <div>Total Students</div>
            </td>
            <td>
                <div class="stat-value">{{ number_format($totalDepartments) }}</div>
                <div>Departments</div>
            </td>
        </tr>
    </table>

    <div class="section-heading">II. Faculty Upload Summary</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">#</th>
                <th style="width: 25%;">Faculty Name</th>
                <th style="width: 20%;">Department</th>
                <th style="width: 35%;">Course Codes (Count)</th>
                <th style="width: 15%;" class="text-right">Total Modules</th>
            </tr>
        </thead>
        <tbody>
            @php $facultyGrandTotal = 0; @endphp
            @forelse($facultyUploadSummary as $index => $faculty)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="font-bold">{{ $faculty['faculty_name'] }}</td>
                <td>{{ $faculty['department'] }}</td>
                <td>
                    <ul class="program-list">
                    @foreach($faculty['course_breakdown'] as $course)
                        <li>{{ $course['course_code'] }} ({{ $course['count'] }})</li>
                    @endforeach
                    </ul>
                </td>
                <td class="text-right font-bold">{{ $faculty['total_modules'] }}</td>
            </tr>
            @php $facultyGrandTotal += $faculty['total_modules']; @endphp
            @empty
            <tr>
                <td colspan="5" class="text-center">No data available.</td>
            </tr>
            @endforelse
            @if(count($facultyUploadSummary) > 0)
            <tr class="total-row">
                <td colspan="4" class="text-right">Grand Total</td>
                <td class="text-right">{{ $facultyGrandTotal }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="section-heading">III. Department Breakdown</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">#</th>
                <th style="width: 35%;">Department Name</th>
                <th style="width: 15%;" class="text-right">Modules</th>
                <th style="width: 45%;">Degree Programs</th>
            </tr>
        </thead>
        <tbody>
            @php $deptGrandTotal = 0; @endphp
            @forelse($departmentBreakdown as $index => $dept)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="font-bold">{{ $dept['department_name'] }}</td>
                <td class="text-right font-bold">{{ $dept['total_modules'] }}</td>
                <td>
                    <ul class="program-list">
                    @foreach($dept['degree_programs'] as $program)
                        @if($program)
                            <li>{{ $program->course_name }}</li>
                        @endif
                    @endforeach
                    </ul>
                </td>
            </tr>
            @php $deptGrandTotal += $dept['total_modules']; @endphp
            @empty
            <tr>
                <td colspan="4" class="text-center">No data available.</td>
            </tr>
            @endforelse
            @if(count($departmentBreakdown) > 0)
            <tr class="total-row">
                <td colspan="2" class="text-right">Grand Total</td>
                <td class="text-right">{{ $deptGrandTotal }}</td>
                <td></td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="section-heading">IV. Student Download Report</div>

    @forelse($studentDownloads as $deptName => $programs)
        <div class="sub-label">{{ $deptName }}</div>

        @foreach($programs as $program)
            <div class="sub-label-indent">- {{ $program['degree_program'] }}</div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 5%;" class="text-center">#</th>
                        <th style="width: 35%;">Student Name</th>
                        <th style="width: 20%;" class="text-center">ID Number</th>
                        <th style="width: 15%;" class="text-right">Downloads</th>
                        <th style="width: 25%;">Last Activity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($program['students'] as $sIndex => $student)
                    <tr>
                        <td class="text-center">{{ $sIndex + 1 }}</td>
                        <td>{{ $student['name'] }}</td>
                        <td class="text-center" style="font-family: monospace;">{{ $student['id_number'] }}</td>
                        <td class="text-right">{{ $student['download_count'] }}</td>
                        <td>{{ $student['last_download'] ? \Carbon\Carbon::parse($student['last_download'])->format('M d, Y h:i A') : 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    @empty
        <p style="text-align: center; padding: 20px;">No student download data available.</p>
    @endforelse

    <div class="report-footer">
        &copy; {{ date('Y') }} Mindanao State University - Databanking Module System | Report ID: SUM-{{ now()->format('Ymd-His') }}
    </div>

</body>
</html>
