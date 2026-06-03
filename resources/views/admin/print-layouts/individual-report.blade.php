<!DOCTYPE html>
<html>
<head>
    <title>{{ $course->course_name }} Report - {{ config('constants.APP_TITLE') }}</title>
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

        .report-subtitle {
            font-size: 10pt;
            color: #444;
            margin-top: 4px;
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
                    <td><span class="badge">{{ $module->course_code }}</span></td>
                    <td class="text-center">
                        <span class="badge" style="background-color: {{ $module->isMajor ? '#e0f2fe' : '#f1f5f9' }}; border-color: {{ $module->isMajor ? '#7dd3fc' : '#cbd5e1' }}; color: {{ $module->isMajor ? '#0369a1' : '#334155' }}">
                            {{ $module->isMajor ? 'Major' : 'Minor' }}
                        </span>
                    </td>
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
