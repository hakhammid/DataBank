<!DOCTYPE html>
<html>
<head>
    <title>{{ $course->course_name }} Report - {{ config('constants.APP_TITLE') }}</title>
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
            line-height: 1.4;
            color: #1a1a2e;
            background: #fff;
            font-size: 11px;
        }

        .report-container {
            width: 100%;
            margin: 0 auto;
        }

        /* Confidential Watermark/Header */
        .confidential-header {
            text-align: right;
            color: #e53e3e;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 2px;
            margin-bottom: 10px;
            border-bottom: 1px solid #fed7d7;
            padding-bottom: 5px;
        }

        /* Header Section */
        .report-header {
            display: flex;
            align-items: center;
            justify-content: center;
            padding-bottom: 20px;
            border-bottom: 3px solid #1a1a2e;
            margin-bottom: 20px;
            position: relative;
        }

        .logo-container {
            position: absolute;
            left: 0;
            top: 0;
        }

        .logo-container img {
            width: 80px;
            height: auto;
        }

        .header-text {
            text-align: center;
        }

        .institution-name {
            font-size: 24px;
            font-weight: 800;
            color: #1a1a2e;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .report-title {
            font-size: 18px;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 4px;
        }

        .report-meta {
            font-size: 10px;
            color: #718096;
        }

        /* Summary Stats Table */
        .stats-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .stats-table td {
            width: 16.66%;
            padding: 10px;
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            text-align: center;
        }

        .stats-value {
            font-size: 18px;
            font-weight: 700;
            color: #2b6cb0;
        }

        .stats-label {
            font-size: 9px;
            color: #718096;
            text-transform: uppercase;
            font-weight: 600;
        }

        /* Section Titles */
        .section-title {
            font-size: 14px;
            font-weight: 700;
            color: #1a1a2e;
            margin: 20px 0 10px 0;
            padding-left: 8px;
            border-left: 4px solid #2b6cb0;
            background: #ebf8ff;
            padding: 8px;
            text-transform: uppercase;
        }

        /* Data Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .data-table thead th {
            background: #2d3748;
            color: #ffffff;
            font-weight: 600;
            padding: 8px 10px;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            border: 1px solid #2d3748;
        }

        .data-table tbody td {
            padding: 6px 10px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .type-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .type-major { background: #c6f6d5; color: #22543d; }
        .type-minor { background: #e9d8fd; color: #44337a; }

        /* Footer */
        .report-footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            font-size: 9px;
            color: #a0aec0;
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
                <div class="report-title">{{ $course->course_name }} Report</div>
                <div class="report-meta">
                    Generated on {{ now()->format('F d, Y h:i A') }} &bull; 
                    Prepared by: {{ Auth::user()->name }}
                </div>
            </div>
        </div>

        <!-- Course Statistics Table -->
        <table class="stats-table">
            <tr>
                <td>
                    <div class="stats-value">{{ $stats['total_modules'] }}</div>
                    <div class="stats-label">Total Modules</div>
                </td>
                <td>
                    <div class="stats-value">{{ $stats['major_modules'] }}</div>
                    <div class="stats-label">Major Subjects</div>
                </td>
                <td>
                    <div class="stats-value">{{ $stats['minor_modules'] }}</div>
                    <div class="stats-label">Minor Subjects</div>
                </td>
                <td>
                    <div class="stats-value">{{ number_format($stats['total_views']) }}</div>
                    <div class="stats-label">Total Views</div>
                </td>
                <td>
                    <div class="stats-value">{{ number_format($stats['total_downloads']) }}</div>
                    <div class="stats-label">Total Downloads</div>
                </td>
                <td>
                    <div class="stats-value">{{ $stats['uploaders'] }}</div>
                    <div class="stats-label">Contributors</div>
                </td>
            </tr>
        </table>

        <!-- Top Contributors Section -->
        @if($uploaders->isNotEmpty())
        <div class="section-title">Key Contributors & Faculty Uploaders</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 60%">Faculty Member Name</th>
                    <th style="text-align: center; width: 40%;">Total Modules Uploaded for this Course</th>
                </tr>
            </thead>
            <tbody>
                @foreach($uploaders as $uploader)
                <tr>
                    <td style="font-weight: 700; color: #2d3748;">{{ $uploader->name }}</td>
                    <td style="text-align: center; font-weight: 800; color: #2b6cb0; background-color: #f0f4f8;">{{ $uploader->modules_count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <!-- All Modules Table -->
        <div class="section-title">Course Modules Inventory</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 32%">Module Title / Course Code</th>
                    <th style="width: 10%; text-align: center;">Subject Type</th>
                    <th style="width: 20%">Department</th>
                    <th style="width: 18%">Uploaded By</th>
                    <th style="width: 10%; text-align: center;">Views</th>
                    <th style="width: 10%; text-align: center;">Downloads</th>
                </tr>
            </thead>
            <tbody>
                @foreach($modules as $module)
                <tr>
                    <td>
                        <div style="font-weight: 700; color: #2d3748;">{{ $module->title }}</div>
                        <div style="font-size: 8px; font-weight: 600; color: #718096; background: #f7fafc; padding: 1px 4px; border-radius: 2px; width: fit-content; border: 1px solid #e2e8f0; margin-top: 2px;">{{ $module->course_code }}</div>
                    </td>
                    <td style="text-align: center; vertical-align: middle;">
                        <span class="type-badge {{ $module->isMajor ? 'type-major' : 'type-minor' }}" style="border: 1px solid rgba(0,0,0,0.05);">
                            {{ $module->isMajor ? 'Major' : 'Minor' }}
                        </span>
                    </td>
                    <td style="font-size: 10px;">{{ $module->department->department_name ?? 'N/A' }}</td>
                    <td style="font-weight: 600;">{{ $module->user->name }}</td>
                    <td style="text-align: center; font-weight: 700; color: #4a5568;">{{ number_format($module->number_of_views) }}</td>
                    <td style="text-align: center; font-weight: 700; color: #2b6cb0;">{{ number_format($module->module_downloads_count) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Footer -->
        <div class="report-footer">
            <div>&copy; {{ date('Y') }} Mindanao State University - Databanking Module System</div>
            <div>Report ID: CRS-{{ $course->id }}-{{ now()->format('Ymd-His') }}</div>
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
