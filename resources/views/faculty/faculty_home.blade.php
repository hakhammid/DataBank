<x-faculty-layout :title="'Home'">
    <header>
        <style>
            .course-card {
                transition: all 0.3s ease;
                cursor: pointer;
            }

            .course-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            }

            .backdrop-blur {
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
            }

            .backdrop-blur body {
                overflow: hidden;
            }

            .pdf-card .hover-actions {
                opacity: 0;
                transform: translateY(10px);
                transition: all 0.2s ease-in-out;
            }

            .pdf-card:hover .hover-actions {
                opacity: 1;
                transform: translateY(0);
            }

            .border-error-500 {
                border-color: #ef4444 !important;
            }

            .text-error-500 {
                color: #ef4444 !important;
            }

            .focus\:border-error-500:focus {
                border-color: #ef4444 !important;
            }

            .dropdown-menu {
                transition: all 0.2s ease-in-out;
                transform-origin: top right;
            }

            .dropdown-menu.hidden {
                opacity: 0;
                transform: scaleY(0);
                visibility: hidden;
            }

            .dropdown-menu:not(.hidden) {
                opacity: 1;
                transform: scaleY(1);
                visibility: visible;
            }

            .back-button {
                transition: all 0.2s ease;
            }

            .back-button:hover {
                transform: translateX(-4px);
            }
        </style>
    </header>

    <div class="min-h-[calc(100vh-60px)] bg-white py-6 px-1 lg:px-4 md:px-4">
        <div class="container mx-auto max-w-[1480px] px-3 mb-20 mt-12">

            @if($selectedCourse && $selectedCourseCode)
                {{-- ============================================ --}}
                {{-- LEVEL 3: Modules inside a Course Code --}}
                {{-- ============================================ --}}

                <!-- Back Button -->
                <div class="mb-6">
                    <a href="{{ route('faculty.home', ['course_id' => $selectedCourse->id]) }}"
                        class="back-button inline-flex items-center gap-2 text-zinc-900 hover:text-zinc-700 font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 6l-6 6l6 6" />
                        </svg>
                        Back to Course Codes
                    </a>
                </div>

                <!-- Selected Course Code Header -->
                <div class="mb-8 bg-gradient-to-r from-zinc-900 to-zinc-700 rounded-2xl p-6 text-white">
                    <p class="text-zinc-300 text-sm mb-1">{{ $selectedCourse->course_name }}</p>
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
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-6 md:gap-8 lg:gap-y-12 cursor-pointer">
                    @foreach ($modules as $module)
                    @include('faculty.partials.module_card', ['module' => $module])
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($modules->hasPages())
                <div class="mt-8 flex items-center justify-center">
                    <div class="flex space-x-2">
                        @if ($modules->onFirstPage())
                        <span
                            class="flex px-2 py-1 rounded border border-zinc-400 text-zinc-400 cursor-not-allowed items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="M15 6l-6 6l6 6" />
                            </svg>
                        </span>
                        @else
                        <a href="{{ $modules->previousPageUrl() }}"
                            class="flex px-2 py-1 rounded border border-zinc-900 text-zinc-900 hover:bg-zinc-50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="M15 6l-6 6l6 6" />
                            </svg>
                        </a>
                        @endif

                        @php
                        $currentPage = $modules->currentPage();
                        $lastPage = $modules->lastPage();
                        $start = max($currentPage - 1, 1);
                        $end = min($currentPage + 1, $lastPage);
                        $pages = [];

                        if ($start > 1) {
                        $pages[] = 1;
                        if ($start > 2) $pages[] = '...';
                        }

                        for ($i = $start; $i <= $end; $i++) { $pages[]=$i; } if ($end < $lastPage) { if ($end < $lastPage -
                            1) $pages[]='...' ; $pages[]=$lastPage; } @endphp @foreach ($pages as $page) @if ($page=='...' )
                            <span class="w-10 h-10 flex items-center justify-center">...</span>
                            @elseif ($page == $currentPage)
                            <span
                                class="w-10 h-10 flex items-center justify-center rounded bg-zinc-900 text-white font-semibold">
                                {{ $page }}
                            </span>
                            @else
                            <a href="{{ $modules->url($page) }}"
                                class="w-10 h-10 flex items-center justify-center rounded border border-zinc-900 text-zinc-900 hover:bg-zinc-900 hover:text-white transition">
                                {{ $page }}
                            </a>
                            @endif
                            @endforeach

                            @if ($modules->hasMorePages())
                            <a href="{{ $modules->nextPageUrl() }}"
                                class="flex px-2 py-1 rounded border border-zinc-900 text-zinc-900 hover:bg-zinc-50 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path d="M9 6l6 6l-6 6" />
                                </svg>
                            </a>
                            @else
                            <span class="flex px-2 py-1 rounded border border-zinc-400 text-zinc-400 cursor-not-allowed">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path d="M9 6l6 6l-6 6" />
                                </svg>
                            </span>
                            @endif
                    </div>
                </div>
                @endif
                @else
                <!-- Empty State -->
                <div class="flex flex-col items-center justify-center py-16">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-700">No modules for this course code yet.</h3>
                </div>
                @endif

            @elseif($selectedCourse)
                {{-- ============================================ --}}
                {{-- LEVEL 2: Course Codes inside a Course --}}
                {{-- ============================================ --}}

                <!-- Back Button -->
                <div class="mb-6">
                    <a href="{{ route('faculty.home') }}"
                        class="back-button inline-flex items-center gap-2 text-zinc-900 hover:text-zinc-700 font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 6l-6 6l6 6" />
                        </svg>
                        Back to Courses
                    </a>
                </div>

                <!-- Selected Course Header -->
                <div class="mb-8 bg-gradient-to-r from-zinc-900 to-zinc-700 rounded-2xl p-6 text-white">
                    <h2 class="text-2xl font-bold mb-2">{{ $selectedCourse->course_name }}</h2>
                    <p class="text-zinc-200">{{ $courseCodeGroups->sum('modules_count') }} {{ Str::plural('Module', $courseCodeGroups->sum('modules_count')) }} across {{ $courseCodeGroups->count() }} {{ Str::plural('Course Code', $courseCodeGroups->count()) }}</p>
                </div>

                <!-- Course Code Cards -->
                @if($courseCodeGroups->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($courseCodeGroups as $group)
                    <a href="{{ route('faculty.home', ['course_id' => $selectedCourse->id, 'course_code' => $group->course_code]) }}"
                        class="course-card bg-white rounded-xl border-2 border-zinc-200 p-6 hover:border-zinc-900">
                        <div class="flex items-start justify-between mb-4">
                            <div class="p-3 bg-zinc-100 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-zinc-900" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m16 18 6-6-6-6M8 6l-6 6 6 6" />
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
                    </a>
                    @endforeach
                </div>
                @else
                <!-- Empty State -->
                <div class="flex flex-col items-center justify-center py-16">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-700">No modules in this course yet.</h3>
                </div>
                @endif

            @else
                {{-- ============================================ --}}
                {{-- LEVEL 1: Course Cards View (default) --}}
                {{-- ============================================ --}}

                @if($coursesWithModules->count())
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-zinc-900 mb-2">Your Courses</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($coursesWithModules as $course)
                    <a href="{{ route('faculty.home', ['course_id' => $course->id]) }}"
                        class="course-card bg-white rounded-xl border-2 border-zinc-200 p-6 hover:border-zinc-900">
                        <div class="flex items-start justify-between mb-4">
                            <div class="p-3 bg-zinc-100 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-zinc-900" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <span class="px-3 py-1 bg-zinc-900 text-white text-sm font-semibold rounded-full">
                                {{ $course->modules_count }}
                            </span>
                        </div>

                        <h3 class="text-lg font-bold text-zinc-900 mb-2 line-clamp-2">
                            {{ $course->course_name }}
                        </h3>

                        <p class="text-sm text-zinc-600">
                            {{ $course->modules_count }} {{ Str::plural('Module', $course->modules_count) }}
                        </p>

                        <div class="mt-4 flex items-center text-zinc-900 font-medium">
                            <span class="text-sm">View Course Codes</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="M9 6l6 6l-6 6" />
                            </svg>
                        </div>
                    </a>
                    @endforeach
                </div>
                @else
                <!-- Empty State - No Courses -->
                <div class="flex flex-col items-center justify-center py-16">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-700">No modules uploaded yet</h3>
                    <p class="mt-2 text-sm text-gray-500">Start by creating your first module</p>
                </div>
                @endif
            @endif
        </div>
    </div>
</x-faculty-layout>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('moduleModal', () => ({
            init() {
                this.$watch('showModal', value => {
                    if (!value) {
                        document.body.style.overflow = '';
                        document.body.classList.remove('backdrop-blur');
                    }
                });
            }
        }));
    });
</script>
