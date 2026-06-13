<x-admin-layout :title="'Reports - Activity Summary'">
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
        $filterBadges = collect();

        if ($selectedDepartment) {
            $filterBadges->push(['label' => 'Department', 'value' => $selectedDepartment->department_name]);
        }
        if ($selectedCourse) {
            $filterBadges->push(['label' => 'Program', 'value' => $selectedCourse->course_name]);
        }
        if (!empty($filters['semester'] ?? null)) {
            $filterBadges->push(['label' => 'Semester', 'value' => $filters['semester']]);
        }
        if ($selectedFaculty) {
            $filterBadges->push(['label' => 'Faculty', 'value' => $selectedFaculty->name]);
        }
    @endphp

    <main class="flex-1 max-h-full p-5 lg:mt-0 mt-20">
        <div class="flex flex-col gap-4 pb-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500">Administrative report dashboard</p>
                <h1 class="mt-1 text-2xl/8 font-semibold text-zinc-950 sm:text-xl/8">Activity Logging and Reporting</h1>
                <p class="mt-2 max-w-3xl text-sm text-zinc-600">
                    Monitor file uploads, downloads, module views, and role-based activity across the repository.
                </p>
            </div>

            <div class="relative" x-data="{ open: false }">
                <x-my-secondary-button @click="open = !open" @click.away="open = false">
                    <svg class="w-5 h-5 text-zinc-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </x-my-secondary-button>

                <div x-cloak x-show="open" x-transition class="absolute right-0 z-50 mt-2 w-48 overflow-hidden rounded-lg border border-zinc-200 bg-white shadow-lg">
                    <button onclick="exportToPDF(this)" class="flex w-full items-center gap-3 px-4 py-3 text-left text-sm text-zinc-700 transition-colors hover:bg-zinc-100">
                        <svg class="w-5 h-5 text-zinc-900" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 1v5h5v10H6V3h7z" />
                            <path d="M8 13h8v2H8zm0 3h8v2H8zm0-6h3v2H8z" />
                        </svg>
                        Export to PDF
                    </button>
                    <button onclick="printReport()" class="flex w-full items-center gap-3 px-4 py-3 text-left text-sm text-zinc-700 transition-colors hover:bg-zinc-100">
                        <svg class="w-5 h-5 text-zinc-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Print Report
                    </button>
                </div>
            </div>
        </div>

        <section class="mb-6 overflow-hidden rounded-lg border border-zinc-200 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-zinc-200 bg-zinc-50/80 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <h2 class="text-sm font-semibold text-zinc-800">Report Scope</h2>
                </div>
                @if($hasFilters)
                    <a href="{{ route('reports.summary') }}" class="inline-flex items-center gap-1 text-xs font-medium text-red-600 transition-colors hover:text-red-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Clear filters
                    </a>
                @endif
            </div>

            <div class="p-6">
                <form method="GET" action="{{ route('reports.summary') }}">
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">
                        <div>
                            <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-zinc-600">Department</label>
                            <select name="department_id" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2.5 text-sm shadow-sm transition-colors hover:border-zinc-400 focus:border-zinc-900 focus:ring-2 focus:ring-zinc-900/20">
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ ($filters['department_id'] ?? '') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->department_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-zinc-600">Degree Program</label>
                            <select name="course_id" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2.5 text-sm shadow-sm transition-colors hover:border-zinc-400 focus:border-zinc-900 focus:ring-2 focus:ring-zinc-900/20">
                                <option value="">All Programs</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" data-department-id="{{ $course->department_id }}" {{ ($filters['course_id'] ?? '') == $course->id ? 'selected' : '' }}>
                                        {{ $course->course_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-zinc-600">Semester</label>
                            <select name="semester" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2.5 text-sm shadow-sm transition-colors hover:border-zinc-400 focus:border-zinc-900 focus:ring-2 focus:ring-zinc-900/20">
                                <option value="">All Semesters</option>
                                <option value="1st" {{ ($filters['semester'] ?? '') == '1st' ? 'selected' : '' }}>1st Semester</option>
                                <option value="2nd" {{ ($filters['semester'] ?? '') == '2nd' ? 'selected' : '' }}>2nd Semester</option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-zinc-600">Faculty Member</label>
                            <select name="faculty_id" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2.5 text-sm shadow-sm transition-colors hover:border-zinc-400 focus:border-zinc-900 focus:ring-2 focus:ring-zinc-900/20">
                                <option value="">All Faculty</option>
                                @foreach($faculties as $faculty)
                                    <option value="{{ $faculty->id }}" data-department-id="{{ $faculty->department_id }}" {{ ($filters['faculty_id'] ?? '') == $faculty->id ? 'selected' : '' }}>
                                        {{ $faculty->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-5 flex flex-col gap-4 border-t border-zinc-100 pt-5 lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex flex-wrap gap-2">
                            @forelse($filterBadges as $badge)
                                <span class="inline-flex items-center gap-2 rounded-md border border-zinc-200 bg-zinc-50 px-3 py-1.5 text-xs text-zinc-700">
                                    <span class="font-semibold text-zinc-500">{{ $badge['label'] }}</span>
                                    <span class="font-medium text-zinc-900">{{ $badge['value'] }}</span>
                                </span>
                            @empty
                                <span class="text-sm text-zinc-500">Showing the complete repository activity scope.</span>
                            @endforelse
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            @if($selectedCourse)
                                <a href="{{ route('reports.individual', $selectedCourse->id) }}" class="inline-flex items-center gap-2 rounded-lg border border-blue-200 bg-blue-50 px-5 py-2.5 text-sm font-medium text-blue-700 shadow-sm transition-colors hover:bg-blue-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Course Report
                                </a>
                            @endif
                            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-colors hover:bg-zinc-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                Apply Filters
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </section>

        <section class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">
            <div class="rounded-lg border border-zinc-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500">File Uploads</p>
                <p class="mt-2 text-3xl font-bold text-blue-700">{{ number_format($totalModules) }}</p>
                <p class="mt-1 text-xs text-zinc-500">Modules in scope</p>
            </div>
            <div class="rounded-lg border border-zinc-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500">Downloads</p>
                <p class="mt-2 text-3xl font-bold text-indigo-700">{{ number_format($totalDownloads) }}</p>
                <p class="mt-1 text-xs text-zinc-500">Download records</p>
            </div>
            <div class="rounded-lg border border-zinc-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500">Module Views</p>
                <p class="mt-2 text-3xl font-bold text-amber-700">{{ number_format($totalViews ?? 0) }}</p>
                <p class="mt-1 text-xs text-zinc-500">Recorded views</p>
            </div>
            <div class="rounded-lg border border-zinc-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500">Contributors</p>
                <p class="mt-2 text-3xl font-bold text-emerald-700">{{ number_format($totalFaculty) }}</p>
                <p class="mt-1 text-xs text-zinc-500">Uploaders in scope</p>
            </div>
            <div class="rounded-lg border border-zinc-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500">Students</p>
                <p class="mt-2 text-3xl font-bold text-rose-700">{{ number_format($totalStudents) }}</p>
                <p class="mt-1 text-xs text-zinc-500">Registered learners</p>
            </div>
        </section>

        <div class="mb-8 grid grid-cols-1 gap-6 xl:grid-cols-2">
            <section class="rounded-lg border border-zinc-200 bg-white shadow-sm">
                <div class="border-b border-zinc-200 px-6 py-4">
                    <h2 class="text-base font-bold text-zinc-900">User Role Summary</h2>
                    <p class="mt-1 text-sm text-zinc-500">Faculty and student accounts across the system.</p>
                </div>
                <div class="divide-y divide-zinc-100">
                    @forelse($roleBreakdown as $role)
                        <div class="flex items-center justify-between px-6 py-4">
                            <div>
                                <p class="text-sm font-semibold text-zinc-900">{{ ucfirst($role['role']) }}</p>
                                <p class="text-xs text-zinc-500">Account role</p>
                            </div>
                            <span class="text-xl font-bold text-zinc-900">{{ number_format($role['total']) }}</span>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-sm text-zinc-500">No user role data available.</div>
                    @endforelse
                </div>
            </section>

            <section class="rounded-lg border border-zinc-200 bg-white shadow-sm">
                <div class="border-b border-zinc-200 px-6 py-4">
                    <h2 class="text-base font-bold text-zinc-900">Recent Faculty and Student Sessions</h2>
                    <p class="mt-1 text-sm text-zinc-500">Saved access history from login and session activity.</p>
                </div>
                <div class="divide-y divide-zinc-100">
                    @forelse($recentUserActivity as $session)
                        <div class="px-6 py-3.5">
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-zinc-900">{{ $session['name'] }}</p>
                                    <p class="text-xs uppercase tracking-wide text-zinc-500">
                                        {{ $session['role'] }} - {{ $session['id_number'] ?? 'N/A' }}
                                    </p>
                                    <p class="mt-1 text-xs text-zinc-500">
                                        Login:
                                        {{ $session['login_at'] ? $session['login_at']->format('M d, Y h:i A') : 'Current session' }}
                                    </p>
                                </div>
                                <div class="shrink-0 text-right">
                                    @if($session['logout_at'] ?? null)
                                        <p class="text-xs font-semibold text-zinc-500">Signed out</p>
                                        <p class="mt-1 text-xs text-zinc-500">{{ $session['logout_at']->format('M d, h:i A') }}</p>
                                    @else
                                        <p class="text-xs font-semibold text-emerald-700">Active</p>
                                        <p class="mt-1 text-xs text-zinc-500">
                                            {{ $session['last_seen_at'] ? $session['last_seen_at']->diffForHumans() : 'No timestamp' }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-sm text-zinc-500">No faculty or student access records available.</div>
                    @endforelse
                </div>
            </section>
        </div>

        <div class="summary-feed-grid mb-8 grid grid-cols-1 gap-6 xl:grid-cols-2">
            <section
                x-data="{ page: 1, perPage: 5, total: {{ count($activityFeed ?? []) }}, get pages() { return Math.max(1, Math.ceil(this.total / this.perPage)); } }"
                class="compact-report-card flex flex-col rounded-lg border border-zinc-200 bg-white shadow-sm"
            >
                <div class="compact-card-header border-b border-zinc-200 px-6 py-4">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <h2 class="text-base font-bold text-zinc-900">Recent Repository Activity</h2>
                            <p class="mt-1 text-sm text-zinc-500">Combined activity log for the selected report scope.</p>
                        </div>
                        @if(count($activityFeed ?? []) > 0)
                            <span class="shrink-0 rounded-md border border-zinc-200 bg-zinc-50 px-2.5 py-1 text-xs font-semibold text-zinc-600">
                                {{ count($activityFeed) }} {{ Str::plural('record', count($activityFeed)) }}
                            </span>
                        @endif
                    </div>
                </div>
                @if(count($activityFeed ?? []) > 0)
                    <div class="compact-activity-list divide-y divide-zinc-100">
                    @foreach($activityFeed as $index => $activity)
                        @php
                            $isUpload = $activity['type'] === 'upload';
                            $activityDate = \Carbon\Carbon::parse($activity['occurred_at']);
                            $activityMeta = $activity['actor'] . ' (' . ucfirst($activity['role']) . ') - ' . $activity['course_code'] . ' - ' . $activity['department'];
                        @endphp
                        <div x-show="{{ $index }} >= (page - 1) * perPage && {{ $index }} < page * perPage" x-cloak class="compact-activity-row px-6 py-4">
                            <div class="compact-activity-content">
                                <div class="compact-activity-icon mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-lg {{ $isUpload ? 'bg-blue-50 text-blue-700' : 'bg-indigo-50 text-indigo-700' }}">
                                    @if($isUpload)
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M12 4v12m0-12l-4 4m4-4l4 4" />
                                        </svg>
                                    @else
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="compact-activity-heading">
                                        <p class="truncate text-sm font-semibold text-zinc-900" title="{{ $activity['label'] }}">{{ $activity['label'] }}</p>
                                        <p class="compact-activity-date text-xs text-zinc-500">{{ $activityDate->format('M d, Y h:i A') }}</p>
                                    </div>
                                    <p class="mt-1 truncate text-sm text-zinc-700" title="{{ $activity['module_title'] }}">{{ $activity['module_title'] }}</p>
                                    <p class="compact-activity-meta mt-1 text-xs text-zinc-500" title="{{ $activityMeta }}">{{ $activityMeta }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </div>

                    <div class="compact-card-footer flex flex-col gap-3 border-t border-zinc-200 bg-zinc-50/70 px-6 py-3 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-xs font-medium text-zinc-500">
                            Showing
                            <span x-text="((page - 1) * perPage) + 1"></span>
                            -
                            <span x-text="Math.min(page * perPage, total)"></span>
                            of
                            <span x-text="total"></span>
                        </p>
                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                @click="page = Math.max(1, page - 1)"
                                :disabled="page === 1"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-zinc-300 bg-white text-zinc-700 shadow-sm transition-colors hover:bg-zinc-100 disabled:cursor-not-allowed disabled:opacity-40"
                                aria-label="Previous activity page"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <span class="min-w-16 text-center text-xs font-semibold text-zinc-600">
                                <span x-text="page"></span> / <span x-text="pages"></span>
                            </span>
                            <button
                                type="button"
                                @click="page = Math.min(pages, page + 1)"
                                :disabled="page === pages"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-zinc-300 bg-white text-zinc-700 shadow-sm transition-colors hover:bg-zinc-100 disabled:cursor-not-allowed disabled:opacity-40"
                                aria-label="Next activity page"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @else
                    <div>
                        <div class="px-6 py-10 text-center text-sm text-zinc-500">No upload or download activity found.</div>
                    </div>
                @endif
            </section>

            <section
                x-data="{ page: 1, perPage: 4, total: {{ count($topModules ?? []) }}, get pages() { return Math.max(1, Math.ceil(this.total / this.perPage)); } }"
                class="compact-report-card flex flex-col rounded-lg border border-zinc-200 bg-white shadow-sm"
            >
                <div class="compact-card-header border-b border-zinc-200 px-6 py-4">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <h2 class="text-base font-bold text-zinc-900">Top Module Engagement</h2>
                            <p class="mt-1 text-sm text-zinc-500">Modules ranked by combined views and downloads.</p>
                        </div>
                        @if(count($topModules ?? []) > 0)
                            <span class="shrink-0 rounded-md border border-zinc-200 bg-zinc-50 px-2.5 py-1 text-xs font-semibold text-zinc-600">
                                {{ count($topModules) }} {{ Str::plural('module', count($topModules)) }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="compact-table-wrap">
                    <table class="compact-module-table min-w-full divide-y divide-zinc-200">
                        <thead class="bg-zinc-50">
                            <tr>
                                <th style="width: 52%;" class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wide text-zinc-500">Module</th>
                                <th style="width: 24%;" class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wide text-zinc-500">Uploader</th>
                                <th style="width: 10%;" class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wide text-zinc-500">Views</th>
                                <th style="width: 14%;" class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wide text-zinc-500">Downloads</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 bg-white">
                            @forelse($topModules as $index => $module)
                                @php
                                    $moduleDepartment = $module->department->department_name ?? 'N/A';
                                    $moduleUploader = $module->user->name ?? 'Unknown';
                                    $moduleMeta = $module->course_code . ' - ' . $moduleDepartment;
                                @endphp
                                <tr x-show="{{ $index }} >= (page - 1) * perPage && {{ $index }} < page * perPage" x-cloak class="hover:bg-zinc-50">
                                    <td class="px-6 py-4">
                                        <p class="compact-module-title text-sm font-semibold text-zinc-900" title="{{ $module->title }}">{{ $module->title }}</p>
                                        <p class="compact-module-meta mt-1 text-xs text-zinc-500" title="{{ $moduleMeta }}">{{ $moduleMeta }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-zinc-700"><span class="compact-uploader" title="{{ $moduleUploader }}">{{ $moduleUploader }}</span></td>
                                    <td class="metric-cell px-6 py-4 text-center text-sm font-semibold text-zinc-900">{{ number_format($module->number_of_views ?? 0) }}</td>
                                    <td class="metric-cell px-6 py-4 text-center text-sm font-semibold text-indigo-700">{{ number_format($module->module_downloads_count ?? 0) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-sm text-zinc-500">No module engagement data available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if(count($topModules ?? []) > 0)
                    <div class="compact-card-footer flex flex-col gap-3 border-t border-zinc-200 bg-zinc-50/70 px-6 py-3 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-xs font-medium text-zinc-500">
                            Showing
                            <span x-text="((page - 1) * perPage) + 1"></span>
                            -
                            <span x-text="Math.min(page * perPage, total)"></span>
                            of
                            <span x-text="total"></span>
                        </p>
                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                @click="page = Math.max(1, page - 1)"
                                :disabled="page === 1"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-zinc-300 bg-white text-zinc-700 shadow-sm transition-colors hover:bg-zinc-100 disabled:cursor-not-allowed disabled:opacity-40"
                                aria-label="Previous module page"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <span class="min-w-16 text-center text-xs font-semibold text-zinc-600">
                                <span x-text="page"></span> / <span x-text="pages"></span>
                            </span>
                            <button
                                type="button"
                                @click="page = Math.min(pages, page + 1)"
                                :disabled="page === pages"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-zinc-300 bg-white text-zinc-700 shadow-sm transition-colors hover:bg-zinc-100 disabled:cursor-not-allowed disabled:opacity-40"
                                aria-label="Next module page"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif
            </section>
        </div>

        <section x-data="{ activeTab: 'faculty' }" class="rounded-lg border border-zinc-200 bg-white shadow-sm">
            <div class="border-b border-zinc-200 px-6 py-4">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h2 class="text-base font-bold text-zinc-900">Detailed Activity Reports</h2>
                        <p class="mt-1 text-sm text-zinc-500">Uploads, department coverage, and downloader activity.</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" @click="activeTab = 'faculty'" class="rounded-md px-4 py-2 text-sm font-semibold transition-colors" :class="activeTab === 'faculty' ? 'bg-zinc-900 text-white' : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200'">Faculty Uploads</button>
                        <button type="button" @click="activeTab = 'departments'" class="rounded-md px-4 py-2 text-sm font-semibold transition-colors" :class="activeTab === 'departments' ? 'bg-zinc-900 text-white' : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200'">Departments</button>
                        <button type="button" @click="activeTab = 'downloads'" class="rounded-md px-4 py-2 text-sm font-semibold transition-colors" :class="activeTab === 'downloads' ? 'bg-zinc-900 text-white' : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200'">Downloads</button>
                    </div>
                </div>
            </div>

            <div x-cloak x-show="activeTab === 'faculty'" x-transition class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200">
                    <thead class="bg-zinc-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wide text-zinc-500">Faculty</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wide text-zinc-500">Department</th>
                            <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wide text-zinc-500">Uploads</th>
                            <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wide text-zinc-500">Views</th>
                            <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wide text-zinc-500">Downloads</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wide text-zinc-500">Course Codes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @forelse($facultyUploadSummary as $faculty)
                            <tr class="hover:bg-zinc-50">
                                <td class="px-6 py-4 text-sm font-semibold text-zinc-900">{{ $faculty['faculty_name'] }}</td>
                                <td class="px-6 py-4 text-sm text-zinc-600">{{ $faculty['department'] }}</td>
                                <td class="px-6 py-4 text-center text-sm font-bold text-zinc-900">{{ number_format($faculty['total_modules']) }}</td>
                                <td class="px-6 py-4 text-center text-sm font-semibold text-amber-700">{{ number_format($faculty['total_views'] ?? 0) }}</td>
                                <td class="px-6 py-4 text-center text-sm font-semibold text-indigo-700">{{ number_format($faculty['total_downloads'] ?? 0) }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($faculty['course_breakdown'] as $course)
                                            <span class="rounded-md border border-zinc-200 bg-zinc-50 px-2 py-1 text-xs font-medium text-zinc-700">
                                                {{ $course['course_code'] }} ({{ $course['count'] }})
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-sm text-zinc-500">No faculty upload data found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div x-cloak x-show="activeTab === 'departments'" x-transition class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200">
                    <thead class="bg-zinc-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wide text-zinc-500">Department</th>
                            <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wide text-zinc-500">Modules</th>
                            <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wide text-zinc-500">Views</th>
                            <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wide text-zinc-500">Downloads</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wide text-zinc-500">Degree Programs</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @forelse($departmentBreakdown as $dept)
                            <tr class="hover:bg-zinc-50">
                                <td class="px-6 py-4 text-sm font-semibold text-zinc-900">{{ $dept['department_name'] }}</td>
                                <td class="px-6 py-4 text-center text-sm font-bold text-zinc-900">{{ number_format($dept['total_modules']) }}</td>
                                <td class="px-6 py-4 text-center text-sm font-semibold text-amber-700">{{ number_format($dept['total_views'] ?? 0) }}</td>
                                <td class="px-6 py-4 text-center text-sm font-semibold text-indigo-700">{{ number_format($dept['total_downloads'] ?? 0) }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($dept['degree_programs'] as $program)
                                            @if($program)
                                                <a href="{{ route('reports.individual', $program->id) }}" class="rounded-md border border-zinc-200 bg-zinc-50 px-2 py-1 text-xs font-medium text-zinc-700 transition-colors hover:border-zinc-300 hover:bg-zinc-100">
                                                    {{ $program->course_name }}
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-sm text-zinc-500">No department data found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div x-cloak x-show="activeTab === 'downloads'" x-transition class="p-6">
                @forelse($studentDownloads as $deptName => $programs)
                    <div class="mb-8 last:mb-0">
                        <h3 class="mb-3 text-sm font-bold uppercase tracking-wide text-zinc-700">{{ $deptName }}</h3>
                        @foreach($programs as $program)
                            <div class="mb-6 overflow-hidden rounded-lg border border-zinc-200 last:mb-0">
                                <div class="bg-zinc-50 px-4 py-3">
                                    <p class="text-sm font-semibold text-zinc-900">{{ $program['degree_program'] }}</p>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-zinc-200">
                                        <thead class="bg-white">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-zinc-500">User</th>
                                                <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wide text-zinc-500">ID Number</th>
                                                <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wide text-zinc-500">Downloads</th>
                                                <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-zinc-500">Last Activity</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-zinc-100">
                                            @foreach($program['students'] as $student)
                                                <tr class="hover:bg-zinc-50">
                                                    <td class="px-4 py-3 text-sm font-semibold text-zinc-900">{{ $student['name'] }}</td>
                                                    <td class="px-4 py-3 text-center font-mono text-sm text-zinc-600">{{ $student['id_number'] }}</td>
                                                    <td class="px-4 py-3 text-center text-sm font-bold text-indigo-700">{{ number_format($student['download_count']) }}</td>
                                                    <td class="px-4 py-3 text-sm text-zinc-600">
                                                        {{ $student['last_download'] ? \Carbon\Carbon::parse($student['last_download'])->format('M d, Y h:i A') : 'N/A' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @empty
                    <div class="py-10 text-center text-sm text-zinc-500">No download activity found.</div>
                @endforelse
            </div>
        </section>
    </main>

    <style>
        .summary-feed-grid {
            align-items: start;
        }

        .compact-report-card {
            overflow: hidden;
        }

        .compact-card-header {
            padding: 1rem 1.25rem;
        }

        .compact-activity-list,
        .compact-table-wrap {
            flex: 0 0 auto;
        }

        .compact-activity-row {
            padding: 0.75rem 1.25rem;
        }

        .compact-activity-content {
            display: grid;
            grid-template-columns: 2rem minmax(0, 1fr);
            gap: 0.75rem;
            align-items: start;
        }

        .compact-activity-icon {
            width: 2rem;
            height: 2rem;
            border-radius: 0.5rem;
        }

        .compact-activity-heading {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 0.75rem;
            align-items: baseline;
        }

        .compact-activity-heading p,
        .compact-activity-meta {
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .compact-activity-date {
            white-space: nowrap;
        }

        .compact-card-footer {
            padding: 0.75rem 1.25rem;
        }

        .compact-module-table {
            table-layout: fixed;
            width: 100%;
        }

        .compact-module-table th,
        .compact-module-table td {
            padding: 0.7rem 1rem;
            vertical-align: top;
        }

        .compact-module-title {
            display: -webkit-box;
            overflow: hidden;
            line-height: 1.25;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        .compact-module-meta,
        .compact-uploader {
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .compact-module-table .metric-cell {
            white-space: nowrap;
        }

        @media (max-width: 640px) {
            .compact-activity-heading {
                grid-template-columns: 1fr;
                gap: 0.15rem;
            }
        }

        @media print {
            body { display: none !important; }
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const departmentSelect = document.querySelector('select[name="department_id"]');
            const courseSelect = document.querySelector('select[name="course_id"]');
            const facultySelect = document.querySelector('select[name="faculty_id"]');

            function filterDropdowns() {
                if (!departmentSelect || !courseSelect || !facultySelect) return;

                const selectedDept = departmentSelect.value;
                [courseSelect, facultySelect].forEach((select) => {
                    Array.from(select.options).forEach((option) => {
                        if (option.value === '') {
                            option.hidden = false;
                            option.disabled = false;
                            return;
                        }

                        const shouldShow = !selectedDept || option.dataset.departmentId === selectedDept;
                        option.hidden = !shouldShow;
                        option.disabled = !shouldShow;

                        if (!shouldShow && option.selected) {
                            select.value = '';
                        }
                    });
                });
            }

            filterDropdowns();
            departmentSelect?.addEventListener('change', filterDropdowns);
        });

        function buildSummaryPrintUrl() {
            const url = new URL("{{ route('reports.print.summary') }}", window.location.origin);
            const params = new URLSearchParams(window.location.search);
            params.forEach((value, key) => url.searchParams.append(key, value));
            return url;
        }

        function exportToPDF(btn) {
            const url = buildSummaryPrintUrl();
            const originalText = btn.innerHTML;

            btn.innerHTML = `<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Generating PDF...`;
            btn.disabled = true;

            fetch(url.toString())
                .then((res) => res.text())
                .then((html) => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const reportEl = doc.querySelector('.report-container') || doc.body;
                    const styleBlock = doc.querySelector('style');

                    if (reportEl && styleBlock) {
                        reportEl.prepend(styleBlock);
                    }

                    const options = {
                        margin: [12, 15, 12, 15],
                        filename: 'Activity_Summary_Report_{{ now()->format("Ymd_His") }}.pdf',
                        image: { type: 'jpeg', quality: 0.98 },
                        html2canvas: { scale: 2, useCORS: true, logging: false },
                        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
                        pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }
                    };

                    return html2pdf().set(options).from(reportEl).save();
                })
                .catch((err) => {
                    console.error('PDF generation failed:', err);
                    window.open(url.toString(), '_blank');
                })
                .finally(() => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                });
        }

        function printReport() {
            const iframe = document.getElementById('print-preview-iframe');
            iframe.src = buildSummaryPrintUrl().toString();
            document.getElementById('trigger-print-modal').click();
        }
    </script>

    <x-my-modal id="print-modal" title="Report Print Preview" :showIcon="false" maxWidth="5xl">
        <div class="mt-4 overflow-hidden rounded-lg border border-zinc-200 bg-zinc-100/50 p-2" style="height: 75vh; min-height: 500px;">
            <iframe id="print-preview-iframe" class="h-full w-full rounded-lg border border-zinc-200 bg-white shadow-sm" src=""></iframe>
        </div>
        <x-slot name="footer">
            <x-my-secondary-button data-modal-close>Close</x-my-secondary-button>
            <button onclick="document.getElementById('print-preview-iframe').contentWindow.print()" class="ml-3 inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition-colors hover:bg-zinc-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Document
            </button>
        </x-slot>
    </x-my-modal>

    <button id="trigger-print-modal" data-modal-target="print-modal" class="hidden"></button>
</x-admin-layout>
