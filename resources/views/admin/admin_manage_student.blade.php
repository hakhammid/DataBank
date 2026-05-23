<x-admin-layout :title="'All Students'">

    <main class="flex-1 max-h-full p-5 lg:mt-0 mt-20">
        <div class="flex items-start justify-between">
            <h1 class="text-2xl/8 font-semibold text-zinc-950 sm:text-xl/8">
                Students
            </h1>
            <x-my-secondary-button onclick="window.location.href='{{ route('admin.student.create') }}'">
                Create new student
            </x-my-secondary-button>
        </div>

        <!-- Search Controls -->
        <div class="mt-8">
            <x-search-bar id="studentSearch" placeholder="Search students..." />
        </div>

        <!-- Module Table -->
        <div class="mt-6 inline-block min-w-full align-middle sm:px-[--gutter]">
            <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm">
                <table class="min-w-full text-left text-sm/6 text-zinc-950" id="table">
                    <thead class="border-b border-zinc-200">
                        <tr>
                            <th class="px-4 py-3 font-semibold text-zinc-700">ID</th>
                                <th class="px-4 py-3 font-semibold text-zinc-700">Name</th>
                                <th class="px-4 py-3 font-semibold text-zinc-700">Email</th>
                                <th class="px-4 py-3 font-semibold text-zinc-700">Degree Program</th>
                            <th class="px-4 py-3 font-semibold text-zinc-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($students as $student)
                            <tr class="border-b border-zinc-100 hover:bg-zinc-50 transition-colors duration-150 student-row"
                                data-student-id="{{ $student->id }}" data-student-name="{{ $student->name }}"
                                data-student-email="{{ $student->email }}">
                                <td class="px-4 py-3 font-medium text-zinc-900">{{ $student->id_number }}</td>
                                <td
                                    class="relative px-4 first:pl-[var(--gutter,theme(spacing.2))] last:pr-[var(--gutter,theme(spacing.2))] border-b border-zinc-950/5 dark:border-white/5 py-4 sm:first:pl-1 sm:last:pr-1">
                                    <div class="flex gap-2 items-center">
                                        <div class="ml-2 w-8 h-8 rounded-full bg-gray-200 overflow-hidden flex-shrink-0">
                                            <img src="{{ $student->profile_picture ? asset('images/' . $student->profile_picture) : asset('images/default_profile.png') }}"
                                                alt="Profile Picture" class="w-full h-full object-cover" draggable="false"
                                                ondragstart="return false;" onselectstart="return false;">
                                        </div>
                                        {{ $student->name }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-zinc-600">{{ $student->email }}</td>
                                <td class="px-4 py-3 text-zinc-600">{{ $student->course->course_name }}</td>
                                <td class="py-3">
                                    <div class="flex gap-2 items-center">
                                        <a href="{{ route('admin-edit-student', $student->id) }}"
                                            class="inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-sm font-medium bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </a>
                                        <button data-modal-target="delete-student-modal"
                                            class="inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-sm font-medium bg-red-50 text-danger hover:bg-danger/90 transition-colors duration-200">
                                            <svg class="w-4 h-4 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <td colspan="5" class="px-4 py-8 text-center text-zinc-500">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-12 h-12 text-zinc-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <p class="text-lg font-medium">No student yet</p>
                                        <p class="text-sm">Get started by creating your first student.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($students->hasPages())
            <div class="mt-6 flex items-center justify-center">
                {{-- <div class="text-sm text-gray-500">
                    Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of {{ $students->total() }} results
                </div> --}}
                <div class="flex space-x-2">
                    @if ($students->onFirstPage())
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
                    <a href="{{ $students->previousPageUrl() }}"
                        class="flex px-2 py-1 rounded border border-zinc-900 text-zinc-900 items-center justify-center text-center hover:bg-zinc-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="w-5 h-5">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M15 6l-6 6l6 6" />
                        </svg>
                    </a>
                    @endif

                    @foreach ($students->getUrlRange(1, $students->lastPage()) as $page => $url)
                    @if ($page == $students->currentPage())
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

                    @if ($students->hasMorePages())
                    <a href="{{ $students->nextPageUrl() }}"
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

    <!-- Import Student Modal -->
    <x-my-modal id="import-student-modal" title="Import Students via CSV" iconType="info">
        <div class="mt-2">
            <p class="text-sm text-zinc-500 mb-4">
                Upload a CSV file containing student details. The file must have the following headers: <br>
                <code class="text-xs bg-gray-100 px-1 py-0.5 rounded">id_number, first_name, last_name, email, department_id, course_id, password</code>
            </p>
            <form id="import-student-form" method="POST" action="{{ route('admin.students.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <input type="file" name="csv_file" accept=".csv" required
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none p-2">
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button data-modal-close type="button"
                        class="inline-flex w-full justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 hover:bg-zinc-50 sm:mt-0 sm:w-auto">
                        Cancel
                    </button>
                    <button type="submit"
                        class="inline-flex w-full justify-center rounded-lg bg-zinc-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-zinc-800 sm:w-auto">
                        Import
                    </button>
                </div>
            </form>
        </div>
        <x-slot name="footer">
        </x-slot>
    </x-my-modal>

    <!-- Delete Student Modal -->
    <x-my-modal id="delete-student-modal" title="Delete Student Confirmation" iconType="warning">
        <p class="text-sm text-zinc-600">
            Are you sure you want to delete "<span id="delete-student-name" class="font-bold text-zinc-900"></span>"?
            This action cannot be undone.
        </p>

        <x-slot name="footer">
            <form id="delete-student-form" method="POST" action="{{ route('admin.delete-student') }}">
                @csrf
                @method('DELETE')
                <input type="hidden" name="student_id" id="student-id-input">
                <button data-modal-close type="button"
                    class="mt-3 inline-flex w-full justify-center rounded-lg bg-zinc-100 px-4 py-2 text-sm font-semibold text-zinc-700 hover:bg-zinc-200 transition-colors sm:mt-0 sm:w-auto">
                    Cancel
                </button>
                <button type="submit"
                    class="inline-flex w-full justify-center rounded-lg bg-zinc-900 px-4 py-2 text-sm font-semibold text-white hover:bg-zinc-900/90 transition-colors sm:ml-3 sm:w-auto">
                    Yes, delete
                </button>
            </form>
        </x-slot>
    </x-my-modal>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Delete modal functionality
            document.querySelectorAll('[data-modal-target="delete-student-modal"]').forEach(button => {
                button.addEventListener('click', function () {
                    const row = this.closest('tr');
                    const studentId = row.dataset.studentId;
                    const studentName = row.dataset.studentName;

                    document.getElementById('delete-student-name').textContent = studentName;
                    document.getElementById('student-id-input').value = studentId;
                });
            });

            // Enhanced search functionality
            const searchInput = document.getElementById('studentSearch');
            const searchStatus = document.getElementById('studentSearch-status');
            const searchResultsText = document.getElementById('studentSearch-results-text');
            const studentRows = document.querySelectorAll('.student-row');

            function performSearch() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                let visibleCount = 0;

                studentRows.forEach(row => {
                    const name = row.dataset.studentName.toLowerCase();
                    const email = row.dataset.studentEmail.toLowerCase();
                    const id = row.querySelector('td:first-child').textContent.toLowerCase();

                    if (name.includes(searchTerm) || email.includes(searchTerm) || id.includes(searchTerm)) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (searchTerm) {
                    searchStatus.classList.remove('hidden');
                    searchResultsText.textContent = `Found ${visibleCount} student${visibleCount !== 1 ? 's' : ''} matching "${searchTerm}"`;
                } else {
                    searchStatus.classList.add('hidden');
                }
            }

            searchInput.addEventListener('input', performSearch);
        });


    </script>
</x-admin-layout>
