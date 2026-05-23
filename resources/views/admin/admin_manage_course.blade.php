<x-admin-layout :title="'Degree Programs'">
    <main class="flex-1 max-h-full p-5 lg:mt-0 mt-20">
        <div class="flex items-start justify-between">
            <h1 class="text-2xl/8 font-semibold text-zinc-950 sm:text-xl/8">
                Degree Programs
            </h1>
            <x-my-secondary-button onclick="window.location.href='{{ route('admin.degree-program.create') }}'">
                Create degree program
            </x-my-secondary-button>
        </div>

        <!-- Search Controls -->
        <div class="mt-8">
            <x-search-bar id="degreeProgramSearch" placeholder="Search degree programs..." />
        </div>

        <!-- Module Table -->
        <div class="mt-6 inline-block min-w-full align-middle sm:px-[--gutter]">
            <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm">
                <table class="min-w-full text-left text-sm/6 text-zinc-950" id="table">
                    <thead class="border-b border-zinc-200">
                        <tr>
                            {{-- <th class="px-4 py-3 font-semibold text-zinc-700">Id</th> --}}
                            <th class="px-4 py-3 font-semibold text-zinc-700">Degree Program</th>
                            <th class="px-4 py-3 font-semibold text-zinc-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($courses as $course)
                            <!-- Delete Degree Program Modal -->
                            <x-my-modal id="delete-course-modal" title="Delete Degree Program Confirmation"
                                iconType="warning">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to delete "<span id="delete-course-name"
                                        class="font-bold"></span>"?
                                    This action cannot be undone.
                                </p>

                                <x-slot name="footer">
                                     <form id="delete-course-form" method="POST"
                                        action="{{ route('admin.degree-program.delete', $course) }}">
                                        <form id="delete-course-form" method="POST" action="">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="course_id" id="course-id-input">
                                            <button type="submit"
                                                class="inline-flex w-full justify-center rounded-md bg-primary px-3 py-2 text-sm font-semibold text-white hover:bg-primary/90 sm:ml-3 sm:w-auto">
                                                Yes, delete
                                            </button>
                                            <button data-modal-close type="button"
                                                class="mt-3 inline-flex w-full justify-center rounded-md bg-gray-100 px-3 py-2 text-sm font-semibold text-gray-900 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                                Cancel
                                            </button>
                                        </form>
                                </x-slot>
                            </x-my-modal>
                            <tr class="border-b border-zinc-100 hover:bg-zinc-50 transition-colors duration-150 course-row"
                                data-course-id="{{ $course->id }}" data-course-name="{{ $course->course_name }}">
                                {{-- <td class="px-4 py-3 text-zinc-900">{{ $course->id }}</td> --}}
                                <td class="px-4 py-3 text-zinc-900">{{ $course->course_name }}</td>
                                <td class="py-3">
                                    <div class="flex gap-2 items-center">
                                        <a href="{{ route('admin.degree-program.edit', $course) }}"
                                            class="inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-sm font-medium text-zinc-900 hover:bg-zinc-900/5 transition-colors duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </a>
                                    <button data-modal-target="delete-course-modal"
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
                                <td colspan="2" class="px-4 py-8 text-center text-zinc-500">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-12 h-12 text-zinc-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <p class="text-lg font-medium">No courses yet.</p>
                                        <p class="text-sm">Get started by creating your first course.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($courses->hasPages())
                <div class="mt-6 flex items-center justify-center">
                    {{-- <div class="text-sm text-gray-500">
                        Showing {{ $courses->firstItem() }} to {{ $courses->lastItem() }} of {{
                        $courses->total() }}
                        results
                    </div> --}}
                    <div class="flex space-x-2">
                        @if ($courses->onFirstPage())
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
                            <a href="{{ $courses->previousPageUrl() }}"
                                class="flex px-2 py-1 rounded border border-zinc-900 text-zinc-900 items-center justify-center text-center hover:bg-zinc-50 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="w-5 h-5">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M15 6l-6 6l6 6" />
                                </svg>
                            </a>
                        @endif

                        @foreach ($courses->getUrlRange(1, $courses->lastPage()) as $page => $url)
                            @if ($page == $courses->currentPage())
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

                        @if ($courses->hasMorePages())
                            <a href="{{ $courses->nextPageUrl() }}"
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
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Delete modal functionality
            document.querySelectorAll('[data-modal-target="delete-course-modal"]').forEach(button => {
                button.addEventListener('click', function () {
                    const row = this.closest('tr');
                    const courseId = row.dataset.courseId;
                    const courseName = row.dataset.courseName;

                    document.getElementById('delete-course-name').textContent = courseName;
                    document.getElementById('course-id-input').value = courseId;
                });
            });

            // Enhanced search functionality
            const searchInput = document.getElementById('degreeProgramSearch');
            const searchStatus = document.getElementById('degreeProgramSearch-status');
            const searchResultsText = document.getElementById('degreeProgramSearch-results-text');
            const courseRows = document.querySelectorAll('.course-row');

            function performSearch() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                let visibleCount = 0;

                courseRows.forEach(row => {
                    const courseName = row.dataset.courseName.toLowerCase();
                    const courseId = row.dataset.courseId.toLowerCase();

                    if (courseId.includes(searchTerm) || courseName.includes(searchTerm)) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (searchTerm) {
                    searchStatus.classList.remove('hidden');
                    searchResultsText.textContent = `Found ${visibleCount} course${visibleCount !== 1 ? 's' : ''} matching "${searchTerm}"`;
                } else {
                    searchStatus.classList.add('hidden');
                }
            }

            searchInput.addEventListener('input', performSearch);
        });


    </script>

</x-admin-layout>
