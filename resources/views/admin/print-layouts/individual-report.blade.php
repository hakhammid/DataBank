<!DOCTYPE html>
<html>
<head>
    <title>{{ $course->course_name }} Activity Report - {{ config('constants.APP_TITLE') }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 14mm;

            @bottom-center {
                content: "Page " counter(page) " of " counter(pages);
                font-size: 8pt;
                font-family: Arial, sans-serif;
            }
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #fff;
            color: #111;
            font-family: Arial, sans-serif;
            font-size: 8.5pt;
            line-height: 1.35;
        }

        .report-container {
            width: 100%;
        }

        .report-header {
            margin-bottom: 14px;
            padding-bottom: 12px;
            border-bottom: 2px solid #111;
            text-align: center;
        }

        .report-header img {
            width: 58px;
            height: auto;
            margin-bottom: 4px;
        }

        .institution {
            font-family: "Times New Roman", Times, serif;
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .header-small {
            font-size: 8.5pt;
        }

        .report-title {
            margin-top: 8px;
            font-size: 12pt;
            font-weight: bold;
            letter-spacing: .5px;
            text-transform: uppercase;
        }

        .report-subtitle {
            margin-top: 3px;
            color: #333;
            font-size: 10pt;
            font-weight: bold;
        }

        .report-meta {
            margin-top: 5px;
            color: #444;
            font-size: 8pt;
        }

        .scope-box {
            margin-bottom: 12px;
            padding: 8px 10px;
            border: 1px solid #999;
            background: #f7f7f7;
        }

        .section-heading {
            margin: 16px 0 7px;
            padding-bottom: 4px;
            border-bottom: 1.5px solid #111;
            font-size: 9.5pt;
            font-weight: bold;
            text-transform: uppercase;
            page-break-after: avoid;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: auto;
        }

        th,
        td {
            border: 1px solid #d0d0d0;
            padding: 5px 6px;
            vertical-align: top;
        }

        th {
            background: #efefef;
            color: #111;
            font-size: 7.5pt;
            font-weight: bold;
            text-align: left;
            text-transform: uppercase;
        }

        tr {
            page-break-inside: avoid;
        }

        .stats-table {
            margin-bottom: 12px;
        }

        .stats-table td {
            width: 16.666%;
            text-align: center;
        }

        .stat-value {
            display: block;
            font-size: 14pt;
            font-weight: bold;
        }

        .stat-label {
            display: block;
            margin-top: 2px;
            color: #555;
            font-size: 7.4pt;
            text-transform: uppercase;
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

        .sub-heading {
            margin: 10px 0 4px;
            font-size: 8.7pt;
            font-weight: bold;
        }

        .report-footer {
            margin-top: 22px;
            padding-top: 8px;
            border-top: 1px solid #bbb;
            color: #555;
            font-size: 7.5pt;
            display: flex;
            justify-content: space-between;
            gap: 10px;
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
        $modulesByCourseCode = $allModules->groupBy('course_code')->sortKeys();
    @endphp

    <div class="report-container">
        <div class="report-header">
            <img src="{{ asset('logo/MSU-LOGO.jpg') }}" alt="MSU Logo">
            <div class="header-small">Republic of the Philippines</div>
            <div class="institution">Mindanao State University</div>
            <div class="header-small">Maguindanao</div>
            <div class="report-title">Individual Course Activity Report</div>
            <div class="report-subtitle">{{ $course->course_name }}</div>
            <div class="report-meta">
                Generated: {{ now()->format('F d, Y h:i A') }}
                | Prepared by: {{ auth()->user()?->name ?? 'Administrator' }}
            </div>
        </div>

        <div class="scope-box">
            <strong>Report Coverage:</strong>
            Course-level module uploads, downloads, views, contributor activity, and downloader activity.
            <br>
            <strong>Semester Scope:</strong> {{ $semester ?: 'All semesters' }}
        </div>

        <div class="section-heading">I. Course Activity Summary</div>
        <table class="stats-table">
            <tr>
                <td>
                    <span class="stat-value">{{ number_format($stats['total_modules']) }}</span>
                    <span class="stat-label">Modules</span>
                </td>
                <td>
                    <span class="stat-value">{{ number_format($stats['major_modules']) }}</span>
                    <span class="stat-label">Major</span>
                </td>
                <td>
                    <span class="stat-value">{{ number_format($stats['minor_modules']) }}</span>
                    <span class="stat-label">Minor</span>
                </td>
                <td>
                    <span class="stat-value">{{ number_format($stats['total_views']) }}</span>
                    <span class="stat-label">Views</span>
                </td>
                <td>
                    <span class="stat-value">{{ number_format($stats['total_downloads']) }}</span>
                    <span class="stat-label">Downloads</span>
                </td>
                <td>
                    <span class="stat-value">{{ number_format($stats['uploaders']) }}</span>
                    <span class="stat-label">Contributors</span>
                </td>
            </tr>
        </table>

        <div class="section-heading">II. Course Code Breakdown</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 16%;">Course Code</th>
                    <th style="width: 12%;" class="text-right">Modules</th>
                    <th style="width: 12%;" class="text-right">Major</th>
                    <th style="width: 12%;" class="text-right">Minor</th>
                    <th style="width: 12%;" class="text-right">Views</th>
                    <th style="width: 12%;" class="text-right">Downloads</th>
                    <th style="width: 24%;">Latest Upload</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courseCodeBreakdown as $row)
                    <tr>
                        <td class="font-bold">{{ $row['course_code'] }}</td>
                        <td class="text-right">{{ number_format($row['modules']) }}</td>
                        <td class="text-right">{{ number_format($row['major_modules']) }}</td>
                        <td class="text-right">{{ number_format($row['minor_modules']) }}</td>
                        <td class="text-right">{{ number_format($row['views']) }}</td>
                        <td class="text-right">{{ number_format($row['downloads']) }}</td>
                        <td>{{ $row['latest_upload'] ? \Carbon\Carbon::parse($row['latest_upload'])->format('M d, Y') : 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No course-code data available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="section-heading">III. Downloads by Role</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 35%;">Role</th>
                    <th style="width: 25%;" class="text-right">Downloads</th>
                    <th style="width: 25%;" class="text-right">Unique Users</th>
                    <th style="width: 15%;">Last Activity</th>
                </tr>
            </thead>
            <tbody>
                @forelse($downloadActivityByRole as $roleActivity)
                    <tr>
                        <td class="font-bold">{{ ucfirst($roleActivity['role']) }}</td>
                        <td class="text-right">{{ number_format($roleActivity['downloads']) }}</td>
                        <td class="text-right">{{ number_format($roleActivity['unique_users']) }}</td>
                        <td>{{ $roleActivity['last_activity'] ? \Carbon\Carbon::parse($roleActivity['last_activity'])->format('M d, Y') : 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No download activity available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="section-heading">IV. Recent Upload and Download Activity</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 13%;">Action</th>
                    <th style="width: 20%;">Actor</th>
                    <th style="width: 10%;">Role</th>
                    <th style="width: 32%;">Module</th>
                    <th style="width: 10%;">Code</th>
                    <th style="width: 15%;">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activityFeed as $activity)
                    <tr>
                        <td class="font-bold">{{ $activity['label'] }}</td>
                        <td>{{ $activity['actor'] }}</td>
                        <td>{{ ucfirst($activity['role']) }}</td>
                        <td>{{ $activity['module_title'] }}</td>
                        <td>{{ $activity['course_code'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($activity['occurred_at'])->format('M d, Y h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No upload or download activity available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="section-heading">V. Faculty Contributors</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;" class="text-center">#</th>
                    <th style="width: 65%;">Faculty</th>
                    <th style="width: 30%;" class="text-right">Modules Uploaded</th>
                </tr>
            </thead>
            <tbody>
                @forelse($uploaders as $index => $uploader)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="font-bold">{{ $uploader->name }}</td>
                        <td class="text-right">{{ number_format($uploader->modules_count) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">No contributors found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="section-heading">VI. Top Downloaders</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;" class="text-center">#</th>
                    <th style="width: 30%;">User</th>
                    <th style="width: 15%;">ID Number</th>
                    <th style="width: 15%;">Role</th>
                    <th style="width: 15%;" class="text-right">Downloads</th>
                    <th style="width: 20%;">Last Activity</th>
                </tr>
            </thead>
            <tbody>
                @forelse($downloaders as $index => $downloader)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="font-bold">{{ $downloader['name'] }}</td>
                        <td>{{ $downloader['id_number'] }}</td>
                        <td>{{ ucfirst($downloader['role']) }}</td>
                        <td class="text-right">{{ number_format($downloader['download_count']) }}</td>
                        <td>{{ $downloader['last_download'] ? \Carbon\Carbon::parse($downloader['last_download'])->format('M d, Y h:i A') : 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No downloader data available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="section-heading">VII. Top Module Engagement</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;" class="text-center">#</th>
                    <th style="width: 35%;">Module</th>
                    <th style="width: 12%;">Code</th>
                    <th style="width: 20%;">Uploader</th>
                    <th style="width: 10%;" class="text-right">Views</th>
                    <th style="width: 10%;" class="text-right">Downloads</th>
                    <th style="width: 8%;">Type</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topModules as $index => $module)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="font-bold">{{ $module->title }}</td>
                        <td>{{ $module->course_code }}</td>
                        <td>{{ $module->user->name ?? 'Unknown' }}</td>
                        <td class="text-right">{{ number_format($module->number_of_views ?? 0) }}</td>
                        <td class="text-right">{{ number_format($module->module_downloads_count ?? 0) }}</td>
                        <td>{{ $module->isMajor ? 'Major' : 'Minor' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No module engagement data available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="section-heading">VIII. Module Inventory</div>
        @forelse($modulesByCourseCode as $courseCode => $groupedModules)
            <div class="sub-heading">
                {{ $courseCode }} <span class="muted">({{ $groupedModules->count() }} {{ Str::plural('module', $groupedModules->count()) }})</span>
            </div>
            <table style="margin-bottom: 8px;">
                <thead>
                    <tr>
                        <th style="width: 5%;" class="text-center">#</th>
                        <th style="width: 32%;">Module Title</th>
                        <th style="width: 11%;">Type</th>
                        <th style="width: 20%;">Uploaded By</th>
                        <th style="width: 10%;" class="text-right">Views</th>
                        <th style="width: 10%;" class="text-right">Downloads</th>
                        <th style="width: 12%;">Date Added</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groupedModules as $index => $module)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="font-bold">{{ $module->title }}</td>
                            <td>{{ $module->isMajor ? 'Major' : 'Minor' }}</td>
                            <td>{{ $module->user->name ?? 'Unknown' }}</td>
                            <td class="text-right">{{ number_format($module->number_of_views ?? 0) }}</td>
                            <td class="text-right">{{ number_format($module->module_downloads_count ?? 0) }}</td>
                            <td>{{ optional($module->created_at)->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @empty
            <table>
                <tr>
                    <td class="text-center">No modules found for this course.</td>
                </tr>
            </table>
        @endforelse

        <div class="report-footer">
            <span>&copy; {{ date('Y') }} Mindanao State University - MODUBANK</span>
            <span>Report ID: CRS-{{ $course->id }}-{{ now()->format('Ymd-His') }}</span>
            <span>Generated by: {{ auth()->user()?->name ?? 'Administrator' }}</span>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
