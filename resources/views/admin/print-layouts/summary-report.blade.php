<!DOCTYPE html>
<html>
<head>
    <title>General Summary Report - {{ config('constants.APP_TITLE') }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm 15mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.4;
            color: #333;
            background: #fff;
            font-size: 8.5pt;
        }

        .report-container {
            width: 100%;
            margin: 0 auto;
        }

        /* ── Header ── */
        .report-header {
            text-align: center;
            padding-bottom: 15px;
            border-bottom: 3px solid #111;
            margin-bottom: 15px;
            font-family: 'Times New Roman', Times, serif;
        }

        .report-header img {
            width: 60px;
            height: auto;
            margin-bottom: 5px;
        }

        .institution-name {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #111;
        }

        .institution-sub {
            font-size: 9pt;
            margin-bottom: 2px;
            color: #444;
        }

        .report-title {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 8px;
            letter-spacing: 1px;
            color: #000;
            font-family: 'Arial', sans-serif;
        }

        .report-meta {
            font-size: 8pt;
            color: #666;
            margin-top: 5px;
            font-family: 'Arial', sans-serif;
        }

        /* ── Filter Info ── */
        .filter-info {
            font-size: 8pt;
            color: #444;
            margin-bottom: 15px;
            padding: 8px 10px;
            border-left: 3px solid #111;
            background: #f8f9fa;
        }

        .filter-info strong {
            color: #111;
            margin-right: 5px;
        }

        /* ── Summary Stats ── */
        .stats-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 5px;
            margin-bottom: 20px;
            margin-left: -5px;
            margin-right: -5px;
        }

        .stats-table td {
            border: 1px solid #ddd;
            background: #fcfcfc;
            text-align: center;
            padding: 12px 5px;
            border-radius: 4px;
        }

        .stats-table .stats-label {
            font-size: 7.5pt;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 0.5px;
            color: #666;
            margin-top: 5px;
        }

        .stats-table .stats-value {
            font-size: 16pt;
            font-weight: bold;
            color: #222;
        }

        /* ── Section Heading ── */
        .section-heading {
            font-size: 9.5pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 20px 0 10px 0;
            padding-bottom: 4px;
            border-bottom: 2px solid #333;
            color: #111;
        }

        /* ── Data Tables ── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 8pt;
        }

        .data-table thead th {
            background: #f0f0f0;
            color: #222;
            font-weight: bold;
            padding: 8px 6px;
            text-align: left;
            font-size: 7.5pt;
            text-transform: uppercase;
            border: 1px solid #ccc;
            border-bottom: 2px solid #888;
        }

        .data-table tbody td {
            padding: 6px;
            border: 1px solid #e0e0e0;
            vertical-align: top;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #fafafa;
        }

        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        .font-bold { font-weight: bold; }

        .data-table .total-row td {
            font-weight: bold;
            background: #f0f0f0;
            border-top: 2px solid #888;
            color: #111;
        }

        /* ── Sub-section labels ── */
        .sub-label {
            font-size: 9pt;
            font-weight: bold;
            margin: 15px 0 8px 0;
            padding: 5px 8px;
            background: #e2e8f0;
            border-left: 4px solid #475569;
            color: #1e293b;
        }

        .sub-label-indent {
            font-size: 8.5pt;
            font-weight: bold;
            margin: 10px 0 6px 12px;
            color: #334155;
        }

        .sub-label-indent::before {
            content: "»";
            margin-right: 6px;
            color: #94a3b8;
        }

        /* ── Footer ── */
        .report-footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 7.5pt;
            color: #666;
            display: flex;
            justify-content: space-between;
        }

        /* ── Badges and Lists ── */
        .badge {
            display: inline-block;
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 3px 6px;
            border-radius: 4px;
            margin: 2px 2px 2px 0;
            font-size: 7.5pt;
            color: #334155;
        }
        .badge-count {
            color: #64748b;
            font-weight: 600;
            margin-left: 3px;
        }
        .program-list {
            margin: 0;
            padding-left: 16px;
            list-style-type: square;
        }
        .program-list li {
            margin-bottom: 2px;
            color: #444;
        }

        @media print {
            .no-print { display: none !important; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .data-table thead th { background: #f0f0f0 !important; }
            .data-table tbody tr:nth-child(even) { background-color: #fafafa !important; }
            .data-table .total-row td { background: #f0f0f0 !important; }
            .sub-label { background: #e2e8f0 !important; }
            .stats-table td { background: #fcfcfc !important; border: 1px solid #ccc !important; }
            .badge { background-color: #f1f5f9 !important; border-color: #cbd5e1 !important; }
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
                            <span class="badge">{{ $course['course_code'] }} <span class="badge-count">({{ $course['count'] }})</span></span>
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
