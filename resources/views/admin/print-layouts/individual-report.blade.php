<!DOCTYPE html>
<html>
<head>
    <title>{{ $course->course_name }} Report - {{ config('constants.APP_TITLE') }}</title>
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

        .report-subtitle {
            font-size: 9pt;
            margin-top: 2px;
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
            <div class="report-title">Individual Course Report</div>
            <div class="report-subtitle">{{ $course->course_name }}</div>
            <div class="report-meta">
                Date Generated: {{ now()->format('F d, Y — h:i A') }}&nbsp;&nbsp;|&nbsp;&nbsp;Prepared by: {{ Auth::user()->name }}
            </div>
        </div>

        {{-- ── Applied Filters ── --}}
        @if(!empty($semester))
        <div class="filter-info">
            <strong>Filter Applied:</strong> Semester: {{ $semester }}
        </div>
        @endif

        {{-- ── I. Course Statistics ── --}}
        <div class="section-heading">I. Course Statistics</div>
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

        {{-- ── II. Faculty Contributors ── --}}
        @if($uploaders->isNotEmpty())
        <div class="section-heading">II. Faculty Contributors</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 70%;">Faculty Name</th>
                    <th style="width: 25%; text-align: right;">Modules Uploaded</th>
                </tr>
            </thead>
            <tbody>
                @foreach($uploaders as $index => $uploader)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-bold">{{ $uploader->name }}</td>
                    <td class="text-right font-bold">{{ $uploader->modules_count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        {{-- ── III. Module Inventory ── --}}
        <div class="section-heading">{{ $uploaders->isNotEmpty() ? 'III' : 'II' }}. Module Inventory</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 4%;">#</th>
                    <th style="width: 28%;">Module Title</th>
                    <th style="width: 10%;">Code</th>
                    <th style="width: 7%; text-align: center;">Type</th>
                    <th style="width: 18%;">Department</th>
                    <th style="width: 15%;">Uploaded By</th>
                    <th style="width: 9%; text-align: right;">Views</th>
                    <th style="width: 9%; text-align: right;">Downloads</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalViews = 0;
                    $totalDL = 0;
                @endphp
                @forelse($modules as $index => $module)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-bold">{{ $module->title }}</td>
                    <td>{{ $module->course_code }}</td>
                    <td class="text-center">{{ $module->isMajor ? 'Major' : 'Minor' }}</td>
                    <td>{{ $module->department->department_name ?? 'N/A' }}</td>
                    <td>{{ $module->user->name }}</td>
                    <td class="text-right">{{ number_format($module->number_of_views) }}</td>
                    <td class="text-right">{{ number_format($module->module_downloads_count) }}</td>
                </tr>
                @php
                    $totalViews += $module->number_of_views;
                    $totalDL += $module->module_downloads_count;
                @endphp
                @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 6px; font-style: italic; color: #666;">No modules found for this course.</td>
                </tr>
                @endforelse
                @if(count($modules) > 0)
                <tr class="total-row">
                    <td colspan="6" class="text-right">Grand Total</td>
                    <td class="text-right">{{ number_format($totalViews) }}</td>
                    <td class="text-right">{{ number_format($totalDL) }}</td>
                </tr>
                @endif
            </tbody>
        </table>

        {{-- ── Footer ── --}}
        <div class="report-footer">
            <span>&copy; {{ date('Y') }} Mindanao State University — Databanking Module System</span>
            <span>Report ID: CRS-{{ $course->id }}-{{ now()->format('Ymd-His') }}</span>
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
