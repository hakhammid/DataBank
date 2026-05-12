<div>
    <div class="top-16 z-10 bg-white pt-10 pb-2 mt-[5rem]">
            <div class="max-w-[1480px] mx-auto">
                <h1 class="text-3xl md:text-4xl font-bold text-zinc-900 text-center mb-6">Browse Learning Modules</h1>

                <!-- Centered Search Bar -->
                <div class="flex justify-center px-4">
                    <div class="w-full max-w-3xl relative">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <svg class="w-5 h-5 text-zinc-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1 0 6.75 6.75a7.5 7.5 0 0 0 9.9 9.9Z" />
                                </svg>
                            </div>

                            <!-- Search Input -->
                            <input wire:model.live.debounce.500ms="search" type="text"
                                placeholder="{{ $selectedCourseCode ? 'Search modules by title...' : 'Search by course code or title...' }}" id="searchInput" class="block w-full pl-12 pr-10 py-3 text-base text-zinc-900 placeholder-zinc-400
                                      bg-secondary/60 rounded-full border-none
                                      hover:bg-secondary focus:bg-secondary
                                      focus:outline-none focus:ring-2 focus:ring-zinc-900/20 focus:border-transparent
                                      transition-all duration-200" autocomplete="off" />

                            <!-- Clear Button -->
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                <button type="button" wire:click="clearSearch" id="clearBtn"
                                    class="text-zinc-900 hover:text-zinc-600 transition-all duration-200 ease-in-out opacity-0 scale-90"
                                    aria-label="Clear search" style="display: none;">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Semester Filter Choice Chips -->
            <div class="filter-container flex justify-center">
                <div class="filter-wrapper">
                    <div class="choice-chips flex flex-wrap gap-2 justify-center mt-4">
                        <button type="button" wire:click="setSemester('1st')"
                            class="choice-chip px-4 py-2 rounded-full text-sm font-medium truncate leading-snug bg-gray-100 hover:bg-gray-200 transition-colors duration-200 {{ $semester === '1st' ? 'active !bg-black text-white hover:!bg-black/90' : '' }}">
                            1st Semester
                        </button>
                        <button type="button" wire:click="setSemester('2nd')"
                            class="choice-chip px-4 py-2 rounded-full text-sm font-medium truncate leading-snug bg-gray-100 hover:bg-gray-200 transition-colors duration-200 {{ $semester === '2nd' ? 'active !bg-black text-white hover:!bg-black/90' : '' }}">
                            2nd Semester
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="min-h-[calc(100vh-60px)] bg-white py-6 px-1 lg:px-4 md:px-4">
            <div class="mx-auto max-w-[1480px] px-3 mt-4">
                <div class="mx-auto max-w-[1480px] px-3 mb-20">

                    @if($selectedCourseCode)
                        {{-- ============================================ --}}
                        {{-- MODULES VIEW (inside a course code) --}}
                        {{-- ============================================ --}}

                        <!-- Back Button -->
                        <div class="mb-6">
                            <button type="button" wire:click="setCourseCode('{{ $selectedCourseCode }}')"
                                class="inline-flex items-center gap-2 text-zinc-900 hover:text-zinc-700 font-medium transition-all duration-200 hover:-translate-x-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 6l-6 6l6 6" />
                                </svg>
                                Back to Course Codes
                            </button>
                        </div>

                        <!-- Selected Course Code Header -->
                        <div class="mb-8 bg-gradient-to-r from-zinc-900 to-zinc-700 rounded-2xl p-6 text-white">
                            <h2 class="text-2xl font-bold mb-2">{{ $selectedCourseCode }}</h2>
                            <p class="text-zinc-200">
                                @if($modules instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                    {{ $modules->total() }} {{ Str::plural('Module', $modules->total()) }}
                                @else
                                    0 Modules
                                @endif
                            </p>
                        </div>

                        <!-- Modules Grid -->
                        @if($modules instanceof \Illuminate\Pagination\LengthAwarePaginator && $modules->count())
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-6 md:gap-8 lg:gap-y-12 cursor-pointer">
                                @foreach ($modules as $module)
                                    <div class="group module-card" data-name="{{ $module->title }}"
                                        data-code="{{ $module->course_code }}" data-department="{{ $module->department_id ?? '' }}"
                                        wire:key="module-{{ $module->id }}">
                                        <div class="mt-auto pb-2 flex items-start justify-between gap-2">
                                            <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden flex-shrink-0">
                                                <img src="{{ $module->user->profile_picture ? asset('images/' . $module->user->profile_picture) : asset('images/default_profile.png') }}"
                                                    alt="Profile Picture" class="w-full h-full object-cover" draggable="false"
                                                    ondragstart="return false;" onselectstart="return false;">
                                            </div>
                                            <a href="{{ route('view-module', $module) }}"
                                                class="flex items-center gap-3 min-w-0 flex-1">
                                                <div class="flex flex-col min-w-0">
                                                    <h4 class="text-md font-medium text-zinc-900 truncate leading-snug">
                                                        {{ $module->user->name }}
                                                    </h4>
                                                    <p class="text-sm text-zinc-600 truncate">
                                                        {{ $module->user->department->department_name ?? '' }}
                                                    </p>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="flex flex-col">
                                            <a href="{{ route('view-module', $module) }}" class="block">
                                                <div
                                                    class="pdf-card relative xl:h-[25rem] md:h-[15rem] h-[15rem] overflow-hidden rounded-xl bg-secondary transition-all duration-200 hover:shadow-sm flex flex-col">
                                                    <div class="absolute inset-0 duration-300 z-10">
                                                        <iframe
                                                            src="{{ asset('files/' . $module->file) }}#toolbar=0&navpanes=0&scrollbar=0&view=FitH"
                                                            class="w-full h-full scale-110 rounded-xl" type="application/pdf"
                                                            oncontextmenu="return false;" style="
                                                        pointer-events: none;
                                                        border: none;
                                                        background-color: transparent;
                                                        overflow: hidden;">
                                                        </iframe>
                                                    </div>
                                                    <div
                                                        class="absolute bottom-0 h-[10rem] bg-gradient-to-t from-black/30 via-black/25 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
                                                    </div>
                                                    <div class="flex items-center justify-center">
                                                        <div class="flex items-center justify-center">
                                                            @if ($module->isMajor)
                                                                <div
                                                                    class="absolute top-3 right-4 gap-1 flex flex-col z-20 p-2 rounded-full shadow-lg backdrop-blur-sm">
                                                                    <div class="relative">
                                                                        <div
                                                                            class="flex items-center gap-2 text-white group focus:outline-none">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                class="h-6 w-6 text-yellow-400" fill="currentColor"
                                                                                viewBox="0 0 20 20">
                                                                                <path
                                                                                    d="M9.049 2.927a1 1 0 011.902 0l1.1 3.394a1 1 0 00.95.69h3.574a1 1 0 01.591 1.81l-2.89 2.103a1 1 0 00-.364 1.118l1.1 3.394a1 1 0 01-1.537 1.118L10 13.347l-2.975 2.109a1 1 0 01-1.537-1.118l1.1-3.394a1 1 0 00-.364-1.118L3.334 8.82a1 1 0 01.591-1.81h3.574a1 1 0 00.95-.69l1.1-3.394z" />
                                                                            </svg>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            <div
                                                                class="hover-actions absolute bottom-3 gap-1 flex flex-col z-20 bg-black/60 p-4 rounded-xl shadow-lg backdrop-blur-sm">
                                                                <div class="flex items-center gap-2 text-white">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="h-5 w-5 text-yellow-400" fill="currentColor"
                                                                        viewBox="0 0 20 20">
                                                                        <path
                                                                            d="M9.049 2.927a1 1 0 011.902 0l1.1 3.394a1 1 0 00.95.69h3.574a1 1 0 01.591 1.81l-2.89 2.103a1 1 0 00-.364 1.118l1.1 3.394a1 1 0 01-1.537 1.118L10 13.347l-2.975 2.109a1 1 0 01-1.537-1.118l1.1-3.394a1 1 0 00-.364-1.118L3.334 8.82a1 1 0 01.591-1.81h3.574a1 1 0 00.95-.69l1.1-3.394z" />
                                                                    </svg>
                                                                    <span class="text-sm font-semibold truncate">{{ $module->isMajor ? 'Major Subject' : 'Minor Subject'
                                                                    }}</span>
                                                                </div>
                                                                <div class="flex items-center gap-2 text-white">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                                        fill="none" stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        class="h-5 w-5 text-white">
                                                                        <path d="m16 18 6-6-6-6" />
                                                                        <path d="m8 6-6 6 6 6" />
                                                                    </svg>
                                                                    <span class="text-sm font-semibold truncate">{{ $module->course_code
                                                                    }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="mt-auto pt-2 flex items-start justify-between gap-4">
                                                <a href="{{ route('view-module', $module) }}"
                                                    class="flex items-center gap-3 min-w-0 flex-1">
                                                    <div class="flex flex-col min-w-0">
                                                        <h4 class="text-md font-medium text-zinc-900 truncate leading-snug">
                                                            {{ $module->title }}
                                                        </h4>
                                                    </div>
                                                </a>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!--   Pagination -->
                            @if($modules->hasPages())
                                <div class="mt-6 flex items-center justify-center">
                                    <div class="flex space-x-2">
                                        @if ($modules->onFirstPage())
                                            <span class="flex px-2 py-1 rounded border border-zinc-400 text-zinc-400 cursor-not-allowed items-center justify-center text-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="w-5 h-5">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M15 6l-6 6l6 6" />
                                                </svg>
                                            </span>
                                        @else
                                            <button wire:click="previousPage"
                                                class="flex px-2 py-1 rounded border border-zinc-900 text-zinc-900 items-center justify-center text-center hover:bg-zinc-50 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="w-5 h-5">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M15 6l-6 6l6 6" />
                                                </svg>
                                            </button>
                                        @endif

                                        @php
                                            $currentPage = $modules->currentPage();
                                            $lastPage = $modules->lastPage();
                                            $start = max($currentPage - 1, 1);
                                            $end = min($currentPage + 1, $lastPage);
                                            $pages = [];

                                            if ($start > 1) {
                                                $pages[] = 1;
                                                if ($start > 2) {
                                                    $pages[] = '...';
                                                }
                                            }

                                            for ($i = $start; $i <= $end; $i++) {
                                                $pages[] = $i;
                                            }
                                            if ($end < $lastPage) {
                                                if ($end < $lastPage - 1) {
                                                    $pages[] = '...';
                                                }
                                                $pages[] = $lastPage;
                                            }
                                        @endphp

                                        @foreach ($pages as $page)
                                            @if ($page == '...')
                                                <span class="w-10 h-10 flex items-center justify-center rounded text-zinc-900">...</span>
                                            @elseif ($page == $modules->currentPage())
                                                <span class="w-10 h-10 flex items-center justify-center rounded bg-zinc-900 text-white font-semibold">
                                                    {{ $page }}
                                                </span>
                                            @else
                                                <button wire:click="gotoPage({{ $page }})" wire:key="paginator-page-{{ $page }}"
                                                    class="w-10 h-10 flex items-center justify-center rounded border border-zinc-900 text-zinc-900 hover:text-white hover:bg-zinc-900 transition">
                                                    {{ $page }}
                                                </button>
                                            @endif
                                        @endforeach

                                        @if ($modules->hasMorePages())
                                            <button wire:click="nextPage"
                                                class="flex px-2 py-1 rounded border border-zinc-900 text-zinc-900 items-center justify-center text-center hover:bg-zinc-50 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="w-5 h-5">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M9 6l6 6l-6 6" />
                                                </svg>
                                            </button>
                                        @else
                                            <span
                                                class="flex px-2 py-1 rounded border border-zinc-400 text-zinc-400 cursor-not-allowed items-center justify-center text-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="w-5 h-5">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M9 6l6 6l-6 6" />
                                                </svg>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @else
                            <!-- Empty State - No Modules -->
                            <div class="flex flex-col items-center justify-center rounded-xl bg-white py-16">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-zinc-300" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="mt-4 text-lg font-medium text-zinc-700">No modules found for this course code.</h3>
                            </div>
                        @endif

                    @else
                        {{-- ============================================ --}}
                        {{-- COURSE CODE CARDS VIEW (default) --}}
                        {{-- ============================================ --}}

                        @if($courseCodeGroups->count())
                            <h2 class="text-2xl font-bold text-zinc-900 mb-6">Browse by Course Code</h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                                @foreach($courseCodeGroups as $group)
                                    <button type="button" wire:click="setCourseCode('{{ $group->course_code }}')"
                                        class="course-card bg-white rounded-xl border-2 border-zinc-200 p-6 hover:border-zinc-900 text-left transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="p-3 bg-zinc-100 rounded-lg">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-zinc-900" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                </svg>
                                            </div>
                                            <span class="px-3 py-1 bg-zinc-900 text-white text-sm font-semibold rounded-full">
                                                {{ $group->modules_count }}
                                            </span>
                                        </div>

                                        <h3 class="text-lg font-bold text-zinc-900 mb-2">
                                            {{ $group->course_code }}
                                        </h3>

                                        <p class="text-sm text-zinc-600">
                                            {{ $group->modules_count }} {{ Str::plural('Module', $group->modules_count) }}
                                        </p>

                                        <div class="mt-4 flex items-center text-zinc-900 font-medium">
                                            <span class="text-sm">View Modules</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <path d="M9 6l6 6l-6 6" />
                                            </svg>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        @else
                            <!-- Empty State - No Course Codes -->
                            <div class="flex flex-col items-center justify-center rounded-xl bg-white py-16">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-zinc-300" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="mt-4 text-lg font-medium text-zinc-700">No modules found.</h3>
                            </div>
                        @endif

                    @endif
                </div>
            </div>
    </div>

</div>
