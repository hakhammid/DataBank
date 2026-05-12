<!DOCTYPE html>
<html>
<head>
    <title>General Summary Report - {{ config('constants.APP_TITLE') }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.5;
            color: #1a202c;
            background: #fff;
            font-size: 13px;
        }

        .report-container {
            width: 100%;
            margin: 0 auto;
        }

        /* Confidential Watermark/Header */
        .confidential-header {
            text-align: right;
            color: #c53030;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 2px;
            margin-bottom: 15px;
            border-bottom: 2px solid #feb2b2;
            padding-bottom: 8px;
        }

        /* Header Section */
        .report-header {
            display: flex;
            align-items: center;
            justify-content: center;
            padding-bottom: 25px;
            border-bottom: 4px solid #1a1a2c;
            margin-bottom: 25px;
            position: relative;
        }

        .logo-container {
            position: absolute;
            left: 0;
            top: 0;
        }

        .logo-container img {
            width: 90px;
            height: auto;
        }

        .header-text {
            text-align: center;
        }

        .institution-name {
            font-size: 28px;
            font-weight: 900;
            color: #1a1a2c;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .report-title {
            font-size: 20px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 6px;
        }

        .report-meta {
            font-size: 12px;
            color: #4a5568;
        }

        /* Summary Stats Table */
        .stats-table {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }

        .stats-table td {
            width: 20%;
            padding: 15px;
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            text-align: center;
        }

        .stats-value {
            font-size: 22px;
            font-weight: 900;
            color: #2b6cb0;
        }

        .stats-label {
            font-size: 10px;
            color: #4a5568;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* Section Titles */
        .section-title {
            font-size: 16px;
            font-weight: 800;
            color: #1a1a2e;
            margin: 30px 0 15px 0;
            padding: 10px 15px;
            border-left: 6px solid #2b6cb0;
            background: #ebf8ff;
            text-transform: uppercase;
        }

        /* Data Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .data-table thead th {
            background: #2d3748;
            color: #ffffff;
            font-weight: 700;
            padding: 12px 15px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            border: 1px solid #2d3748;
        }

        .data-table tbody td {
            padding: 10px 15px;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .course-badge {
            display: inline-block;
            padding: 4px 10px;
            background: #edf2f7;
            border: 1px solid #cbd5e0;
            border-radius: 4px;
            margin-right: 5px;
            margin-bottom: 5px;
            font-size: 10px;
            font-weight: 700;
            color: #2d3748;
        }

        .count-badge {
            font-weight: 800;
            color: #2b6cb0;
        }

        /* Footer */
        .report-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #718096;
            font-weight: 600;
        }

        .page-break {
            page-break-after: always;
        }

        @media print {
            .no-print { display: none !important; }
            body { -webkit-print-color-adjust: exact; }
            .stats-table td { background: #f7fafc !important; }
            .data-table thead th { background: #2d3748 !important; }
            .section-title { background: #ebf8ff !important; }
        }
    </style>
</head>
<body>
    <div class="report-container">
        <div class="confidential-header">Official & Confidential - Internal Use Only</div>

        <div class="report-header">
            <div class="logo-container">
                <img src="{{ asset('logo/MSU-LOGO.jpg') }}" alt="MSU Logo">
            </div>
            <div class="header-text">
                <div class="institution-name">Mindanao State University</div>
                <div class="report-title">General Summary Report</div>
                <div class="report-meta">
                    Generated on {{ now()->format('F d, Y h:i A') }} &bull; 
                    Prepared by: {{ Auth::user()->name }}
                </div>
            </div>
        </div>

        <!-- Summary Statistics Table -->
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

        <!-- Faculty Upload Summary -->
        <div class="section-title">Faculty Upload Summary</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 25%">Faculty Member</th>
                    <th style="width: 20%">Department</th>
                    <th>Course Codes Handled</th>
                    <th style="width: 15%; text-align: right;">Total Modules</th>
                </tr>
            </thead>
            <tbody>
                @foreach($facultyUploadSummary as $faculty)
                <tr>
                    <td style="font-weight: 800; color: #1a202c; font-size: 13px;">{{ $faculty['faculty_name'] }}</td>
                    <td style="font-size: 11px; color: #4a5568; font-weight: 600;">{{ $faculty['department'] }}</td>
                    <td>
                        <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                            @foreach($faculty['course_breakdown'] as $course)
                                <span class="course-badge" style="padding: 4px 8px; background: #f7fafc; border-color: #cbd5e0; color: #2d3748; font-size: 10px;">
                                    <span style="font-weight: 800;">{{ $course['course_code'] }}</span> 
                                    <span style="color: #2b6cb0;">({{ $course['count'] }})</span>
                                </span>
                            @endforeach
                        </div>
                    </td>
                    <td style="text-align: right; font-weight: 900; color: #2b6cb0; font-size: 13px; background-color: #ebf8ff;">{{ $faculty['total_modules'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Department Breakdown -->
        <div class="section-title">Department Breakdown</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 45%">Department Name</th>
                    <th style="width: 15%; text-align: right;">Total Modules</th>
                    <th>Degree Programs Offered</th>
                </tr>
            </thead>
            <tbody>
                @foreach($departmentBreakdown as $dept)
                <tr>
                    <td style="font-weight: 800; color: #1a202c; font-size: 13px;">{{ $dept['department_name'] }}</td>
                    <td style="text-align: right; font-weight: 900; color: #6b46c1; font-size: 13px; background-color: #faf5ff;">{{ $dept['total_modules'] }}</td>
                    <td>
                        <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                            @foreach($dept['degree_programs'] as $program)
                                <span class="course-badge" style="padding: 4px 8px; background: #ffffff; border-color: #cbd5e0; font-size: 10px; color: #2d3748;">{{ $program->course_name }}</span>
                            @endforeach
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="page-break"></div>

        <!-- Student Download Report -->
        <div class="section-title">Student Download Report</div>
        @foreach($studentDownloads as $deptName => $programs)
            <div style="margin-top: 25px; font-weight: 900; color: #1a202c; background: #f7fafc; padding: 10px 15px; border-radius: 4px; border-left: 6px solid #4a5568; font-size: 15px; border-bottom: 1px solid #e2e8f0;">
                Department: {{ $deptName }}
            </div>
            @foreach($programs as $program)
                <div style="margin: 15px 0 10px 15px; font-weight: 800; color: #2d3748; display: flex; items-center; gap: 8px; font-size: 13px;">
                    <span style="color: #3182ce; font-size: 20px; line-height: 1;">&bull;</span> 
                    Program: {{ $program['degree_program'] }}
                </div>
                <table class="data-table" style="width: calc(100% - 15px); margin-left: 15px; border-left: 2px solid #cbd5e0;">
                    <thead>
                        <tr>
                            <th style="width: 40%">Student Name</th>
                            <th style="width: 20%; text-align: center;">ID Number</th>
                            <th style="width: 15%; text-align: right;">Total Downloads</th>
                            <th style="width: 25%">Last Activity Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($program['students'] as $student)
                        <tr>
                            <td style="font-weight: 700; font-size: 12px; color: #1a202c;">{{ $student['name'] }}</td>
                            <td style="font-family: 'Courier New', Courier, monospace; text-align: center; color: #2d3748; font-size: 12px; font-weight: 600;">{{ $student['id_number'] }}</td>
                            <td style="text-align: right; font-weight: 900; color: #c05621; font-size: 12px;">{{ $student['download_count'] }}</td>
                            <td style="color: #4a5568; font-size: 11px; font-weight: 600;">{{ $student['last_download'] ? \Carbon\Carbon::parse($student['last_download'])->format('M d, Y h:i A') : 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        @endforeach

        <!-- Footer -->
        <div class="report-footer">
            <div>&copy; {{ date('Y') }} Mindanao State University - Databanking Module System</div>
            <div>Report ID: SUM-{{ now()->format('Ymd-His') }}</div>
            <div>Generated by: {{ Auth::user()->name }}</div>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
