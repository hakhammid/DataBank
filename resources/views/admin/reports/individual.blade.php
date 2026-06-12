<x-admin-layout :title="'Course Report - ' . $course->course_name">
    <main class="flex-1 max-h-full p-5 lg:mt-0 mt-20">
        <!-- HEADER -->
        <div class="flex flex-col items-start justify-between pb-6 space-y-4 lg:items-center lg:space-y-0 lg:flex-row">
            <div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('reports.summary') }}" class="text-zinc-600 hover:text-zinc-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl/8 font-semibold text-zinc-950 sm:text-xl/8">
                            {{ $course->course_name }} Report
                        </h1>
                        <p class="mt-1 text-sm text-zinc-600">Detailed module information and statistics</p>
                    </div>
                </div>
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
                                class="w-full text-left px-4 py-3 text-sm text-zinc-700 hover:bg-zinc-100 flex items-center gap-3 transition-colors duration-150">
                                <svg class="w-5 h-5 text-zinc-900" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 1v5h5v10H6V3h7z" />
                                    <path d="M8 13h8v2H8zm0 3h8v2H8zm0-6h3v2H8z" />
                                </svg>
                                Export to PDF
                            </button>
                            <button onclick="printReport()"
                                class="w-full text-left px-4 py-3 text-sm text-zinc-700 hover:bg-zinc-100 flex items-center gap-3 transition-colors duration-150">
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
                    <h2 class="text-sm font-semibold text-zinc-800">Filter Analysis</h2>
                </div>
                @if($semester)
                    <a href="{{ route('reports.individual', $course->id) }}" class="text-xs font-medium text-red-600 hover:text-red-700 flex items-center gap-1 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Clear Active Filter
                    </a>
                @endif
            </div>
            
            <div class="p-6">
                <form method="GET" action="{{ route('reports.individual', $course->id) }}">
                    <div class="flex flex-wrap items-end gap-4">
                        <!-- Semester -->
                        <div class="w-full md:w-64">
                            <label class="block text-xs font-semibold text-zinc-600 mb-2 tracking-wide uppercase">Semester</label>
                            <div class="relative">
                                <select name="semester" class="w-full appearance-none border border-zinc-300 rounded-lg text-sm py-2.5 pl-3 pr-10 bg-white hover:border-zinc-400 focus:ring-2 focus:ring-zinc-900/20 focus:border-zinc-900 transition-colors shadow-sm">
                                    <option value="">All Semesters</option>
                                    <option value="1st" {{ $semester == '1st' ? 'selected' : '' }}>1st Semester</option>
                                    <option value="2nd" {{ $semester == '2nd' ? 'selected' : '' }}>2nd Semester</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-zinc-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="submit" class="px-6 py-2.5 bg-zinc-900 text-white font-medium text-sm rounded-lg hover:bg-zinc-800 transition-colors shadow-sm flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                Apply Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-zinc-600">Total Modules</p>
                        <p class="text-3xl font-bold text-primary mt-2">{{ $stats['total_modules'] }}</p>
                    </div>
                    <div class="p-3 rounded-lg bg-blue-50">
                        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-zinc-600">Major Subjects</p>
                        <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['major_modules'] }}</p>
                    </div>
                    <div class="p-3 rounded-lg bg-green-50">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-zinc-600">Minor Subjects</p>
                        <p class="text-3xl font-bold text-purple-600 mt-2">{{ $stats['minor_modules'] }}</p>
                    </div>
                    <div class="p-3 rounded-lg bg-purple-50">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-zinc-600">Total Views</p>
                        <p class="text-3xl font-bold text-orange-600 mt-2">{{ number_format($stats['total_views']) }}</p>
                    </div>
                    <div class="p-3 rounded-lg bg-orange-50">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-zinc-600">Total Downloads</p>
                        <p class="text-3xl font-bold text-indigo-600 mt-2">{{ number_format($stats['total_downloads']) }}</p>
                    </div>
                    <div class="p-3 rounded-lg bg-indigo-50">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-zinc-600">Contributors</p>
                        <p class="text-3xl font-bold text-teal-600 mt-2">{{ $stats['uploaders'] }}</p>
                    </div>
                    <div class="p-3 rounded-lg bg-teal-50">
                        <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Uploaders List -->
        @if($uploaders->isNotEmpty())
        <div class="mt-8 bg-white rounded-xl border border-zinc-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-zinc-200 bg-white">
                <h2 class="text-lg font-bold text-zinc-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Top Contributors
                </h2>
                <p class="mt-1 text-sm text-zinc-500">Faculty members who have uploaded modules for this course</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($uploaders as $uploader)
                    <div class="flex items-center gap-4 p-4 rounded-xl bg-zinc-50 border border-zinc-100 hover:bg-zinc-50/80 transition-colors shadow-sm">
                        <div class="w-12 h-12 rounded-full bg-zinc-200 overflow-hidden flex-shrink-0 border-2 border-white shadow-sm">
                            <img src="{{ $uploader->profile_picture ? asset('images/' . $uploader->profile_picture) : asset('images/default_profile.png') }}"
                                alt="{{ $uploader->name }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-zinc-900 truncate">{{ $uploader->name }}</p>
                            <p class="text-xs font-medium text-zinc-500 flex items-center gap-1.5 mt-0.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span>
                                {{ $uploader->modules_count }} {{ Str::plural('module', $uploader->modules_count) }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        @php
            $modulesByCourseCode = $allModules->groupBy('course_code')->sortBy(function ($items, $key) {
                return $key;
            });
        @endphp

        <!-- Modules by Course Code -->
        <div class="mt-8 mb-4">
            <h2 class="text-lg font-bold text-zinc-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                Modules by Course Code
            </h2>
            <p class="mt-1 text-sm text-zinc-500">Select a course code to view its uploaded modules.</p>
        </div>

        <div x-data="{ openModalFor: null }">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @forelse($modulesByCourseCode as $courseCode => $groupedModules)
                    @php $modalId = 'modal-' . md5($courseCode); @endphp
                    <div @click="openModalFor = '{{ $modalId }}'" class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm p-5 cursor-pointer hover:bg-blue-50 hover:border-blue-200 transition-colors group">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-lg font-bold text-zinc-900 group-hover:text-blue-700 transition-colors">{{ $courseCode }}</h3>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $groupedModules->first()->isMajor ? 'bg-emerald-100 text-emerald-800' : 'bg-purple-100 text-purple-800' }}">
                                {{ $groupedModules->first()->isMajor ? 'Major' : 'Minor' }}
                            </span>
                        </div>
                        <p class="text-sm text-zinc-500 font-medium">{{ $groupedModules->count() }} {{ Str::plural('module', $groupedModules->count()) }}</p>
                    </div>

                    <!-- Modal for {{ $courseCode }} -->
                    <div x-cloak x-show="openModalFor === '{{ $modalId }}'" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
                        <div x-show="openModalFor === '{{ $modalId }}'"
                             x-transition:enter="ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="ease-in duration-200"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="fixed inset-0 bg-zinc-900/50 backdrop-blur-sm transition-opacity" 
                             @click="openModalFor = null"></div>

                        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                            <div x-show="openModalFor === '{{ $modalId }}'"
                                 x-transition:enter="ease-out duration-300"
                                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                 x-transition:leave="ease-in duration-200"
                                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                 class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 w-full max-w-6xl">
                                
                                <!-- Modal Header -->
                                <div class="bg-zinc-50 px-6 py-4 border-b border-zinc-200 flex justify-between items-center">
                                    <h3 class="text-lg font-bold text-zinc-900 flex items-center gap-2" id="modal-title">
                                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        Modules for {{ $courseCode }}
                                    </h3>
                                    <button @click="openModalFor = null" class="text-zinc-400 hover:text-zinc-600 transition-colors rounded-lg p-1 hover:bg-zinc-200">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Modal Body (Table) -->
                                <div class="bg-white max-h-[75vh] overflow-y-auto">
                                    <table class="min-w-full divide-y divide-zinc-200">
                                        <thead class="bg-zinc-50/50 sticky top-0 z-10">
                                            <tr>
                                                <th class="px-6 py-4 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Module Title</th>
                                                <th class="px-6 py-4 text-center text-xs font-bold text-zinc-500 uppercase tracking-wider">Type</th>
                                                <th class="px-6 py-4 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Department</th>
                                                <th class="px-6 py-4 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Uploaded By</th>
                                                <th class="px-6 py-4 text-center text-xs font-bold text-zinc-500 uppercase tracking-wider">Views</th>
                                                <th class="px-6 py-4 text-center text-xs font-bold text-zinc-500 uppercase tracking-wider">Downloads</th>
                                                <th class="px-6 py-4 text-left text-xs font-bold text-zinc-500 uppercase tracking-wider">Date Added</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-zinc-100">
                                            @foreach($groupedModules as $module)
                                            <tr class="hover:bg-zinc-50/50 transition-colors">
                                                <td class="px-6 py-4">
                                                    <div class="text-sm font-semibold text-zinc-900">{{ $module->title }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex justify-center">
                                                        @if($module->isMajor)
                                                        <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md bg-emerald-50 text-emerald-700 border border-emerald-100 shadow-sm">Major</span>
                                                        @else
                                                        <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md bg-purple-50 text-purple-700 border border-purple-100 shadow-sm">Minor</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-600">
                                                    {{ $module->department->department_name ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center gap-2">
                                                        <div class="w-8 h-8 rounded-full bg-zinc-200 overflow-hidden flex-shrink-0 border border-zinc-300 shadow-sm">
                                                            <img src="{{ $module->user->profile_picture ? asset('images/' . $module->user->profile_picture) : asset('images/default_profile.png') }}"
                                                                alt="{{ $module->user->name }}" class="w-full h-full object-cover">
                                                        </div>
                                                        <div>
                                                            <div class="text-sm font-semibold text-zinc-900">{{ $module->user->name }}</div>
                                                            <div class="text-[10px] font-medium text-zinc-500 uppercase tracking-wider">{{ $module->user->usertype }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <div class="flex justify-center">
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-zinc-100 text-zinc-700 font-bold border border-zinc-200 shadow-sm">
                                                            <svg class="w-3.5 h-3.5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                            {{ number_format($module->number_of_views) }}
                                                        </span>
                                                    </div>
                                                </td>
                    
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                    <div class="flex justify-center">
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-indigo-50 text-indigo-700 font-bold border border-indigo-100 shadow-sm">
                                                            <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                                            {{ number_format($module->module_downloads_count) }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 font-medium">
                                                    <div class="flex items-center gap-1.5">
                                                        <svg class="w-3.5 h-3.5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 00-2 2z" /></svg>
                                                        {{ $module->created_at->format('M d, Y') }}
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full p-8 text-center bg-white rounded-xl border border-zinc-200 shadow-sm">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-12 h-12 text-zinc-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                            <p class="text-zinc-500 font-medium">No modules found for this course</p>
                            <p class="text-sm text-zinc-400 mt-1">Try adjusting your filters</p>
                            @if($semester)
                                <a href="{{ route('reports.individual', $course->id) }}" class="mt-3 inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    Clear semester filter
                                </a>
                            @endif
                        </div>
                    </div>
                @endforelse
            </div>
    </main>

    {{-- Print Styles (disabled — printing is handled by the dedicated print layout) --}}
    <style>
        @media print {
            body { display: none !important; }
        }
    </style>

    {{-- html2pdf Library --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function exportToPDF() {
            const url = new URL("{{ route('reports.print.individual', $course->id) }}", window.location.origin);
            const params = new URLSearchParams(window.location.search);
            params.forEach((value, key) => url.searchParams.append(key, value));

            // Show loading indicator
            const btn = event.currentTarget;
            const originalText = btn.innerHTML;
            btn.innerHTML = `<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Generating PDF...`;
            btn.disabled = true;

            fetch(url.toString())
                .then(res => res.text())
                .then(html => {
                    const container = document.createElement('div');
                    container.innerHTML = html;
                    const reportEl = container.querySelector('.report-container') || container;

                    const opt = {
                        margin:       [12, 15, 12, 15],
                        filename:     '{{ Str::slug($course->course_name) }}_Report_{{ now()->format("Ymd_His") }}.pdf',
                        image:        { type: 'jpeg', quality: 0.98 },
                        html2canvas:  { scale: 2, useCORS: true, logging: false },
                        jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' },
                        pagebreak:    { mode: ['avoid-all', 'css', 'legacy'] }
                    };

                    html2pdf().set(opt).from(reportEl).save().then(() => {
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    });
                })
                .catch(err => {
                    console.error('PDF generation failed:', err);
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    // Fallback: open print layout
                    window.open(url.toString(), '_blank');
                });
        }

        function printReport() {
            const url = new URL("{{ route('reports.print.individual', $course->id) }}", window.location.origin);
            const params = new URLSearchParams(window.location.search);
            params.forEach((value, key) => url.searchParams.append(key, value));
            window.open(url.toString(), '_blank');
        }
    </script>
</x-admin-layout>
