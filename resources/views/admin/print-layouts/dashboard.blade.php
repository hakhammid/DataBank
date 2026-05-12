<!DOCTYPE html>
<html>
<head>
    <title>Department Modules Report - {{ config('constants.APP_TITLE') }}</title>
    <style>
        @page {
            size: A4;
            margin: 20mm 15mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #1a1a2e;
            padding: 0;
            background: #fff;
            font-size: 12px;
        }

        .report-container {
            max-width: 100%;
            margin: 0 auto;
            padding: 30px;
        }

        .report-header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 3px solid #1a1a2e;
            margin-bottom: 25px;
        }

        .report-header .logo-section {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 10px;
        }

        .report-header .logo-section img {
            width: 55px;
            height: 55px;
            object-fit: contain;
        }

        .report-header .institution-name {
            font-size: 22px;
            font-weight: 700;
            color: #1a1a2e;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .report-header .report-title {
            font-size: 16px;
            font-weight: 600;
            color: #2d3748;
            margin-top: 8px;
            letter-spacing: 0.5px;
        }

        .report-header .report-subtitle {
            font-size: 11px;
            color: #718096;
            margin-top: 4px;
        }

        .summary-section {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .summary-card {
            flex: 1;
            min-width: 120px;
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px 16px;
            text-align: center;
        }

        .summary-card .card-value {
            font-size: 22px;
            font-weight: 700;
            color: #1a1a2e;
        }

        .summary-card .card-label {
            font-size: 10px;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 2px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
        }

        .data-table thead th {
            background: #1a1a2e;
            color: #ffffff;
            font-weight: 600;
            padding: 10px 12px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        .data-table thead th:first-child {
            border-radius: 4px 0 0 0;
        }

        .data-table thead th:last-child {
            border-radius: 0 4px 0 0;
        }

        .data-table tbody td {
            padding: 9px 12px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f7fafc;
        }

        .data-table tbody tr:last-child td {
            border-bottom: 2px solid #1a1a2e;
        }

        .row-number {
            color: #a0aec0;
            font-weight: 600;
            font-size: 10px;
            text-align: center;
            width: 35px;
        }

        /* Progress bar for module distribution */
        .progress-bar-container {
            width: 100%;
            background: #e2e8f0;
            border-radius: 4px;
            height: 8px;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            background: #1a1a2e;
            border-radius: 4px;
            transition: width 0.3s ease;
        }

        .report-footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #1a1a2e;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 10px;
            color: #718096;
        }

        .report-footer .footer-left {
            text-align: left;
        }

        .report-footer .footer-right {
            text-align: right;
        }

        .confidential-badge {
            display: inline-block;
            border: 1px solid #e53e3e;
            color: #e53e3e;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }

        .page-break {
            page-break-after: always;
        }

        @media print {
            body { padding: 0; }
            .report-container { padding: 0; }
            .no-print { display: none !important; }
            .summary-card, .data-table thead th, .data-table tbody tr:nth-child(even),
            .progress-bar-container, .progress-bar-fill {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="report-container">
        <div class="report-header">
            <div class="logo-section">
                <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('logo/MSU-LOGO.jpg'))) }}" alt="MSU Logo">
                <div class="institution-name">{{ config('constants.APP_TITLE') }}</div>
            </div>
            <div class="report-title">Department Modules Distribution Report</div>
            <div class="report-subtitle">
                Generated on {{ \Carbon\Carbon::now()->format('F j, Y \a\t g:i A') }} &bull;
                Prepared by: {{ Auth::user()->name ?? 'System Administrator' }}
            </div>
        </div>

        <div class="summary-section">
            <div class="summary-card">
                <div class="card-value">{{ count($departments) }}</div>
                <div class="card-label">Total Departments</div>
            </div>
            <div class="summary-card">
                <div class="card-value">{{ $moduleCount }}</div>
                <div class="card-label">Total Modules</div>
            </div>
            <div class="summary-card">
                <div class="card-value">
                    {{ count($departments) > 0 ? round($moduleCount / count($departments), 1) : 0 }}
                </div>
                <div class="card-label">Avg per Department</div>
            </div>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th style="text-align: center;">#</th>
                    <th>Department</th>
                    <th style="text-align: center;">Modules</th>
                    <th>Distribution</th>
                    <th style="text-align: center;">Share (%)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($departments as $index => $department)
                    @php
                        $percentage = $moduleCount > 0 ? round(($department->modules_count / $moduleCount) * 100, 1) : 0;
                    @endphp
                    <tr>
                        <td class="row-number">{{ $index + 1 }}</td>
                        <td style="font-weight: 600;">{{ $department->department_name }}</td>
                        <td style="text-align: center; font-weight: 600;">{{ $department->modules_count }}</td>
                        <td>
                            <div class="progress-bar-container">
                                <div class="progress-bar-fill" style="width: {{ $percentage }}%;"></div>
                            </div>
                        </td>
                        <td style="text-align: center;">{{ $percentage }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="report-footer">
            <div class="footer-left">
                <div class="confidential-badge">Confidential</div>
                <div>{{ config('constants.APP_TITLE') }} &copy; {{ date('Y') }} &bull; All Rights Reserved</div>
            </div>
            <div class="footer-right">
                <div>Report Reference: DMOD-{{ date('Ymd-His') }}</div>
                <div>Page 1 of 1</div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
                setTimeout(function() {
                    window.close();
                }, 100);
            }, 300);
        });
    </script>
</body>
</html>
