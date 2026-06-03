<x-admin-layout :title="'All Faculties'">
    <main class="flex-1 max-h-full p-5 lg:mt-0 mt-20">
        <div class="flex items-start justify-between">
            <h1 class="text-2xl/8 font-semibold text-zinc-950 sm:text-xl/8">
                Faculties
            </h1>
            <div class="flex gap-2">
                <x-my-secondary-button data-modal-target="import-faculty-modal">
                    Import Faculties
                </x-my-secondary-button>
                <x-my-secondary-button onclick="window.location.href='{{ route('admin.faculty.create') }}'">
                    Create new faculty
                </x-my-secondary-button>
            </div>
        </div>

        <!-- Search Controls -->
        <div class="mt-8">
            <x-search-bar id="facultySearch" placeholder="Search faculties..." />
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
                            <th class="px-4 py-3 font-semibold text-zinc-700">Department</th>
                            <th class="px-4 py-3 font-semibold text-zinc-700 whitespace-nowrap">Modules Uploaded</th>
                            <th class="px-4 py-3 font-semibold text-zinc-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($faculties as $faculty)
                            <tr class="border-b border-zinc-100 hover:bg-zinc-50 transition-colors duration-150 faculty-row"
                                data-faculty-id="{{ $faculty->id }}" data-faculty-name="{{ $faculty->name }}"
                                data-faculty-email="{{ $faculty->email }}">
                                <td class="px-4 py-3 font-medium text-zinc-900">{{ $faculty->id_number }}</td>
                                <td
                                    class="relative px-4 first:pl-[var(--gutter,theme(spacing.2))] last:pr-[var(--gutter,theme(spacing.2))] border-b border-zinc-950/5 dark:border-white/5 py-4 sm:first:pl-1 sm:last:pr-1">
                                    <div class="flex gap-2 items-center">
                                        <div class="ml-2 w-8 h-8 rounded-full bg-gray-200 overflow-hidden flex-shrink-0">
                                            <img src="{{ $faculty->profile_picture ? asset('images/' . $faculty->profile_picture) : asset('images/default_profile.png') }}"
                                                alt="Profile Picture" class="w-full h-full object-cover" draggable="false"
                                                ondragstart="return false;" onselectstart="return false;">
                                        </div>
                                        {{ $faculty->name }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-zinc-600">{{ $faculty->email }}</td>
                                <td class="px-4 py-3 text-zinc-600">{{ $faculty->department->department_name }}</td>
                                <td class="px-4 py-3 text-zinc-600">{{ $faculty->modules_count }}</td>
                                <td class="py-3">
                                    <div class="flex gap-2 items-center">
                                        <a href="{{ route('admin-edit-faculty', $faculty->id) }}"
                                            class="inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 text-sm font-medium text-zinc-900 hover:bg-zinc-900/5 transition-colors duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </a>
                                        <button data-modal-target="delete-faculty-modal"
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
                                <td colspan="6" class="px-4 py-8 text-center text-zinc-500">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-12 h-12 text-zinc-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <p class="text-lg font-medium">No faculty yet</p>
                                        <p class="text-sm">Get started by creating your first faculty.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($faculties->hasPages())
                <x-pagination :paginator="$faculties" />
            @endif
        </div>
    </main>
    <!-- Import Faculty Modal -->
    <x-import-modal 
        id="import-faculty-modal"
        title="Import Faculties via CSV"
        actionUrl="{{ route('admin.faculties.import') }}"
        headers="id_number, first_name, last_name, email, department_id, password"
        submitText="Import Faculties"
        entityType="faculty"
    />

    <x-my-modal id="delete-faculty-modal" title="Delete Faculty Confirmation" iconType="warning">
        <p class="text-sm text-gray-500">
            Are you sure you want to delete "<span id="delete-faculty-name" class="font-bold"></span>"? This will also
            delete all faculties posted by this faculty.
            This action cannot be undone.
        </p>

        <x-slot name="footer">
            <form id="delete-faculty-form" method="POST" action="{{ route('admin.delete-faculty') }}">
                @csrf
                @method('DELETE')
                <input type="hidden" name="faculty_id" id="faculty-id-input">
                <button data-modal-close type="button"
                    class="mt-3 inline-flex w-full justify-center rounded-md bg-gray-100 px-3 py-2 text-sm font-semibold text-gray-900 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                    Cancel
                </button>
                <button type="submit"
                    class="inline-flex w-full justify-center rounded-md bg-primary px-3 py-2 text-sm font-semibold text-white hover:bg-gray-900/90 sm:ml-3 sm:w-auto">
                    Yes, delete
                </button>
            </form>
        </x-slot>
    </x-my-modal>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Delete modal functionality
            document.querySelectorAll('[data-modal-target="delete-faculty-modal"]').forEach(button => {
                button.addEventListener('click', function () {
                    const row = this.closest('tr');
                    const facultyId = row.dataset.facultyId;
                    const facultyName = row.dataset.facultyName;

                    document.getElementById('delete-faculty-name').textContent = facultyName;
                    document.getElementById('faculty-id-input').value = facultyId;
                });
            });

            // Enhanced search functionality
            const searchInput = document.getElementById('facultySearch');
            const searchStatus = document.getElementById('facultySearch-status');
            const searchResultsText = document.getElementById('facultySearch-results-text');
            const facultyRows = document.querySelectorAll('.faculty-row');

            function performSearch() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                let visibleCount = 0;

                facultyRows.forEach(row => {
                    const name = row.dataset.facultyName.toLowerCase();
                    const email = row.dataset.facultyEmail.toLowerCase();
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
                    searchResultsText.textContent = `Found ${visibleCount} faculty${visibleCount !== 1 ? 's' : ''} matching "${searchTerm}"`;
                } else {
                    searchStatus.classList.add('hidden');
                }
            }

            searchInput.addEventListener('input', performSearch);
        });


    </script>
</x-admin-layout>
