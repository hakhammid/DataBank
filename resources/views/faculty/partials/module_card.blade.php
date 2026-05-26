<!-- Delete Module Modal -->
<div x-data="{ showDeleteModal: false }" x-show="showDeleteModal"
    x-on:open-modal.window="if ($event.detail === 'delete-module-modal-{{$module->id}}') { showDeleteModal = true }"
    x-on:close.stop="showDeleteModal = false" x-on:keydown.escape.window="showDeleteModal = false"
    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50" style="display: none;">

    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all w-full max-w-lg mx-auto"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-base font-semibold leading-6 text-gray-900">Delete Module Confirmation</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to delete "<span class="font-bold">{{ $module->title }}</span>
                                    (<span class="font-bold">{{ $module->course_code }}</span>)"? This action cannot be
                                    undone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <form method="POST" action="{{ route('delete-module', ['module' => $module->id]) }}"
                        class="flex flex-col-reverse sm:flex-row-reverse gap-2 sm:gap-0">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="redirect_to" value="{{ request()->fullUrl() }}">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
                            Delete Module
                        </button>
                        <button type="button" x-on:click="showDeleteModal = false"
                            class="w-full inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                            Cancel
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Module Modal -->
<div x-data="{ showModal: false }" x-show="showModal"
    x-on:open-modal.window="if ($event.detail === 'view-module-{{$module->id}}') {
        showModal = true;
        document.body.classList.add('overflow-hidden', 'backdrop-blur')
    }"
    x-on:close.stop="showModal = false; document.body.classList.remove('overflow-hidden', 'backdrop-blur')"
    x-on:keydown.escape.window="showModal = false; document.body.classList.remove('overflow-hidden', 'backdrop-blur')"
    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[9999]" style="display: none;" x-cloak>

    <!-- Blurred Overlay - Fixed, non-scrollable -->
    <div class="fixed inset-0 bg-black/70 backdrop-blur-lg transition-opacity"
        x-on:click="showModal = false; document.body.classList.remove('overflow-hidden', 'backdrop-blur')">
    </div>

    <!-- Modal Container - Fixed, non-scrollable -->
    <div class="fixed inset-0 flex items-center justify-center">
        <!-- Modal Content - Only this inner div is scrollable -->
        <div class="relative w-full h-full max-w-[1800px] mx-auto flex flex-col p-4">
            <!-- Header - Fixed at top -->
            <div class="flex-shrink-0 bg-black/50 backdrop-blur-md rounded-xl p-4 mb-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center min-w-0">
                        <button x-on:click="showModal = false; document.body.classList.remove('overflow-hidden', 'backdrop-blur')"
                            class="mr-3 bg-white/20 hover:bg-white/30 rounded-full p-2 transition-all duration-200 focus:outline-none flex-shrink-0">
                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <h3 class="text-lg font-medium text-white truncate">
                            {{ $module->title }} ({{ $module->course_code }})
                        </h3>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" x-on:click="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'confirm-download-{{$module->id}}' }))"
                            class="bg-white/20 hover:bg-white/30 rounded-full p-2 transition-all duration-200 focus:outline-none relative group flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" class="h-5 w-5 text-white">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                <polyline points="7 10 12 15 17 10" />
                                <line x1="12" x2="12" y1="15" y2="3" />
                            </svg>
                            <span class="absolute -top-10 left-1/2 -translate-x-1/2 px-2 py-1 bg-white text-zinc-800 text-xs rounded shadow-md opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 whitespace-nowrap border border-zinc-100">
                                Download
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- PDF Viewer Container - Scrollable content area -->
            <div class="flex-1 relative min-h-0 rounded-xl overflow-hidden">
                <iframe src="{{ asset('files/' . $module['file']) }}#toolbar=0&navpanes=0&view=FitH"
                    class="absolute inset-0 w-full h-full"
                    type="application/pdf"
                    id="pdf-viewer-{{$module->id}}"
                    allowfullscreen>
                </iframe>
            </div>
        </div>
    </div>
</div>

<!-- Download Module Modal -->
<div x-data="{ showDownloadModal: false }" x-show="showDownloadModal"
    x-on:open-modal.window="if ($event.detail === 'confirm-download-{{$module->id}}') { showDownloadModal = true }"
    x-on:close.stop="showDownloadModal = false" x-on:keydown.escape.window="showDownloadModal = false"
    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[10000]" style="display: none;" x-cloak>

    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" x-on:click="showDownloadModal = false"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all w-full max-w-lg mx-auto"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-zinc-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-zinc-900" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-base font-semibold leading-6 text-gray-900">Confirm Download</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to download "<span class="font-bold">{{ $module->title }}</span>"?
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                    <a href="{{ asset('files/' . $module->file) }}" download="{{ $module->title }}.pdf"
                        x-on:click="showDownloadModal = false"
                        class="w-full inline-flex justify-center rounded-md bg-zinc-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 sm:ml-3 sm:w-auto">
                        Download
                    </a>
                    <button type="button" x-on:click="showDownloadModal = false"
                        class="w-full inline-flex justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Module Card -->
<div class="group module-card">
    <!-- Entire card is clickable to view module -->
    <div onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'view-module-{{$module->id}}' }))"
        class="pdf-card relative xl:h-[25rem] md:h-[20rem] h-[20rem] overflow-hidden rounded-xl bg-secondary transition-all duration-200 hover:shadow-sm flex flex-col cursor-pointer">

        <div class="absolute inset-0 duration-300 z-10">
            <iframe src="{{ asset('files/' . $module->file) }}#toolbar=0&navpanes=0&scrollbar=0&view=FitH"
                class="w-full h-full scale-110 rounded-xl" type="application/pdf"
                style="pointer-events: none; border: none; background-color: transparent; overflow: hidden;">
            </iframe>
        </div>

        <div
            class="absolute bottom-0 h-[10rem] bg-gradient-to-t from-black/30 via-black/25 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
        </div>

        <div class="absolute top-3 left-4 z-20">
            @if($module->status === 'published')
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-600/90 text-white shadow-sm backdrop-blur-sm">
                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                    Published
                </span>
            @elseif($module->status === 'rejected')
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-600/90 text-white shadow-sm backdrop-blur-sm">
                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                    Rejected
                </span>
            @else
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-500/95 text-white shadow-sm backdrop-blur-sm">
                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                    Pending Approval
                </span>
            @endif
        </div>

        <div class="flex items-center justify-center">
            <div class="flex items-center justify-center">
                <!-- Actions dropdown with stopPropagation to prevent card click -->
                <div onclick="event.stopPropagation()"
                    class="absolute top-3 right-4 gap-1 flex flex-col z-20 p-2 rounded-full shadow-lg backdrop-blur-sm">
                    <div x-data="{ open: false }" class="relative">
                        <button x-on:click="open = !open" onclick="event.stopPropagation()"
                            class="flex items-center gap-2 text-white group focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-zinc-950" fill="none"
                                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="1" />
                                <circle cx="12" cy="5" r="1" />
                                <circle cx="12" cy="19" r="1" />
                            </svg>
                        </button>
                        <div x-show="open" x-cloak
                            @click.away="if (!event.target.closest('#showDeleteModal')) { open = false }"
                            class="absolute right-0 mt-3 w-48">
                            <div
                                class="absolute right-0 w-56 rounded-lg shadow-lg bg-white ring-1 ring-zinc-100 z-50 block">
                                <div class="py-1">
                                    <a href="{{ route('faculty.module.edit', $module->id) }}?from_course={{ request('course_id') }}"
                                        class="w-full text-zinc-900 text-left flex items-center gap-2 px-4 py-1 text-sm hover:bg-secondary/40">
                                        <div class="rounded-full bg-secondary p-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-zinc-900"
                                                fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                <path
                                                    d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z" />
                                            </svg>
                                        </div>
                                        Edit
                                    </a>
                                    <!-- Optional: Keep View button in dropdown for accessibility -->
                                    <button type="button"
                                        onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'view-module-{{$module->id}}' }))"
                                        class="w-full text-zinc-900 text-left flex items-center gap-2 px-4 py-1 text-sm hover:bg-secondary/40">
                                        <div class="rounded-full bg-secondary p-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-zinc-900"
                                                fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path
                                                    d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </div>
                                        View
                                    </button>
                                    <button type="button"
                                        onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'delete-module-modal-{{$module->id}}' }))"
                                        class="w-full text-danger text-left flex items-center gap-2 px-4 py-1 text-sm hover:bg-secondary/40">
                                        <div class="rounded-full bg-danger/5 p-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M3 6h18" />
                                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                <line x1="10" x2="10" y1="11" y2="17" />
                                                <line x1="14" x2="14" y1="11" y2="17" />
                                            </svg>
                                        </div>
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="hover-actions absolute bottom-3 gap-1 flex flex-col z-20 bg-black/60 p-4 rounded-xl shadow-lg backdrop-blur-sm">
                    <div class="flex items-center gap-2 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927a1 1 0 011.902 0l1.1 3.394a1 1 0 00.95.69h3.574a1 1 0 01.591 1.81l-2.89 2.103a1 1 0 00-.364 1.118l1.1 3.394a1 1 0 01-1.537 1.118L10 13.347l-2.975 2.109a1 1 0 01-1.537-1.118l1.1-3.394a1 1 0 00-.364-1.118L3.334 8.82a1 1 0 01.591-1.81h3.574a1 1 0 00.95-.69l1.1-3.394z" />
                        </svg>
                        <span class="text-sm font-semibold">{{ $module->isMajor ? 'Major Subject' : 'Minor Subject'
                            }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24">
                            <path d="m16 18 6-6-6-6" />
                            <path d="m8 6-6 6 6 6" />
                        </svg>
                        <span class="text-sm font-semibold">{{ $module->course_code }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Module info also clickable to view module -->
    <div onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'view-module-{{$module->id}}' }))"
        class="mt-auto pt-2 flex items-start justify-between gap-4 cursor-pointer">
        <div class="flex flex-col min-w-0">
            <h4 class="text-md font-medium text-zinc-900 truncate leading-snug">{{ $module->title }}</h4>
            <p class="text-sm text-zinc-600 truncate">{{ $module->course_code }}</p>
        </div>

    </div>
</div>
