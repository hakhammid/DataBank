<x-admin-layout :title="'Course Activity Report - ' . $course->course_name">
    @php
        $modulesByCourseCode = $allModules->groupBy('course_code')->sortKeys();
    @endphp

    <main class="flex-1 max-h-full p-5 lg:mt-0 mt-20">
        <div class="flex flex-col gap-4 pb-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-start gap-3">
                <a href="{{ route('reports.summary') }}" class="mt-1 rounded-lg p-1.5 text-zinc-600 transition-colors hover:bg-zinc-100 hover:text-zinc-900">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500">Individual course report</p>
                    <h1 class="mt-1 text-2xl/8 font-semibold text-zinc-950 sm:text-xl/8">{{ $course->course_name }}</h1>
                    <p class="mt-2 max-w-3xl text-sm text-zinc-600">
                        Course-specific monitoring of module uploads, downloads, views, contributors, and downloader activity.
                    </p>
                </div>
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
                    <svg class="h-5 w-5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <h2 class="text-sm font-semibold text-zinc-800">Course Scope</h2>
                </div>
                @if($semester)
                    <a href="{{ route('reports.individual', $course->id) }}" class="inline-flex items-center gap-1 text-xs font-medium text-red-600 transition-colors hover:text-red-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Clear semester
                    </a>
                @endif
            </div>

            <div class="p-6">
                <form method="GET" action="{{ route('reports.individual', $course->id) }}" class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                    <div class="w-full md:max-w-xs">
                        <label class="mb-2 block text-xs font-semibold uppercase tracking-wide text-zinc-600">Semester</label>
                        <select name="semester" class="w-full rounded-lg border border-zinc-300 bg-white px-3 py-2.5 text-sm shadow-sm transition-colors hover:border-zinc-400 focus:border-zinc-900 focus:ring-2 focus:ring-zinc-900/20">
                            <option value="">All Semesters</option>
                            <option value="1st" {{ $semester == '1st' ? 'selected' : '' }}>1st Semester</option>
                            <option value="2nd" {{ $semester == '2nd' ? 'selected' : '' }}>2nd Semester</option>
                        </select>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <span class="text-sm text-zinc-500">
                            {{ $semester ? 'Filtered to ' . $semester . ' semester.' : 'Showing all semester activity.' }}
                        </span>
                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition-colors hover:bg-zinc-800">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Apply Filter
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <section class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-6">
            <div class="rounded-lg border border-zinc-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500">Modules</p>
                <p class="mt-2 text-3xl font-bold text-blue-700">{{ number_format($stats['total_modules']) }}</p>
                <p class="mt-1 text-xs text-zinc-500">Files uploaded</p>
            </div>
            <div class="rounded-lg border border-zinc-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500">Major</p>
                <p class="mt-2 text-3xl font-bold text-emerald-700">{{ number_format($stats['major_modules']) }}</p>
                <p class="mt-1 text-xs text-zinc-500">Major subjects</p>
            </div>
            <div class="rounded-lg border border-zinc-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500">Minor</p>
                <p class="mt-2 text-3xl font-bold text-violet-700">{{ number_format($stats['minor_modules']) }}</p>
                <p class="mt-1 text-xs text-zinc-500">Minor subjects</p>
            </div>
            <div class="rounded-lg border border-zinc-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500">Views</p>
                <p class="mt-2 text-3xl font-bold text-amber-700">{{ number_format($stats['total_views']) }}</p>
                <p class="mt-1 text-xs text-zinc-500">Module views</p>
            </div>
            <div class="rounded-lg border border-zinc-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500">Downloads</p>
                <p class="mt-2 text-3xl font-bold text-indigo-700">{{ number_format($stats['total_downloads']) }}</p>
                <p class="mt-1 text-xs text-zinc-500">Download records</p>
            </div>
            <div class="rounded-lg border border-zinc-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500">Contributors</p>
                <p class="mt-2 text-3xl font-bold text-teal-700">{{ number_format($stats['uploaders']) }}</p>
                <p class="mt-1 text-xs text-zinc-500">Uploaders</p>
            </div>
        </section>

        <div class="mb-8 grid grid-cols-1 gap-6 xl:grid-cols-3">
            <section class="rounded-lg border border-zinc-200 bg-white shadow-sm xl:col-span-2">
                <div class="border-b border-zinc-200 px-6 py-4">
                    <h2 class="text-base font-bold text-zinc-900">Course Code Breakdown</h2>
                    <p class="mt-1 text-sm text-zinc-500">Coverage, engagement, and latest upload per course code.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-200">
                        <thead class="bg-zinc-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wide text-zinc-500">Course Code</th>
                                <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wide text-zinc-500">Modules</th>
                                <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wide text-zinc-500">Major</th>
                                <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wide text-zinc-500">Minor</th>
                                <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wide text-zinc-500">Views</th>
                                <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wide text-zinc-500">Downloads</th>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wide text-zinc-500">Latest Upload</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100">
                            @forelse($courseCodeBreakdown as $row)
                                <tr class="hover:bg-zinc-50">
                                    <td class="px-6 py-4 text-sm font-semibold text-zinc-900">{{ $row['course_code'] }}</td>
                                    <td class="px-6 py-4 text-center text-sm font-bold text-zinc-900">{{ number_format($row['modules']) }}</td>
                                    <td class="px-6 py-4 text-center text-sm text-emerald-700">{{ number_format($row['major_modules']) }}</td>
                                    <td class="px-6 py-4 text-center text-sm text-violet-700">{{ number_format($row['minor_modules']) }}</td>
                                    <td class="px-6 py-4 text-center text-sm text-amber-700">{{ number_format($row['views']) }}</td>
                                    <td class="px-6 py-4 text-center text-sm text-indigo-700">{{ number_format($row['downloads']) }}</td>
                                    <td class="px-6 py-4 text-sm text-zinc-600">
                                        {{ $row['latest_upload'] ? \Carbon\Carbon::parse($row['latest_upload'])->format('M d, Y') : 'N/A' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-sm text-zinc-500">No course-code data available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="rounded-lg border border-zinc-200 bg-white shadow-sm">
                <div class="border-b border-zinc-200 px-6 py-4">
                    <h2 class="text-base font-bold text-zinc-900">Downloads by Role</h2>
                    <p class="mt-1 text-sm text-zinc-500">Downloader activity inside this course.</p>
                </div>
                <div class="divide-y divide-zinc-100">
                    @forelse($downloadActivityByRole as $roleActivity)
                        <div class="px-6 py-4">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-zinc-900">{{ ucfirst($roleActivity['role']) }}</p>
                                    <p class="text-xs text-zinc-500">{{ number_format($roleActivity['unique_users']) }} active {{ Str::plural('user', $roleActivity['unique_users']) }}</p>
                                </div>
                                <span class="text-xl font-bold text-indigo-700">{{ number_format($roleActivity['downloads']) }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-10 text-center text-sm text-zinc-500">No download activity available.</div>
                    @endforelse
                </div>
            </section>
        </div>

        <div class="mb-8 grid grid-cols-1 gap-6 xl:grid-cols-2">
            <section
                x-data="{ page: 1, perPage: 5, total: {{ count($activityFeed ?? []) }}, get pages() { return Math.max(1, Math.ceil(this.total / this.perPage)); } }"
                class="flex flex-col rounded-lg border border-zinc-200 bg-white shadow-sm"
            >
                <div class="border-b border-zinc-200 px-6 py-4">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <h2 class="text-base font-bold text-zinc-900">Recent Course Activity</h2>
                            <p class="mt-1 text-sm text-zinc-500">Upload and download events for this course.</p>
                        </div>
                        @if(count($activityFeed ?? []) > 0)
                            <span class="shrink-0 rounded-md border border-zinc-200 bg-zinc-50 px-2.5 py-1 text-xs font-semibold text-zinc-600">
                                {{ count($activityFeed) }} {{ Str::plural('record', count($activityFeed)) }}
                            </span>
                        @endif
                    </div>
                </div>
                @if(count($activityFeed ?? []) > 0)
                <div class="flex-1 divide-y divide-zinc-100">
                    @foreach($activityFeed as $index => $activity)
                        @php
                            $isUpload = $activity['type'] === 'upload';
                            $activityDate = \Carbon\Carbon::parse($activity['occurred_at']);
                        @endphp
                        <div x-show="{{ $index }} >= (page - 1) * perPage && {{ $index }} < page * perPage" x-cloak class="px-6 py-4">
                            <div class="flex gap-3">
                                <div class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-lg {{ $isUpload ? 'bg-blue-50 text-blue-700' : 'bg-indigo-50 text-indigo-700' }}">
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
                                    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                                        <p class="text-sm font-semibold text-zinc-900">{{ $activity['label'] }}</p>
                                        <p class="text-xs text-zinc-500">{{ $activityDate->format('M d, Y h:i A') }}</p>
                                    </div>
                                    <p class="mt-1 truncate text-sm text-zinc-700">{{ $activity['module_title'] }}</p>
                                    <p class="mt-1 text-xs text-zinc-500">
                                        {{ $activity['actor'] }} ({{ ucfirst($activity['role']) }}) - {{ $activity['course_code'] }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex flex-col gap-3 border-t border-zinc-200 bg-zinc-50/70 px-6 py-3 sm:flex-row sm:items-center sm:justify-between">
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
                    <div class="px-6 py-10 text-center text-sm text-zinc-500">No recent activity found.</div>
                @endif
            </section>

            <section
                x-data="{ page: 1, perPage: 5, total: {{ count($topModules ?? []) }}, get pages() { return Math.max(1, Math.ceil(this.total / this.perPage)); } }"
                class="flex flex-col rounded-lg border border-zinc-200 bg-white shadow-sm"
            >
                <div class="border-b border-zinc-200 px-6 py-4">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <h2 class="text-base font-bold text-zinc-900">Top Course Modules</h2>
                            <p class="mt-1 text-sm text-zinc-500">Ranked by combined views and downloads.</p>
                        </div>
                        @if(count($topModules ?? []) > 0)
                            <span class="shrink-0 rounded-md border border-zinc-200 bg-zinc-50 px-2.5 py-1 text-xs font-semibold text-zinc-600">
                                {{ count($topModules) }} {{ Str::plural('module', count($topModules)) }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="flex-1 overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-200">
                        <thead class="bg-zinc-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wide text-zinc-500">Module</th>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wide text-zinc-500">Uploader</th>
                                <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wide text-zinc-500">Views</th>
                                <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wide text-zinc-500">Downloads</th>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wide text-zinc-500">Uploaded</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100">
                            @forelse($topModules as $index => $module)
                                <tr x-show="{{ $index }} >= (page - 1) * perPage && {{ $index }} < page * perPage" x-cloak class="hover:bg-zinc-50">
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-semibold text-zinc-900">{{ $module->title }}</p>
                                        <p class="text-xs text-zinc-500">{{ $module->course_code }} - {{ $module->isMajor ? 'Major' : 'Minor' }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-zinc-700">{{ $module->user->name ?? 'Unknown' }}</td>
                                    <td class="px-6 py-4 text-center text-sm font-semibold text-amber-700">{{ number_format($module->number_of_views ?? 0) }}</td>
                                    <td class="px-6 py-4 text-center text-sm font-semibold text-indigo-700">{{ number_format($module->module_downloads_count ?? 0) }}</td>
                                    <td class="px-6 py-4 text-sm text-zinc-500">{{ optional($module->created_at)->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-sm text-zinc-500">No module data available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if(count($topModules ?? []) > 0)
                    <div class="flex flex-col gap-3 border-t border-zinc-200 bg-zinc-50/70 px-6 py-3 sm:flex-row sm:items-center sm:justify-between">
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

        <div class="mb-8 grid grid-cols-1 gap-6 xl:grid-cols-2">
            <section class="rounded-lg border border-zinc-200 bg-white shadow-sm">
                <div class="border-b border-zinc-200 px-6 py-4">
                    <h2 class="text-base font-bold text-zinc-900">Faculty Contributors</h2>
                    <p class="mt-1 text-sm text-zinc-500">Faculty members who uploaded modules for this course.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-200">
                        <thead class="bg-zinc-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wide text-zinc-500">Faculty</th>
                                <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wide text-zinc-500">Modules Uploaded</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100">
                            @forelse($uploaders as $uploader)
                                <tr class="hover:bg-zinc-50">
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-semibold text-zinc-900">{{ $uploader->name }}</p>
                                        <p class="text-xs text-zinc-500">{{ ucfirst($uploader->usertype) }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm font-bold text-zinc-900">{{ number_format($uploader->modules_count) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-10 text-center text-sm text-zinc-500">No contributors found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="rounded-lg border border-zinc-200 bg-white shadow-sm">
                <div class="border-b border-zinc-200 px-6 py-4">
                    <h2 class="text-base font-bold text-zinc-900">Top Downloaders</h2>
                    <p class="mt-1 text-sm text-zinc-500">Users with the most downloads from this course.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-zinc-200">
                        <thead class="bg-zinc-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wide text-zinc-500">User</th>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wide text-zinc-500">Role</th>
                                <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wide text-zinc-500">Downloads</th>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wide text-zinc-500">Last Activity</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100">
                            @forelse($downloaders as $downloader)
                                <tr class="hover:bg-zinc-50">
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-semibold text-zinc-900">{{ $downloader['name'] }}</p>
                                        <p class="font-mono text-xs text-zinc-500">{{ $downloader['id_number'] }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-zinc-700">{{ ucfirst($downloader['role']) }}</td>
                                    <td class="px-6 py-4 text-center text-sm font-bold text-indigo-700">{{ number_format($downloader['download_count']) }}</td>
                                    <td class="px-6 py-4 text-sm text-zinc-600">
                                        {{ $downloader['last_download'] ? \Carbon\Carbon::parse($downloader['last_download'])->format('M d, Y h:i A') : 'N/A' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-sm text-zinc-500">No downloader data found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <section class="rounded-lg border border-zinc-200 bg-white shadow-sm">
            <div class="border-b border-zinc-200 px-6 py-4">
                <h2 class="text-base font-bold text-zinc-900">Module Inventory</h2>
                <p class="mt-1 text-sm text-zinc-500">Complete module list grouped by course code.</p>
            </div>

            @forelse($modulesByCourseCode as $courseCode => $groupedModules)
                <div class="border-b border-zinc-200 last:border-b-0">
                    <div class="flex flex-col gap-2 bg-zinc-50 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-sm font-bold text-zinc-900">{{ $courseCode }}</h3>
                            <p class="text-xs text-zinc-500">{{ $groupedModules->count() }} {{ Str::plural('module', $groupedModules->count()) }}</p>
                        </div>
                        <span class="w-fit rounded-md border px-2.5 py-1 text-xs font-semibold {{ $groupedModules->first()->isMajor ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-violet-200 bg-violet-50 text-violet-700' }}">
                            {{ $groupedModules->first()->isMajor ? 'Major' : 'Minor' }}
                        </span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-zinc-200">
                            <thead class="bg-white">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wide text-zinc-500">Module Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wide text-zinc-500">Department</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wide text-zinc-500">Uploaded By</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wide text-zinc-500">Views</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wide text-zinc-500">Downloads</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wide text-zinc-500">Date Added</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100">
                                @foreach($groupedModules as $module)
                                    <tr class="hover:bg-zinc-50">
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-semibold text-zinc-900">{{ $module->title }}</p>
                                            <p class="text-xs text-zinc-500">{{ $module->semester ? $module->semester . ' semester' : 'Semester not set' }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-zinc-600">{{ $module->department->department_name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm text-zinc-700">{{ $module->user->name ?? 'Unknown' }}</td>
                                        <td class="px-6 py-4 text-center text-sm font-semibold text-amber-700">{{ number_format($module->number_of_views ?? 0) }}</td>
                                        <td class="px-6 py-4 text-center text-sm font-semibold text-indigo-700">{{ number_format($module->module_downloads_count ?? 0) }}</td>
                                        <td class="px-6 py-4 text-sm text-zinc-500">{{ optional($module->created_at)->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center text-sm text-zinc-500">No modules found for this course.</div>
            @endforelse
        </section>
    </main>

    <style>
        @media print {
            body { display: none !important; }
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function buildIndividualPrintUrl() {
            const url = new URL("{{ route('reports.print.individual', $course->id) }}", window.location.origin);
            const params = new URLSearchParams(window.location.search);
            params.forEach((value, key) => url.searchParams.append(key, value));
            return url;
        }

        function exportToPDF(btn) {
            const url = buildIndividualPrintUrl();
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
                        filename: '{{ Str::slug($course->course_name) }}_Activity_Report_{{ now()->format("Ymd_His") }}.pdf',
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
            window.open(buildIndividualPrintUrl().toString(), '_blank');
        }
    </script>
</x-admin-layout>
