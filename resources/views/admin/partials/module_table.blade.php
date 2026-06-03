            <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm">
                <table class="min-w-full text-left text-sm/6 text-zinc-950" id="table">
                    <thead class="border-b border-zinc-200">
                        <tr>
                            <th class="px-4 py-3 font-semibold text-zinc-700">Course Code</th>
                            <th class="px-4 py-3 font-semibold text-zinc-700">Module Title</th>

                            <th class="px-4 py-3 font-semibold text-zinc-700">Views</th>
                            <th class="px-4 py-3 font-semibold text-zinc-700">Teacher</th>
                            <th class="px-4 py-3 font-semibold text-zinc-700">Status</th>
                            <th class="px-4 py-3 font-semibold text-zinc-700">Date Posted</th>
                            <th class="px-4 py-3 font-semibold text-zinc-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($modules as $module)
                        <!-- Custom PDF Viewer Modal with Blur Effect -->
                        <div x-data="{ showModuleModal: false, showDownloadModal: false }" x-show="showModuleModal"
                            x-on:open-modal.window="if ($event.detail === 'view-module-{{$module->id}}') { showModuleModal = true; document.body.classList.add('overflow-hidden', 'backdrop-blur') }"
                            x-on:close.stop="showDownloadModal = false; showModuleModal = false; document.body.classList.remove('overflow-hidden', 'backdrop-blur')"
                            x-on:keydown.escape.window="showDownloadModal = false; showModuleModal = false; document.body.classList.remove('overflow-hidden', 'backdrop-blur')"
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
                                            <button type="button" x-on:click="showDownloadModal = true"
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
                                            </button>
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

                            <!-- Download Confirmation Modal -->
                            <div x-show="showDownloadModal"
                                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class="fixed inset-0 z-[10000] flex items-center justify-center p-4" style="display: none;" x-cloak>
                                
                                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" x-on:click="showDownloadModal = false"></div>
                                
                                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all w-full max-w-lg mx-auto z-10"
                                    x-transition:enter="ease-out duration-300"
                                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                    x-transition:leave="ease-in duration-200"
                                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                                    
                                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                        <div class="sm:flex sm:items-start">
                                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-zinc-100 sm:mx-0 sm:h-10 sm:w-10">
                                                <svg class="h-6 w-6 text-zinc-900" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
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
                        <tr class="border-b border-zinc-100 hover:bg-zinc-50 transition-colors duration-150 module-row"
                            data-module-id="{{ $module->id}}" data-module-title="{{ $module->title }}"
                            data-module-course-code="{{ $module->course_code }}"
                            data-module-uploader-name="{{ $module->user?->name ?? 'N/A' }}">
                            <td class="px-4 py-3 font-medium text-zinc-900">{{ $module->course_code }}</td>
                            <td class="px-4 py-3 text-zinc-600">{{ $module->title }}</td>

                            <td class="px-4 py-3 text-zinc-600">{{ $module->number_of_views }}</td>
                            <td
                                class="relative px-4 first:pl-[var(--gutter,theme(spacing.2))] last:pr-[var(--gutter,theme(spacing.2))] border-b border-zinc-950/5 dark:border-white/5 py-4 sm:first:pl-1 sm:last:pr-1">
                                <div class="flex gap-2 items-center">
                                    <div class="w-8 h-8 rounded-full bg-gray-200 overflow-hidden flex-shrink-0">
                                        <img src="{{ ($module->user && $module->user->profile_picture) ? asset('images/' . $module->user->profile_picture) : asset('images/default_profile.png') }}"
                                            alt="Profile Picture" class="w-full h-full object-cover">
                                    </div>
                                    {{ $module->user?->name ?? 'Deleted Teacher' }}
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if($module->status === 'published')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-700 border border-emerald-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Published
                                    </span>
                                @elseif($module->status === 'rejected')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-500/10 text-rose-700 border border-rose-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Rejected
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-700 border border-amber-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-zinc-600">{{ $module->created_at->format('M d, Y') }}</td>
                            <td class="py-3">
                                <div class="flex gap-2 items-center">
                                    @if($module->status === 'pending')
                                        <form method="POST" action="{{ route('admin.module.update-status', $module->id) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="published">
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 rounded-md shadow-sm transition-all duration-200 transform hover:scale-[1.02]">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.module.update-status', $module->id) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold text-white bg-rose-600 hover:bg-rose-700 active:bg-rose-800 rounded-md shadow-sm transition-all duration-200 transform hover:scale-[1.02]">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Reject
                                            </button>
                                        </form>
                                    @elseif($module->status === 'rejected')
                                        <form method="POST" action="{{ route('admin.module.update-status', $module->id) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="published">
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold text-zinc-700 bg-white hover:bg-zinc-50 active:bg-zinc-100 border border-zinc-300 rounded-md shadow-sm transition-all duration-200">
                                                <svg class="w-3.5 h-3.5 mr-1 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Approve
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.module.update-status', $module->id) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold text-zinc-700 bg-white hover:bg-zinc-50 active:bg-zinc-100 border border-zinc-300 rounded-md shadow-sm transition-all duration-200">
                                                <svg class="w-3.5 h-3.5 mr-1 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Reject
                                            </button>
                                        </form>
                                    @endif

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
            <x-pagination :paginator="$modules" />
