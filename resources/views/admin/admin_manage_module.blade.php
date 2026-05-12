<x-admin-layout :title="'All Modules'">
    <main class="flex-1 max-h-full p-5 lg:mt-0 mt-20">
        <div class="flex items-end justify-between">
            <h1 class="text-2xl/8 font-semibold text-zinc-950 sm:text-xl/8">
                Modules
            </h1>
            <x-my-secondary-button onclick="window.location.href='{{ route('admin.module.create') }}'">
                Create new module
            </x-my-secondary-button>
        </div>

        <!-- Enhanced Search and Export Controls -->
        <div class="mt-8 bg-white rounded-xl border border-zinc-200 p-6 shadow-sm">
            <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between">
                <!-- Export Controls -->
                <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                    <!-- Search Section -->
                    <div class="flex-1 w-full lg:w-auto">
                        <div class="relative w-full max-w-md">
                            <input type="text" id="moduleSearch" placeholder="Search..."
                                class="block w-full py-2.5 border border-zinc-300 rounded-lg focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all duration-200 text-sm placeholder-zinc-400">
                            <button type="button" id="clearSearch"
                                class="absolute inset-y-0 right-0 mr-2 flex items-center opacity-0 transition-opacity duration-200 hover:text-zinc-600">
                                <svg class="h-5 w-5 text-zinc-400 hover:text-zinc-600 transition-colors" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                </div>
            </div>

            <!-- Search Results Status -->
            <div id="searchStatus" class="mt-4 hidden">
                <div class="flex items-center gap-2 text-sm text-zinc-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span id="searchResultsText"></span>
                </div>
            </div>
        </div>

        <!-- Module Table -->
        <div class="mt-6 inline-block min-w-full align-middle sm:px-[--gutter]">
            <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm">
                <table class="min-w-full text-left text-sm/6 text-zinc-950" id="table">
                    <thead class="border-b border-zinc-200">
                        <tr>
                            <th class="px-4 py-3 font-semibold text-zinc-700">Course Code</th>
                            <th class="px-4 py-3 font-semibold text-zinc-700">Course Title</th>

                            <th class="px-4 py-3 font-semibold text-zinc-700">Views</th>
                            <th class="px-4 py-3 font-semibold text-zinc-700">Uploaded By</th>
                            <th class="px-4 py-3 font-semibold text-zinc-700">Date Posted</th>
                            <th class="px-4 py-3 font-semibold text-zinc-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($modules as $module)
                        <!-- Custom PDF Viewer Modal with Blur Effect -->
                        <div x-data="{ showModuleModal: false }" x-show="showModuleModal"
                            x-on:open-modal.window="if ($event.detail === 'view-module-{{$module->id}}') { showModuleModal = true; document.body.classList.add('overflow-hidden', 'backdrop-blur') }"
                            x-on:close.stop="showModuleModal = false; document.body.classList.remove('overflow-hidden', 'backdrop-blur')"
                            x-on:keydown.escape.window="showModuleModal = false; document.body.classList.remove('overflow-hidden', 'backdrop-blur')"
                            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                            class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">

                            <!-- Blurred Overlay -->
                            <div class="fixed inset-0 bg-black/70 backdrop-blur-lg transition-opacity"
                                x-on:click="showModuleModal = false; document.body.classList.remove('overflow-hidden', 'backdrop-blur')">
                            </div>

                            <!-- Modal Container -->
                            <div
                                class="flex items-center justify-center min-h-screen pt-0 pb-10 text-center sm:block sm:p-0">
                                <!-- Modal Content -->
                                <div class="inline-block align-bottom text-left overflow-hidden transform transition-all sm:my-4 px-8 sm:align-middle sm:max-w-full sm:w-full h-[95vh] w-full max-w-[1800px]"
                                    x-transition:enter="ease-out duration-300"
                                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                    x-transition:leave="ease-in duration-200"
                                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

                                    <!-- Floating Header -->
                                    <div
                                        class="bg-transparent my-5 flex justify-between items-center sticky z-10 mx-auto w-full">
                                        <h3 class="text-lg leading-6 font-medium text-white truncate max-w-[60vw]">
                                            ({{ $module->course_code }}) {{ $module->title }}
                                        </h3>
                                        <div class="flex items-center">
                                            <a href="{{ asset('files/' . $module['file']) }}"
                                                download="{{ $module->title }}.pdf"
                                                class="ml-4 bg-white/20 rounded-full p-2 transition-all duration-200 focus:outline-none relative group">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" class="h-6 w-6 text-white">
                                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                    <polyline points="7 10 12 15 17 10" />
                                                    <line x1="12" x2="12" y1="15" y2="3" />
                                                </svg>
                                                <span
                                                    class="absolute top-full left-1/2 -translate-x-1/2 mt-2 px-2 py-1 bg-white text-zinc-800 text-xs rounded shadow-md opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 whitespace-nowrap border border-zinc-100">Download</span>
                                            </a>
                                            <button
                                                x-on:click="showModuleModal = false; document.body.classList.remove('overflow-hidden', 'backdrop-blur')"
                                                class="ml-4 bg-white/20 rounded-full p-2 transition-all duration-200 focus:outline-none">
                                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- PDF Viewer Container -->
                                    <div class="h-full w-full relative bg-gray-100/10 backdrop-blur-sm">
                                        <iframe
                                            src="{{ asset('files/' . $module['file']) }}#toolbar=0&navpanes=0&view=FitH"
                                            class="absolute inset-0 w-full h-full pb-20" type="application/pdf"
                                            id="pdf-viewer-{{$module->id}}" oncontextmenu="return false;"
                                            onselectstart="return false;" oncopy="return false;" oncut="return false;"
                                            onpaste="return false;" onkeydown="return false;"
                                            onmousedown="return false;" onmousemove="return false;"
                                            onmouseup="return false;" onmousewheel="return false;"
                                            onmouseenter="return false;" onmouseleave="return false;"
                                            onmouseover="return false;" onmouseout="return false;"
                                            onload="this.contentWindow.document.body.style.cursor='default';">
                                        </iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <tr class="border-b border-zinc-100 hover:bg-zinc-50 transition-colors duration-150 module-row"
                            data-module-id="{{ $module->id}}" data-module-title="{{ $module->title }}"
                            data-module-course-code="{{ $module->course_code }}"
                            data-module-uploader-name="{{ $module->user->name }}">
                            <td class="px-4 py-3 font-medium text-zinc-900">{{ $module->course_code }}</td>
                            <td class="px-4 py-3 text-zinc-600">{{ $module->title }}</td>

                            <td class="px-4 py-3 text-zinc-600">{{ $module->number_of_views }}</td>
                            <td
                                class="relative px-4 first:pl-[var(--gutter,theme(spacing.2))] last:pr-[var(--gutter,theme(spacing.2))] border-b border-zinc-950/5 dark:border-white/5 py-4 sm:first:pl-1 sm:last:pr-1">
                                <div class="flex gap-2 items-center">
                                    <div class="w-8 h-8 rounded-full bg-gray-200 overflow-hidden flex-shrink-0">
                                        <img src="{{ $module->user->profile_picture ? asset('images/' . $module->user->profile_picture) : asset('images/default_profile.png') }}"
                                            alt="Profile Picture" class="w-full h-full object-cover">
                                    </div>
                                    {{ $module->user->name }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-zinc-600">{{ $module->created_at->format('M d, Y') }}</td>
                            <td class="py-3">
                                <div class="flex gap-2 items-center">
                                    {{-- <a href="{{ route('admin.module.edit', $module->id) }}"
                                        class="inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-sm font-medium text-zinc-900 hover:bg-zinc-900/5 transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </a> --}}

                                    <button x-on:click="$dispatch('open-modal', 'view-module-{{$module->id}}')"
                                        class="inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-sm font-medium text-zinc-900 hover:bg-zinc-900/5 transition-colors duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="h-4 w-4">
                                            <path
                                                d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0a1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                        View
                                    </button>
                                    <button data-modal-target="delete-module-modal"
                                        class="inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-sm font-medium text-danger hover:bg-danger/5 transition-colors duration-200">
                                        <svg class="w-4 h-4 text-danger" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-zinc-500">
                                <div class="flex flex-col items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="w-12 h-12 text-zinc-300">
                                        <rect width="8" height="18" x="3" y="3" rx="1" />
                                        <path d="M7 3v18" />
                                        <path
                                            d="M20.4 18.9c.2.5-.1 1.1-.6 1.3l-1.9.7c-.5.2-1.1-.1-1.3-.6L11.1 5.1c-.2-.5.1-1.1.6-1.3l1.9-.7c.5-.2 1.1.1 1.3.6Z" />
                                    </svg>
                                    <p class="text-lg font-medium">No module yet</p>
                                    <p class="text-sm">Get started by creating your first module.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($modules->hasPages())
            <div class="mt-6 flex items-center justify-center">
                {{-- <div class="text-sm text-gray-500">
                    Showing {{ $modules->firstItem() }} to {{ $modules->lastItem() }} of {{ $modules->total() }} results
                </div> --}}
                <div class="flex space-x-2">
                    @if ($modules->onFirstPage())
                    <span
                        class="flex px-2 py-1 rounded border border-zinc-400 text-zinc-400 cursor-not-allowed items-center justify-center text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="w-5 h-5">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M15 6l-6 6l6 6" />
                        </svg>
                    </span>
                    @else
                    <a href="{{ $modules->previousPageUrl() }}"
                        class="flex px-2 py-1 rounded border border-zinc-900 text-zinc-900 items-center justify-center text-center hover:bg-zinc-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="w-5 h-5">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M15 6l-6 6l6 6" />
                        </svg>
                    </a>
                    @endif

                    @foreach ($modules->getUrlRange(1, $modules->lastPage()) as $page => $url)
                    @if ($page == $modules->currentPage())
                    <span
                        class="w-10 h-10 flex items-center justify-center rounded bg-zinc-900 text-white font-semibold">
                        {{ $page }}
                    </span>
                    @else
                    <a href="{{ $url }}"
                        class="w-10 h-10 flex items-center justify-center rounded border border-zinc-900 text-zinc-900 hover:text-white hover:bg-zinc-900 transition">
                        {{ $page }}
                    </a>
                    @endif
                    @endforeach

                    @if ($modules->hasMorePages())
                    <a href="{{ $modules->nextPageUrl() }}"
                        class="flex px-2 py-1 rounded border border-zinc-900 text-zinc-900 items-center justify-center text-center hover:bg-zinc-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M9 6l6 6l-6 6" />
                        </svg>
                    </a>
                    @else
                    <span
                        class="flex px-2 py-1 rounded border border-zinc-400 text-zinc-400 cursor-not-allowed items-center justify-center text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M9 6l6 6l-6 6" />
                        </svg>
                    </span>
                    @endif
                </div>
            </div>
            @endif
        </div>



        <!-- Module Table -->
        {{-- <div class="mt-8 inline-block min-w-full align-middle sm:px-[--gutter]">
            <table class="min-w-full text-left text-sm/6 text-zinc-950">
                <thead class="text-zinc-500 dark:text-zinc-400">
                    <tr class="">
                        <th
                            class="border-b border-b-zinc-950/10 px-4 py-2 font-medium first:pl-[var(--gutter,theme(spacing.2))] last:pr-[var(--gutter,theme(spacing.2))] dark:border-b-white/10 sm:first:pl-1 sm:last:pr-1">
                            Course Code
                        </th>
                        <th
                            class="border-b border-b-zinc-950/10 px-4 py-2 font-medium first:pl-[var(--gutter,theme(spacing.2))] last:pr-[var(--gutter,theme(spacing.2))] dark:border-b-white/10 sm:first:pl-1 sm:last:pr-1">
                            Course Title
                        </th>

                        <th
                            class="border-b border-b-zinc-950/10 px-4 py-2 font-medium first:pl-[var(--gutter,theme(spacing.2))] last:pr-[var(--gutter,theme(spacing.2))] dark:border-b-white/10 sm:first:pl-1 sm:last:pr-1">
                            Views
                        </th>
                        <th
                            class="border-b border-b-zinc-950/10 px-4 py-2 font-medium first:pl-[var(--gutter,theme(spacing.2))] last:pr-[var(--gutter,theme(spacing.2))] dark:border-b-white/10 sm:first:pl-1 sm:last:pr-1">
                            Uploaded By
                        </th>
                        <th
                            class="border-b border-b-zinc-950/10 px-4 py-2 font-medium first:pl-[var(--gutter,theme(spacing.2))] last:pr-[var(--gutter,theme(spacing.2))] dark:border-b-white/10 sm:first:pl-1 sm:last:pr-1">
                            Date Posted
                        </th>
                        <th
                            class="border-b border-b-zinc-950/10 px-4 py-2 font-medium first:pl-[var(--gutter,theme(spacing.2))] last:pr-[var(--gutter,theme(spacing.2))] dark:border-b-white/10 sm:first:pl-1 sm:last:pr-1">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($modules as $module)
                    <!-- Custom PDF Viewer Modal with Blur Effect -->
                    <div x-data="{ showModuleModal: false }" x-show="showModuleModal"
                        x-on:open-modal.window="if ($event.detail === 'view-module-{{$module->id}}') { showModuleModal = true; document.body.classList.add('overflow-hidden', 'backdrop-blur') }"
                        x-on:close.stop="showModuleModal = false; document.body.classList.remove('overflow-hidden', 'backdrop-blur')"
                        x-on:keydown.escape.window="showModuleModal = false; document.body.classList.remove('overflow-hidden', 'backdrop-blur')"
                        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                        class="fixed inset-0 z-[9999] overflow-y-auto" style="display: none;">

                        <!-- Blurred Overlay -->
                        <div class="fixed inset-0 bg-black/70 backdrop-blur-lg transition-opacity"
                            x-on:click="showModuleModal = false; document.body.classList.remove('overflow-hidden', 'backdrop-blur')">
                        </div>

                        <!-- Modal Container -->
                        <div
                            class="flex items-center justify-center min-h-screen pt-0 pb-10 text-center sm:block sm:p-0">
                            <!-- Modal Content -->
                            <div class="inline-block align-bottom text-left overflow-hidden transform transition-all sm:my-4 px-8 sm:align-middle sm:max-w-full sm:w-full h-[95vh] w-full max-w-[1800px]"
                                x-transition:enter="ease-out duration-300"
                                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                x-transition:leave="ease-in duration-200"
                                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

                                <!-- Floating Header -->
                                <div
                                    class="bg-transparent my-5 flex justify-between items-center sticky z-10 mx-auto w-full">
                                    <h3 class="text-lg leading-6 font-medium text-white truncate max-w-[60vw]">
                                        ({{ $module->course_code }}) {{ $module->title }}
                                    </h3>
                                    <div class="flex items-center">
                                        <a href="{{ asset('files/' . $module['file']) }}"
                                            download="{{ $module->title }}.pdf"
                                            class="ml-4 bg-white/20 rounded-full p-2 transition-all duration-200 focus:outline-none relative group">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="h-6 w-6 text-white">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                <polyline points="7 10 12 15 17 10" />
                                                <line x1="12" x2="12" y1="15" y2="3" />
                                            </svg>
                                            <span
                                                class="absolute top-full left-1/2 -translate-x-1/2 mt-2 px-2 py-1 bg-white text-zinc-800 text-xs rounded shadow-md opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 whitespace-nowrap border border-zinc-100">Download</span>
                                        </a>
                                        <button
                                            x-on:click="showModuleModal = false; document.body.classList.remove('overflow-hidden', 'backdrop-blur')"
                                            class="ml-4 bg-white/20 rounded-full p-2 transition-all duration-200 focus:outline-none">
                                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- PDF Viewer Container -->
                                <div class="h-full w-full relative bg-gray-100/10 backdrop-blur-sm">
                                    <iframe src="{{ asset('files/' . $module['file']) }}#toolbar=0&navpanes=0&view=FitH"
                                        class="absolute inset-0 w-full h-full pb-20" type="application/pdf"
                                        id="pdf-viewer-{{$module->id}}" oncontextmenu="return false;"
                                        onselectstart="return false;" oncopy="return false;" oncut="return false;"
                                        onpaste="return false;" onkeydown="return false;" onmousedown="return false;"
                                        onmousemove="return false;" onmouseup="return false;"
                                        onmousewheel="return false;" onmouseenter="return false;"
                                        onmouseleave="return false;" onmouseover="return false;"
                                        onmouseout="return false;"
                                        onload="this.contentWindow.document.body.style.cursor='default';">
                                    </iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                    <tr class="has-[[data-row-link][data-focus]]:outline has-[[data-row-link][data-focus]]:outline-2 has-[[data-row-link][data-focus]]:-outline-offset-2 has-[[data-row-link][data-focus]]:outline-blue-500 hover:bg-zinc-950/[2.5%]"
                        data-module-id="{{ $module->id }}">
                        <td
                            class="relative px-4 first:pl-[var(--gutter,theme(spacing.2))] last:pr-[var(--gutter,theme(spacing.2))] border-b border-zinc-950/5 dark:border-white/5 py-4 sm:first:pl-1 sm:last:pr-1">
                            {{ $module->course_code}}
                        </td>
                        <td
                            class="relative px-4 first:pl-[var(--gutter,theme(spacing.2))] last:pr-[var(--gutter,theme(spacing.2))] border-b border-zinc-950/5 dark:border-white/5 py-4 sm:first:pl-1 sm:last:pr-1">
                            {{ $module->title}}
                        </td>

                        <td
                            class="relative px-4 first:pl-[var(--gutter,theme(spacing.2))] last:pr-[var(--gutter,theme(spacing.2))] border-b border-zinc-950/5 dark:border-white/5 py-4 sm:first:pl-1 sm:last:pr-1">
                            {{ $module->number_of_views}}
                        </td>
                        <td
                            class="relative px-4 first:pl-[var(--gutter,theme(spacing.2))] last:pr-[var(--gutter,theme(spacing.2))] border-b border-zinc-950/5 dark:border-white/5 py-4 sm:first:pl-1 sm:last:pr-1">
                            <div class="flex gap-2 items-center">
                                <div class="w-8 h-8 rounded-full bg-gray-200 overflow-hidden flex-shrink-0">
                                    <img src="{{ $module->user->profile_picture ? asset('images/' . $module->user->profile_picture) : asset('images/default_profile.png') }}"
                                        alt="Profile Picture" class="w-full h-full object-cover">
                                </div>
                                {{ $module->user->name}}
                            </div>
                        </td>
                        <td
                            class="relative px-4 first:pl-[var(--gutter,theme(spacing.2))] last:pr-[var(--gutter,theme(spacing.2))] border-b border-zinc-950/5 dark:border-white/5 py-4 sm:first:pl-1 sm:last:pr-1">
                            {{ $module->created_at->format('F d, Y') }}
                        </td>
                        <td
                            class="relative px-4 first:pl-[var(--gutter,theme(spacing.2))] last:pr-[var(--gutter,theme(spacing.2))] border-b border-zinc-950/5 dark:border-white/5 py-4 sm:first:pl-1 sm:last:pr-1">
                            <div class="flex gap-2 items-center">
                                <div class="flex items-center">
                                    <a href="{{ route('admin.module.edit', $module->id) }}"
                                        class="inline-flex items-center gap-x-1.5 rounded-md px-2 py-1 text-sm/5 font-medium sm:text-xs/5 forced-colors:outline bg-gray-400/10 text-gray-700 group-data-[hover]:bg-gray-400/30 relative group"
                                        data-headlessui-state=""><span
                                            class="absolute left-1/2 top-1/2 size-[max(100%,2.75rem)] -translate-x-1/2 -translate-y-1/2 [@media(pointer:fine)]:hidden"
                                            aria-hidden="true"></span>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="h-5 w-5">
                                            <path
                                                d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" />
                                        </svg>
                                        <span
                                            class="absolute top-full left-1/2 -translate-x-1/2 mt-2 px-2 py-1 bg-white text-zinc-800 text-xs rounded shadow-md opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 whitespace-nowrap border border-zinc-100">Edit</span>
                                    </a>
                                </div>
                                <div class="flex items-center">
                                    <button x-on:click="$dispatch('open-modal', 'view-module-{{$module->id}}')"
                                        class="inline-flex items-center gap-x-1.5 rounded-md px-2 py-1 text-sm/5 font-medium sm:text-xs/5 forced-colors:outline bg-gray-400/10 text-gray-700 group-data-[hover]:bg-gray-400/30 relative group"
                                        data-headlessui-state=""><span
                                            class="absolute left-1/2 top-1/2 size-[max(100%,2.75rem)] -translate-x-1/2 -translate-y-1/2 [@media(pointer:fine)]:hidden"
                                            aria-hidden="true"></span>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="h-5 w-5">
                                            <path
                                                d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0a1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                        <span
                                            class="absolute top-full left-1/2 -translate-x-1/2 mt-2 px-2 py-1 bg-white text-zinc-800 text-xs rounded shadow-md opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 whitespace-nowrap border border-zinc-100">View</span>
                                    </button>
                                </div>
                                <div class="flex items-center">
                                    <button data-modal-target="delete-module-modal"
                                        class="inline-flex items-center gap-x-1.5 rounded-md px-2 py-1 text-sm/5 font-medium sm:text-xs/5 forced-colors:outline bg-red-400/5 text-red-500 group-data-[hover]:bg-red-400/30 relative group"
                                        data-headlessui-state=""><span
                                            class="absolute left-1/2 top-1/2 size-[max(100%,2.75rem)] -translate-x-1/2 -translate-y-1/2 [@media(pointer:fine)]:hidden"
                                            aria-hidden="true"></span>
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="h-5 w-5">
                                            <path d="M3 6h18" />
                                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                            <line x1="10" x2="10" y1="11" y2="17" />
                                            <line x1="14" x2="14" y1="11" y2="17" />
                                        </svg>
                                        <span
                                            class="absolute top-full left-1/2 -translate-x-1/2 mt-2 px-2 py-1 bg-white text-zinc-800 text-xs rounded shadow-md opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 whitespace-nowrap border border-zinc-100">Delete</span>
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>


                    <!-- Edit Module Modal -->
                    <x-modal name="edit-module-{{$module->id}}" :show="$errors->userDeletion->isNotEmpty()" focusable>
                        <!-- Error Modal -->
                        <input type="text" id="updateModuleId" value="update-error-modal-{{$module->id}}"
                            class="hidden">
                        <x-modal name="update-error-modal-{{$module->id}}" focusable>
                            <div class="p-6 items-center text-center justify-end">
                                <div class="flex flex-col items-center text-center justify-end">
                                    <div
                                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100">
                                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                                        </svg>
                                    </div>
                                    <h3 class="mt-4 text-lg font-medium text-gray-900">Invalid File Type</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">Please upload a PDF file only.</p>
                                    </div>
                                    <div class="mt-6">
                                        <x-secondary-button x-on:click="$dispatch('close')">
                                            Close
                                        </x-secondary-button>
                                    </div>
                                </div>
                            </div>
                        </x-modal>
                        <form enctype="multipart/form-data" method="POST"
                            action="{{ route('admin.module.edit', ['module' => $module->id]) }}"
                            id="updateModuleForm-{{$module->id}}">
                            @csrf
                            @method('PUT')
                            <div class="p-5">
                                <h2 class="text-lg font-medium mb-4 text-gray-900">
                                    Edit module
                                </h2>
                                <!-- Upload File -->
                                <div class="flex items-center justify-center w-full">
                                    <label for="update-dropzone-file-{{$module->id}}"
                                        id="update-dropzone-{{$module->id}}"
                                        class="transition-colors duration-300 ease-in-out flex flex-col items-center justify-center w-full h-64 border-2 border-dashed border-gray-200 rounded-lg cursor-pointer bg-white hover:bg-blue-50 relative">
                                        <div
                                            class="flex flex-col items-center justify-center pt-5 pb-6 pointer-events-none">
                                            <svg class="w-8 h-8 mb-4 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 20 16">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                            </svg>
                                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                                <span class="font-semibold">Click to upload</span> or drag and drop
                                            </p>
                                            <p class="text-xs text-gray-400">PDF (10mb)</p>
                                        </div>

                                        <input id="update-dropzone-file-{{$module->id}}" type="file" class="hidden"
                                            name="file" accept=".pdf" />
                                    </label>
                                </div>

                                <!-- File preview UI -->
                                <div id="update-file-preview-{{$module->id}}" class="mt-4 hidden">
                                    <div
                                        class="flex items-center justify-between bg-blue-50 border border-blue-200 rounded-lg p-4 text-sm text-slate-800">
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="flex-shrink-0 flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <span id="update-selected-file-name-{{$module->id}}"
                                                class="truncate max-w-[250px]">No file
                                                selected</span>
                                        </div>
                                        <button type="button" onclick="removeUpdateFile{{$module->id}}()"
                                            class="text-xs text-red-500 hover:underline">Remove</button>
                                    </div>
                                </div>

                                <!-- Input Fields -->
                                <p class="mt-1 text-sm text-gray-600">
                                    {{ __() }}
                                </p>

                                <div class="grid grid-cols-2 gap-6 my-6">
                                    <!-- Course code Input Field -->
                                    <div id="input" class="relative">
                                        <input type="text" id="course_code" required
                                            class="block w-full text-sm h-[50px] px-4 text-slate-900 bg-white rounded-[8px] border border-gray-200 appearance-none focus:border-transparent focus:outline focus:outline-2 focus:outline-primary focus:ring-0 hover:border-brand-500-secondary- peer invalid:border-error-500 invalid:focus:border-error-500 overflow-ellipsis overflow-hidden text-nowrap pr-[48px]"
                                            placeholder="Course code" value="{{ $module['course_code'] }}"
                                            name="course_code" />
                                        <label for="course_code"
                                            class="peer-placeholder-shown:-z-10 peer-focus:z-10 absolute text-[14px] leading-[150%] text-primary peer-focus:text-primary peer-invalid:text-error-500 focus:invalid:text-error-500 duration-300 transform -translate-y-[1.2rem] scale-75 top-2 z-10 origin-[0] bg-white disabled:bg-gray-50-background- px-2 peer-focus:px-2 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-[1.2rem] rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">
                                            Course code
                                        </label>
                                    </div>
                                    <!-- Module title Input Field -->
                                    <div id="input" class="relative">
                                        <input type="text" id="title" required
                                            class="block w-full text-sm h-[50px] px-4 text-slate-900 bg-white rounded-[8px] border border-gray-200 appearance-none focus:border-transparent focus:outline focus:outline-2 focus:outline-primary focus:ring-0 hover:border-brand-500-secondary- peer invalid:border-error-500 invalid:focus:border-error-500 overflow-ellipsis overflow-hidden text-nowrap pr-[48px]"
                                            placeholder="Title" value="{{ $module['title'] }}" name="title" />
                                        <label for="title"
                                            class="peer-placeholder-shown:-z-10 peer-focus:z-10 absolute text-[14px] leading-[150%] text-primary peer-focus:text-primary peer-invalid:text-error-500 focus:invalid:text-error-500 duration-300 transform -translate-y-[1.2rem] scale-75 top-2 z-10 origin-[0] bg-white disabled:bg-gray-50-background- px-2 peer-focus:px-2 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-[1.2rem] rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">
                                            Title
                                        </label>
                                    </div>
                                </div>
                                <!-- Action Buttons -->
                                <div class="mt-6 flex justify-end">
                                    <x-secondary-button type="button"
                                        x-on:click="$dispatch('close'); document.getElementById('uploadModuleForm').reset(); removeFile();">
                                        Cancel
                                    </x-secondary-button>

                                    <x-primary-button type="submit" class="ms-3">
                                        Save changes
                                    </x-primary-button>
                                </div>
                            </div>
                        </form>
                    </x-modal>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                            No modules posted yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <!-- Pagination -->
            @if($modules->hasPages())
            <div class="mt-6 flex items-center justify-center">
                <div class="flex space-x-2">
                    @if ($modules->onFirstPage())
                    <span
                        class="flex px-2 py-1 rounded border border-zinc-400 text-zinc-400 cursor-not-allowed items-center justify-center text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="w-5 h-5">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M15 6l-6 6l6 6" />
                        </svg>
                    </span>
                    @else
                    <a href="{{ $modules->previousPageUrl() }}"
                        class="flex px-2 py-1 rounded border border-zinc-900 text-zinc-900 items-center justify-center text-center hover:bg-zinc-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="w-5 h-5">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M15 6l-6 6l6 6" />
                        </svg>
                    </a>
                    @endif

                    @php
                    // Show a limited number of pages around the current page
                    $currentPage = $modules->currentPage();
                    $lastPage = $modules->lastPage();
                    $start = max($currentPage - 1, 1);
                    $end = min($currentPage + 1, $lastPage);
                    $pages = [];

                    // Always show first page if not in range
                    if ($start > 1) {
                    $pages[] = 1;
                    if ($start > 2) {
                    $pages[] = '...';
                    }
                    }

                    // Add pages in range
                    for ($i = $start; $i <= $end; $i++) { $pages[]=$i; } // Always show last page if not in range if
                        ($end < $lastPage) { if ($end < $lastPage - 1) { $pages[]='...' ; } $pages[]=$lastPage; }
                        @endphp @foreach ($pages as $page) @if ($page=='...' ) <span
                        class="w-10 h-10 flex items-center justify-center rounded text-zinc-900">...</span>
                        @elseif ($page == $modules->currentPage())
                        <span
                            class="w-10 h-10 flex items-center justify-center rounded bg-zinc-900 text-white font-semibold">
                            {{ $page }}
                        </span>
                        @else
                        <a href="{{ $modules->url($page) }}"
                            class="w-10 h-10 flex items-center justify-center rounded border border-zinc-900 text-zinc-900 hover:text-white hover:bg-zinc-900 transition">
                            {{ $page }}
                        </a>
                        @endif
                        @endforeach

                        @if ($modules->hasMorePages())
                        <a href="{{ $modules->nextPageUrl() }}"
                            class="flex px-2 py-1 rounded border border-zinc-900 text-zinc-900 items-center justify-center text-center hover:bg-zinc-50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="w-5 h-5">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M9 6l6 6l-6 6" />
                            </svg>
                        </a>
                        @else
                        <span
                            class="flex px-2 py-1 rounded border border-zinc-400 text-zinc-400 cursor-not-allowed items-center justify-center text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="w-5 h-5">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M9 6l6 6l-6 6" />
                            </svg>
                        </span>
                        @endif
                </div>
            </div>
            @endif
        </div> --}}
    </main>
    <!-- Delete Module Modal -->
    <x-my-modal id="delete-module-modal" title="Delete Module Confirmation" iconType="warning">
        <p class="text-sm text-gray-500">
            Are you sure you want to delete the "<span id="delete-module-title" class="font-bold"></span>(<span
                id="delete-module-course-code" class="font-bold"></span>)" module? All related data will be permanently
            removed.
            This action cannot be undone.
        </p>

        <x-slot name="footer">
            <form id="delete-module-form" method="POST">
                @csrf
                @method('DELETE')
                <button data-modal-close type="button"
                    class="mt-3 inline-flex w-full justify-center rounded-md bg-gray-100 px-3 py-2 text-sm font-semibold text-gray-900 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                    Cancel
                </button>
                <button type="submit"
                    class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white hover:bg-red-500 sm:ml-3 sm:w-auto">
                    Delete Module
                </button>
            </form>
        </x-slot>
    </x-my-modal>
    <!-- Upload module JS script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Only run this code if the dropzone element exists
            const dropzone = document.getElementById("dropzone");
            const fileInput = document.getElementById("dropzone-file");
            const preview = document.getElementById("file-preview");
            const fileNameSpan = document.getElementById("selected-file-name");

            // Only attach event listeners if all elements exist
            if (dropzone && fileInput && preview && fileNameSpan) {
                dropzone.addEventListener("dragenter", (e) => {
                    e.preventDefault();
                    dropzone.classList.remove("border-gray-200");
                    dropzone.classList.add("border-blue-600");
                });

                dropzone.addEventListener("dragover", (e) => {
                    e.preventDefault();
                    dropzone.classList.add("border-blue-600");
                });

                dropzone.addEventListener("dragleave", () => {
                    dropzone.classList.remove("border-blue-600");
                    dropzone.classList.add("border-gray-200");
                });

                dropzone.addEventListener("drop", (e) => {
                    e.preventDefault();
                    dropzone.classList.remove("border-blue-600");
                    dropzone.classList.add("border-gray-200");

                    const file = e.dataTransfer.files[0];
                    if (file && file.type === "application/pdf") {
                        fileInput.files = e.dataTransfer.files;

                        // Show preview
                        fileNameSpan.textContent = file.name;
                        preview.classList.remove("hidden");

                        console.log("PDF dropped:", file.name);
                    } else {
                        // Show error modal instead of alert
                        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'error-modal' }));
                    }
                });

                // Optional: handle manual file selection too
                fileInput.addEventListener("change", function () {
                    const file = this.files[0];
                    if (file && file.type === "application/pdf") {
                        fileNameSpan.textContent = file.name;
                        preview.classList.remove("hidden");
                    } else {
                        // Show error modal instead of alert
                        window.dispatchEvent(new CustomEvent('open-modal', { detail: 'error-modal' }));
                        // Reset the file input
                        this.value = "";
                    }
                });
            }

            // Function to remove file
            window.removeFile = function () {
                if (fileInput) {
                    fileInput.value = "";
                    preview.classList.add("hidden");
                    fileNameSpan.textContent = "No file selected";
                }
            };

            // Get update elements - making sure they exist for each module
            const modules = document.querySelectorAll("[id^='updateModuleForm']");

            modules.forEach(moduleForm => {
                const moduleId = moduleForm.getAttribute('id').replace('updateModuleForm-', '');
                const updateDropzone = document.getElementById(`update-dropzone-${moduleId}`);
                const updateFileInput = document.getElementById(`update-dropzone-file-${moduleId}`);
                const updatePreview = document.getElementById(`update-file-preview-${moduleId}`);
                const updateFileNameSpan = document.getElementById(`update-selected-file-name-${moduleId}`);

                if (updateDropzone && updateFileInput && updatePreview && updateFileNameSpan) {
                    updateDropzone.addEventListener("dragenter", (e) => {
                        e.preventDefault();
                        updateDropzone.classList.remove("border-gray-200");
                        updateDropzone.classList.add("border-blue-600");
                    });

                    updateDropzone.addEventListener("dragover", (e) => {
                        e.preventDefault();
                        updateDropzone.classList.add("border-blue-600");
                    });

                    updateDropzone.addEventListener("dragleave", () => {
                        updateDropzone.classList.remove("border-blue-600");
                        updateDropzone.classList.add("border-gray-200");
                    });

                    updateDropzone.addEventListener("drop", (e) => {
                        e.preventDefault();
                        updateDropzone.classList.remove("border-blue-600");
                        updateDropzone.classList.add("border-gray-200");

                        const file = e.dataTransfer.files[0];
                        if (file && file.type === "application/pdf") {
                            updateFileInput.files = e.dataTransfer.files;

                            // Show preview
                            updateFileNameSpan.textContent = file.name;
                            updatePreview.classList.remove("hidden");

                            console.log("PDF dropped:", file.name);
                        } else {
                            // Show error modal instead of alert
                            window.dispatchEvent(new CustomEvent('open-modal', { detail: `update-error-modal-${moduleId}` }));
                        }
                    });

                    // Handle manual file selection
                    updateFileInput.addEventListener("change", function () {
                        const file = this.files[0];
                        if (file && file.type === "application/pdf") {
                            updateFileNameSpan.textContent = file.name;
                            updatePreview.classList.remove("hidden");
                        } else {
                            // Show error modal instead of alert
                            window.dispatchEvent(new CustomEvent('open-modal', { detail: `update-error-modal-${moduleId}` }));
                            // Reset the file input
                            this.value = "";
                        }
                    });

                    // Create removeUpdateFile function for this specific module
                    window[`removeUpdateFile${moduleId}`] = function () {
                        updateFileInput.value = "";
                        updatePreview.classList.add("hidden");
                        updateFileNameSpan.textContent = "No file selected";
                    };
                }
            });

            // Handle form validation
            const uploadModuleForm = document.getElementById('uploadModuleForm');
            if (uploadModuleForm) {
                uploadModuleForm.addEventListener('submit', function (e) {
                    // Get all required fields
                    const requiredFields = this.querySelectorAll('[required]');
                    let isValid = true;

                    // Check each required field
                    requiredFields.forEach(field => {
                        if (!field.value) {
                            isValid = false;
                            // Highlight empty fields
                            field.classList.add('border-error-500', 'focus:border-error-500');
                            const label = this.querySelector(`label[for="${field.id}"]`);
                            if (label) label.classList.add('text-error-500');
                        }
                    });

                    // Check if PDF is uploaded
                    const fileInput = document.getElementById('dropzone-file');
                    if (fileInput && !fileInput.files.length) {
                        isValid = false;
                        const dropzone = document.getElementById('dropzone');
                        if (dropzone) {
                            dropzone.classList.add('border-error-500');
                        }
                    }

                    // If validation fails, show modal and prevent submission
                    if (!isValid) {
                        e.preventDefault();

                        // Create and show validation error modal
                        const modalHtml = `
                            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-data="{ show: true }" x-show="show">
                                <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full bg-red-100">
                                            <svg class="w-6 h-6 text-red-600" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900">Missing Information</h3>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-6">Please fill in all required fields and upload a PDF file.</p>
                                    <div class="flex justify-end">
                                        <button @click="show = false" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            OK
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;

                        // Insert modal into DOM
                        const modalContainer = document.createElement('div');
                        modalContainer.innerHTML = modalHtml;
                        document.body.appendChild(modalContainer);

                        // Remove modal after animation
                        setTimeout(() => {
                            if (modalContainer) {
                                modalContainer.remove();
                            }
                        }, 5000);
                    }
                });
            }

            // Reset error styles when user starts typing/selecting
            document.querySelectorAll('[required]').forEach(field => {
                field.addEventListener('input', function () {
                    this.classList.remove('border-error-500', 'focus:border-error-500');
                    const label = document.querySelector(`label[for="${this.id}"]`);
                    if (label) label.classList.remove('text-error-500');
                });

                if (field.tagName === 'SELECT') {
                    field.addEventListener('change', function () {
                        this.classList.remove('border-error-500', 'focus:border-error-500');
                        const label = document.querySelector(`label[for="${this.id}"]`);
                        if (label) label.classList.remove('text-error-500');
                    });
                }
            });

            // Reset dropzone error style when file is selected
            const dropzoneFile = document.getElementById('dropzone-file');
            if (dropzoneFile) {
                dropzoneFile.addEventListener('change', function () {
                    if (this.files.length) {
                        const dropzone = document.getElementById('dropzone');
                        if (dropzone) {
                            dropzone.classList.remove('border-error-500');
                        }
                    }
                });
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-modal-target="delete-module-modal"]').forEach(button => {
                button.addEventListener('click', function () {
                    const row = this.closest('tr');
                    const moduleId = row.dataset.moduleId;
                    const moduleTitle = row.querySelector('td:nth-child(2)').textContent.trim();
                    const moduleCourseCode = row.querySelector('td:nth-child(1)').textContent.trim();

                    document.getElementById('delete-module-title').textContent = moduleTitle;
                    document.getElementById('delete-module-course-code').textContent = moduleCourseCode;

                    const deleteForm = document.getElementById('delete-module-form');
                    deleteForm.action = `/admin/delete-module/${moduleId}?redirect_to=/admin/modules`;
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Delete modal functionality
            document.querySelectorAll('[data-modal-target="delete-module-modal"]').forEach(button => {
                button.addEventListener('click', function () {
                    const row = this.closest('tr');
                    const moduleId = row.dataset.moduleId;
                    const moduleName = row.dataset.moduleName;

                    document.getElementById('delete-module-name').textContent = moduleName;
                    document.getElementById('module-id-input').value = moduleId;
                });
            });

            // Enhanced search functionality
            const searchInput = document.getElementById('moduleSearch');
            const clearSearchBtn = document.getElementById('clearSearch');
            const searchStatus = document.getElementById('searchStatus');
            const searchResultsText = document.getElementById('searchResultsText');
            const moduleRows = document.querySelectorAll('.module-row');

            function performSearch() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                let visibleCount = 0;

                moduleRows.forEach(row => {
                    const courseTitle = row.dataset.moduleTitle.toLowerCase();
                    const courseCode = row.dataset.moduleCourseCode.toLowerCase();
                    const moduleUploaderName = row.dataset.moduleUploaderName.toLowerCase();
                    // const id = row.querySelector('td:first-child').textContent.toLowerCase();

                    if (courseTitle.includes(searchTerm) || courseCode.includes(searchTerm) || moduleUploaderName.includes(searchTerm)) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Update search status
                if (searchTerm) {
                    searchStatus.classList.remove('hidden');
                    searchResultsText.textContent = `Found ${visibleCount} module${visibleCount !== 1 ? 's' : ''} matching "${searchTerm}"`;
                } else {
                    searchStatus.classList.add('hidden');
                }

                // Show/hide clear button
                clearSearchBtn.style.opacity = searchTerm ? '1' : '0';
                clearSearchBtn.style.pointerEvents = searchTerm ? 'auto' : 'none';
            }

            searchInput.addEventListener('input', performSearch);
            searchInput.addEventListener('keyup', function (e) {
                if (e.key === 'Escape') {
                    searchInput.value = '';
                    performSearch();
                    searchInput.blur();
                }
            });

            clearSearchBtn.addEventListener('click', function () {
                searchInput.value = '';
                performSearch();
                searchInput.focus();
            });
        });


    </script>
</x-admin-layout>
