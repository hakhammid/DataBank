<!DOCTYPE html>
<html>
<head>
    <title>General Summary Report - {{ config('constants.APP_TITLE') }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm 12mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            line-height: 1.2;
            color: #000;
            background: #fff;
            font-size: 9pt;
        }

        .report-container {
            width: 100%;
            margin: 0 auto;
        }

        /* ── Header ── */
        .report-header {
            text-align: center;
            padding-bottom: 6px;
            border-bottom: 2.5px double #000;
            margin-bottom: 8px;
        }

        .report-header img {
            width: 50px;
            height: auto;
            margin-bottom: 2px;
        }

        .institution-name {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .institution-sub {
            font-size: 8pt;
            margin-bottom: 1px;
        }

        .report-title {
            font-size: 10.5pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 4px;
            letter-spacing: 0.5px;
        }

        .report-meta {
            font-size: 7.5pt;
            color: #333;
            margin-top: 2px;
        }

        /* ── Filter Info ── */
        .filter-info {
            font-size: 7.5pt;
            color: #333;
            margin-bottom: 6px;
            padding: 3px 6px;
            border: 1px solid #999;
            background: #fafafa;
        }

        .filter-info strong {
            color: #000;
        }

        /* ── Summary Stats ── */
        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .stats-table td {
            border: 1px solid #000;
            text-align: center;
            padding: 4px 3px;
        }

        .stats-table .stats-label {
            font-size: 6.5pt;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 0.3px;
        }

        .stats-table .stats-value {
            font-size: 12pt;
            font-weight: bold;
        }

        /* ── Section Heading ── */
        .section-heading {
            font-size: 8.5pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 8px 0 4px 0;
            padding-bottom: 2px;
            border-bottom: 1px solid #000;
        }

        /* ── Data Tables ── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
            font-size: 7.5pt;
        }

        .data-table thead th {
            background: #e0e0e0;
            color: #000;
            font-weight: bold;
            padding: 3px 4px;
            text-align: left;
            font-size: 7pt;
            text-transform: uppercase;
            border: 1px solid #000;
        }

        .data-table tbody td {
            padding: 2.5px 4px;
            border: 1px solid #000;
            vertical-align: top;
            line-height: 1.25;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f5f5f5;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }

        .data-table .total-row td {
            font-weight: bold;
            background: #e0e0e0;
            border-top: 1.5px solid #000;
        }

        /* ── Sub-section labels ── */
        .sub-label {
            font-size: 8pt;
            font-weight: bold;
            margin: 6px 0 3px 0;
            padding: 2px 5px;
            background: #eee;
            border: 1px solid #aaa;
        }

        .sub-label-indent {
            font-size: 7.5pt;
            font-weight: bold;
            margin: 3px 0 2px 8px;
            font-style: italic;
        }

        /* ── Footer ── */
        .report-footer {
            margin-top: 10px;
            padding-top: 4px;
            border-top: 1px solid #000;
            font-size: 7pt;
            color: #333;
            display: flex;
            justify-content: space-between;
        }

        @media print {
            .no-print { display: none !important; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .data-table thead th { background: #e0e0e0 !important; }
            .data-table tbody tr:nth-child(even) { background-color: #f5f5f5 !important; }
            .data-table .total-row td { background: #e0e0e0 !important; }
            .sub-label { background: #eee !important; }
        }
    </style>
</head>
<body>
    <div class="report-container">

        {{-- ── Report Header ── --}}
        <div class="report-header">
            <img src="{{ asset('logo/MSU-LOGO.jpg') }}" alt="MSU Logo">
            <div class="institution-sub">Republic of the Philippines</div>
            <div class="institution-name">Mindanao State University</div>
            <div class="institution-sub">Marawi City</div>
            <div class="report-title">General Summary Report</div>
            <div class="report-meta">
                Date Generated: {{ now()->format('F d, Y — h:i A') }}&nbsp;&nbsp;|&nbsp;&nbsp;Prepared by: {{ Auth::user()->name }}
            </div>
        </div>

        {{-- ── Applied Filters ── --}}
        @php
            $hasFilters = !empty($filters) && array_filter($filters);
        @endphp
        @if($hasFilters)
        <div class="filter-info">
            <strong>Filters:</strong>
            @if(!empty($filters['department_id']))
                Dept: {{ $departments->firstWhere('id', $filters['department_id'])->department_name ?? '—' }};
            @endif
            @if(!empty($filters['course_id']))
                Program: {{ $courses->firstWhere('id', $filters['course_id'])->course_name ?? '—' }};
            @endif
            @if(!empty($filters['semester']))
                Sem: {{ $filters['semester'] }};
            @endif
            @if(!empty($filters['faculty_id']))
                Faculty: {{ $faculties->firstWhere('id', $filters['faculty_id'])->name ?? '—' }};
            @endif
        </div>
        @endif

        {{-- ── I. Summary of Figures ── --}}
        <div class="section-heading">I. Summary of Figures</div>
        <table class="stats-table">
            <tr>
                <td>
                    <div class="stats-value">{{ number_format($totalModules) }}</div>
                    <div class="stats-label">Total Modules</div>
                </td>
                <td>
                    <div class="stats-value">{{ number_format($totalFaculty) }}</div>
                    <div class="stats-label">Faculty Contributors</div>
                </td>
                <td>
                    <div class="stats-value">{{ number_format($totalDownloads) }}</div>
                    <div class="stats-label">Total Downloads</div>
                </td>
                <td>
                    <div class="stats-value">{{ number_format($totalStudents) }}</div>
                    <div class="stats-label">Total Students</div>
                </td>
                <td>
                    <div class="stats-value">{{ number_format($totalDepartments) }}</div>
                    <div class="stats-label">Departments</div>
                </td>
            </tr>
        </table>

        {{-- ── II. Faculty Upload Summary ── --}}
        <div class="section-heading">II. Faculty Upload Summary</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 4%;">#</th>
                    <th style="width: 22%;">Faculty Name</th>
                    <th style="width: 20%;">Department</th>
                    <th style="width: 40%;">Course Codes (Count)</th>
                    <th style="width: 14%; text-align: right;">Total</th>
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
                        @foreach($faculty['course_breakdown'] as $course)
                            {{ $course['course_code'] }}({{ $course['count'] }}){{ !$loop->last ? ', ' : '' }}
                        @endforeach
                    </td>
                    <td class="text-right font-bold">{{ $faculty['total_modules'] }}</td>
                </tr>
                @php $facultyGrandTotal += $faculty['total_modules']; @endphp
                @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 6px; font-style: italic; color: #666;">No data available.</td>
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

        {{-- ── III. Department Breakdown ── --}}
        <div class="section-heading">III. Department Breakdown</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 4%;">#</th>
                    <th style="width: 38%;">Department Name</th>
                    <th style="width: 14%; text-align: right;">Modules</th>
                    <th style="width: 44%;">Degree Programs</th>
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
                        @foreach($dept['degree_programs'] as $program)
                            @if($program)
                                {{ $program->course_name }}{{ !$loop->last ? ', ' : '' }}
                            @endif
                        @endforeach
                    </td>
                </tr>
                @php $deptGrandTotal += $dept['total_modules']; @endphp
                @empty
                <tr>
                    <td colspan="4" class="text-center" style="padding: 6px; font-style: italic; color: #666;">No data available.</td>
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

        {{-- ── IV. Student Download Report ── --}}
        <div class="section-heading">IV. Student Download Report</div>

        @forelse($studentDownloads as $deptName => $programs)
            <div class="sub-label">{{ $deptName }}</div>

            @foreach($programs as $program)
                <div class="sub-label-indent">{{ $program['degree_program'] }}</div>

                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 4%;">#</th>
                            <th style="width: 30%;">Student Name</th>
                            <th style="width: 16%; text-align: center;">ID Number</th>
                            <th style="width: 12%; text-align: right;">Downloads</th>
                            <th style="width: 38%;">Last Activity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($program['students'] as $sIndex => $student)
                        <tr>
                            <td class="text-center">{{ $sIndex + 1 }}</td>
                            <td>{{ $student['name'] }}</td>
                            <td class="text-center" style="font-family: 'Courier New', monospace; font-size: 7pt;">{{ $student['id_number'] }}</td>
                            <td class="text-right font-bold">{{ $student['download_count'] }}</td>
                            <td>{{ $student['last_download'] ? \Carbon\Carbon::parse($student['last_download'])->format('M d, Y h:i A') : 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        @empty
            <p style="text-align: center; padding: 8px; font-style: italic; color: #666; font-size: 8pt;">No student download data available.</p>
        @endforelse

        {{-- ── Footer ── --}}
        <div class="report-footer">
            <span>&copy; {{ date('Y') }} Mindanao State University — Databanking Module System</span>
            <span>Report ID: SUM-{{ now()->format('Ymd-His') }}</span>
            <span>Generated by: {{ Auth::user()->name }}</span>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
