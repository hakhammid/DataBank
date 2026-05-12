<x-admin-layout :title="'Reports - General'">
    <main class="flex-1 max-h-full p-5 lg:mt-0 mt-20">
        <!-- HEADER -->
        <div class="flex flex-col items-start justify-between pb-6 space-y-4 lg:items-center lg:space-y-0 lg:flex-row">
            <div>
                <h1 class="text-2xl/8 font-semibold text-zinc-950 sm:text-xl/8">
                    General Reports
                </h1>
                <p class="mt-2 text-sm text-zinc-600">Comprehensive overview of modules, faculty uploads, and student downloads</p>
            </div>
            <div class="flex gap-3">
                <div class="relative" x-data="{ open: false }">
                    <x-my-secondary-button @click="open = !open" @click.away="open = false">
                        <svg class="w-5 h-5 text-zinc-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </x-my-secondary-button>

                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-zinc-200 z-50">
                        <div class="py-1">
                            <button onclick="exportToPDF()"
                                class="w-full text-left px-4 py-3 text-sm text-zinc-700 hover:bg-gray-100 flex items-center gap-3 transition-colors duration-150">
                                <svg class="w-5 h-5 text-zinc-900" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 1v5h5v10H6V3h7z" />
                                    <path d="M8 13h8v2H8zm0 3h8v2H8zm0-6h3v2H8z" />
                                </svg>
                                Export to PDF
                            </button>
                            <button onclick="printReport()"
                                class="w-full text-left px-4 py-3 text-sm text-zinc-700 hover:bg-gray-100 flex items-center gap-3 transition-colors duration-150">
                                <svg class="w-5 h-5 text-zinc-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Print Report
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-xl border border-zinc-200 shadow-sm mb-8 overflow-hidden">
            <div class="bg-zinc-50/80 border-b border-zinc-200 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <h2 class="text-sm font-semibold text-zinc-800">Filter & Analyze Reports</h2>
                </div>
                @if(array_filter($filters ?? []))
                    <a href="{{ route('reports.summary') }}" class="text-xs font-medium text-red-600 hover:text-red-700 flex items-center gap-1 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Clear Active Filters
                    </a>
                @endif
            </div>
            
            <div class="p-6">
                <form method="GET" action="{{ route('reports.summary') }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
                        <!-- Department -->
                        <div>
                            <label class="block text-xs font-semibold text-zinc-600 mb-2 tracking-wide uppercase">Department</label>
                            <div class="relative">
                                <select name="department_id" class="w-full appearance-none border border-zinc-300 rounded-lg text-sm py-2.5 pl-3 pr-10 bg-white hover:border-zinc-400 focus:ring-2 focus:ring-zinc-900/20 focus:border-zinc-900 transition-colors shadow-sm">
                                    <option value="">All Departments</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ ($filters['department_id'] ?? '') == $dept->id ? 'selected' : '' }}>{{ $dept->department_name }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-zinc-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>

                        <!-- Degree Program -->
                        <div>
                            <label class="block text-xs font-semibold text-zinc-600 mb-2 tracking-wide uppercase">Degree Program</label>
                            <div class="relative">
                                <select name="course_id" class="w-full appearance-none border border-zinc-300 rounded-lg text-sm py-2.5 pl-3 pr-10 bg-white hover:border-zinc-400 focus:ring-2 focus:ring-zinc-900/20 focus:border-zinc-900 transition-colors shadow-sm">
                                    <option value="">All Programs</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ ($filters['course_id'] ?? '') == $course->id ? 'selected' : '' }}>{{ $course->course_name }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-zinc-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>

                        <!-- Semester -->
                        <div>
                            <label class="block text-xs font-semibold text-zinc-600 mb-2 tracking-wide uppercase">Semester</label>
                            <div class="relative">
                                <select name="semester" class="w-full appearance-none border border-zinc-300 rounded-lg text-sm py-2.5 pl-3 pr-10 bg-white hover:border-zinc-400 focus:ring-2 focus:ring-zinc-900/20 focus:border-zinc-900 transition-colors shadow-sm">
                                    <option value="">All Semesters</option>
                                    <option value="1st" {{ ($filters['semester'] ?? '') == '1st' ? 'selected' : '' }}>1st Semester</option>
                                    <option value="2nd" {{ ($filters['semester'] ?? '') == '2nd' ? 'selected' : '' }}>2nd Semester</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-zinc-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>

                        <!-- Faculty -->
                        <div>
                            <label class="block text-xs font-semibold text-zinc-600 mb-2 tracking-wide uppercase">Faculty Member</label>
                            <div class="relative">
                                <select name="faculty_id" class="w-full appearance-none border border-zinc-300 rounded-lg text-sm py-2.5 pl-3 pr-10 bg-white hover:border-zinc-400 focus:ring-2 focus:ring-zinc-900/20 focus:border-zinc-900 transition-colors shadow-sm">
                                    <option value="">All Faculty</option>
                                    @foreach($faculties as $faculty)
                                        <option value="{{ $faculty->id }}" {{ ($filters['faculty_id'] ?? '') == $faculty->id ? 'selected' : '' }}>{{ $faculty->name }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-zinc-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-between pt-4 border-t border-zinc-100">
                        <div class="text-sm text-zinc-500">
                            @if(array_filter($filters ?? []))
                                Showing filtered results based on your selection.
                            @else
                                Showing all report data. Apply filters to narrow down.
                            @endif
                        </div>
                        <div class="flex items-center gap-3">
                            @if(!empty($filters['course_id']))
                            <a href="{{ route('reports.individual', $filters['course_id']) }}" class="px-5 py-2.5 bg-blue-50 text-blue-700 font-medium text-sm rounded-lg border border-blue-200 hover:bg-blue-100 hover:border-blue-300 transition-all shadow-sm flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                View Detailed Course Report
                            </a>
                            @endif
                            <button type="submit" class="px-6 py-2.5 bg-zinc-900 text-white font-medium text-sm rounded-lg hover:bg-zinc-800 transition-colors shadow-sm flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                Apply Filters
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-8" id="reportContent">
            <div class="overflow-hidden rounded-xl border border-zinc-300 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-zinc-600">Total Modules</p>
                        <p class="text-3xl font-bold text-primary mt-2">{{ number_format($totalModules) }}</p>
                    </div>
                    <div class="p-3 rounded-lg bg-blue-50">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="overflow-hidden rounded-xl border border-zinc-300 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-zinc-600">Faculty Contributors</p>
                        <p class="text-3xl font-bold text-green-600 mt-2">{{ number_format($totalFaculty) }}</p>
                    </div>
                    <div class="p-3 rounded-lg bg-green-50">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="overflow-hidden rounded-xl border border-zinc-300 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-zinc-600">Total Downloads</p>
                        <p class="text-3xl font-bold text-indigo-600 mt-2">{{ number_format($totalDownloads) }}</p>
                    </div>
                    <div class="p-3 rounded-lg bg-indigo-50">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="overflow-hidden rounded-xl border border-zinc-300 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-zinc-600">Total Students</p>
                        <p class="text-3xl font-bold text-orange-600 mt-2">{{ number_format($totalStudents) }}</p>
                    </div>
                    <div class="p-3 rounded-lg bg-orange-50">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Faculty Upload Summary -->
        <div class="bg-white rounded-xl border border-zinc-200 shadow-sm mb-8 overflow-hidden">
            <div class="p-6 border-b border-zinc-200 bg-white flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-zinc-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Faculty Upload Summary
                    </h2>
                    <p class="mt-1 text-sm text-zinc-500">Modules uploaded by each faculty member per course code</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200" id="facultyTable">
                    <thead class="bg-zinc-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Faculty Name</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Department</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Course Codes Handled</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-zinc-500 uppercase tracking-wider">Total Modules</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-zinc-200">
                        @forelse($facultyUploadSummary as $faculty)
                        <tr class="hover:bg-zinc-50/50 even:bg-zinc-50/30 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-zinc-900">{{ $faculty['faculty_name'] }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-600">
                                {{ $faculty['department'] }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($faculty['course_breakdown'] as $course)
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-bold bg-indigo-50 text-indigo-700 rounded-md border border-indigo-100 shadow-sm">
                                        {{ $course['course_code'] }} 
                                        <span class="ml-1.5 px-1.5 py-0.5 rounded bg-white text-indigo-600 text-[10px] font-black border border-indigo-100">{{ $course['count'] }}</span>
                                    </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <span class="px-4 py-1.5 inline-flex text-sm leading-5 font-black rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200 shadow-sm">
                                    {{ $faculty['total_modules'] }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-zinc-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                    <p class="text-zinc-500 font-bold">No faculty upload data found</p>
                                    <p class="text-sm text-zinc-400 mt-1">Try adjusting your filters to see results</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Department Breakdown -->
        <div class="bg-white rounded-xl border border-zinc-200 shadow-sm mb-8 overflow-hidden">
            <div class="p-6 border-b border-zinc-200 bg-zinc-50/30 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-zinc-800 flex items-center gap-2">
                        <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Department Breakdown
                    </h2>
                    <p class="mt-1 text-sm text-zinc-500 font-medium">Distribution of modules across academic departments</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200">
                    <thead class="bg-zinc-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Department Name</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-zinc-500 uppercase tracking-wider">Total Modules</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Degree Programs</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-zinc-200">
                        @forelse($departmentBreakdown as $dept)
                        <tr class="hover:bg-zinc-50/50 even:bg-zinc-50/30 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-zinc-900">{{ $dept['department_name'] }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <span class="px-4 py-1.5 inline-flex text-sm leading-5 font-black rounded-full bg-purple-100 text-purple-800 border border-purple-200 shadow-sm">
                                    {{ $dept['total_modules'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($dept['degree_programs'] as $program)
                                    @if($program)
                                        <a href="{{ route('reports.individual', $program->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-bold bg-zinc-100 text-zinc-700 hover:bg-zinc-900 hover:text-white rounded-lg border border-zinc-200 shadow-sm transition-all" title="View individual report for {{ $program->course_name }}">
                                            {{ $program->course_name }}
                                            <svg class="w-3.5 h-3.5 ml-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                        </a>
                                    @endif
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-sm text-zinc-500 font-medium">
                                No department data found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Student Download Report -->
        <div class="bg-white rounded-xl border border-zinc-200 shadow-sm mb-8 overflow-hidden">
            <div class="p-6 border-b border-zinc-200 bg-zinc-50/30 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-zinc-800 flex items-center gap-2">
                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Student Download Report
                    </h2>
                    <p class="mt-1 text-sm text-zinc-500 font-medium">Usage activity tracking for students</p>
                </div>
            </div>
            <div class="p-6">
                @forelse($studentDownloads as $deptName => $programs)
                <div class="mb-10 last:mb-0 bg-zinc-50/50 rounded-2xl p-6 border border-zinc-200 shadow-sm">
                    <h3 class="text-base font-black text-zinc-900 mb-5 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center border border-zinc-200 shadow-sm">
                            <svg class="w-5 h-5 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        {{ $deptName }}
                    </h3>
                    @foreach($programs as $program)
                    <div class="ml-2 mb-8 last:mb-0">
                        <h4 class="text-sm font-bold text-zinc-800 mb-4 flex items-center gap-2.5">
                            <span class="w-2 h-2 rounded-full bg-blue-600 shadow-sm"></span>
                            <span class="px-3 py-1 bg-white text-blue-700 rounded-lg border border-blue-200 shadow-sm uppercase tracking-wide text-xs">{{ $program['degree_program'] }}</span>
                        </h4>
                        <div class="overflow-x-auto rounded-xl border border-zinc-200 shadow-md">
                            <table class="min-w-full divide-y divide-zinc-200 bg-white">
                                <thead class="bg-zinc-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Student Name</th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-zinc-500 uppercase tracking-wider">ID Number</th>
                                        <th class="px-6 py-4 text-right text-xs font-bold text-zinc-500 uppercase tracking-wider">Total Downloads</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Last Download Activity</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-100">
                                    @foreach($program['students'] as $student)
                                    <tr class="hover:bg-zinc-50/50 even:bg-zinc-50/20 transition-colors">
                                        <td class="px-6 py-4 text-sm font-bold text-zinc-900">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-zinc-200 flex items-center justify-center text-xs font-black text-zinc-600 border-2 border-white shadow-sm">
                                                    {{ substr($student['name'], 0, 1) }}
                                                </div>
                                                {{ $student['name'] }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-zinc-600 font-mono text-center font-medium">{{ $student['id_number'] }}</td>
                                        <td class="px-6 py-4 text-sm text-right">
                                            <span class="px-3 py-1.5 rounded-lg bg-orange-50 text-orange-700 font-black border border-orange-100 shadow-sm inline-flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                                {{ $student['download_count'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-zinc-500 font-semibold">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                {{ $student['last_download'] ? \Carbon\Carbon::parse($student['last_download'])->format('M d, Y h:i A') : 'N/A' }}
                                            </div>
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
                <div class="text-center py-12">
                    <div class="flex flex-col items-center justify-center">
                        <svg class="w-12 h-12 text-zinc-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        <p class="text-zinc-500 font-medium">No student download data found</p>
                        <p class="text-sm text-zinc-400 mt-1">Try adjusting your filters</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>

    </main>

    {{-- Print Styles --}}
    <style>
        @media print {
            /* Hide navigation, sidebar, header bar, filter section */
            nav, aside, header, .no-print,
            [data-sidebar], [data-topbar],
            .lg\:pl-64, .fixed {
                display: none !important;
            }

            body {
                background: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            main {
                margin: 0 !important;
                padding: 15px !important;
                max-width: 100% !important;
            }

            /* Ensure cards and badges print with colors */
            .rounded-xl, .rounded-lg, .rounded-full {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            @page {
                size: A4 landscape;
                margin: 10mm;
            }
        }
    </style>

    {{-- html2pdf Library --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function exportToPDF() {
            const url = new URL("{{ route('reports.print.summary') }}", window.location.origin);
            const params = new URLSearchParams(window.location.search);
            params.forEach((value, key) => url.searchParams.append(key, value));
            window.open(url.toString(), '_blank');
        }

        function printReport() {
            const url = new URL("{{ route('reports.print.summary') }}", window.location.origin);
            const params = new URLSearchParams(window.location.search);
            params.forEach((value, key) => url.searchParams.append(key, value));
            window.open(url.toString(), '_blank');
        }
    </script>
</x-admin-layout>

