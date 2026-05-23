<x-admin-layout :title="'Dashboard'">
    <main class="flex-1 max-h-full p-5 lg:mt-0 mt-20">
        <div class="flex flex-col items-start justify-between pb-6 space-y-4 lg:items-center lg:space-y-0 lg:flex-row">
            <h1 class="text-2xl/8 font-semibold text-zinc-950 sm:text-xl/8">
                Hi, {{ Auth::user()->name }}!
            </h1>
        </div>
        <div class="grid grid-cols-1 gap-4 mt-6 sm:grid-cols-2 lg:grid-cols-3">
            @php
                $colors = [
                    'students' => [
                        'bg' => 'bg-white',
                        'text' => 'text-primary',
                        'border' => 'border-zinc-300',
                        'hover' =>
                            'hover:border-zinc-400'
                    ],
                    'faculty' => [
                        'bg' => 'bg-white',
                        'text' => 'text-primary',
                        'border' => 'border-zinc-300',
                        'hover' =>
                            'hover:border-zinc-400'
                    ],
                    'modules' => [
                        'bg' => 'bg-white',
                        'text' => 'text-primary',
                        'border' => 'border-zinc-300',
                        'hover' =>
                            'hover:border-zinc-400'
                    ],
                    'default' => [
                        'bg' => 'bg-white',
                        'text' => 'text-primary',
                        'border' => 'border-zinc-300',
                        'hover' =>
                            'hover:border-zinc-400'
                    ]
                ];

                $stats = [
                    [
                        'id' => 'students',
                        'title' => 'Students',
                        'value' => $studentCount,
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                                <circle cx="9" cy="7" r="4" />
                                                <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                            </svg>'
                    ],
                    [
                        'id' => 'faculty',
                        'title' => 'Faculty',
                        'value' => $facultyCount,
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                                <circle cx="9" cy="7" r="4" />
                                                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                            </svg>'
                    ],
                    [
                        'id' => 'modules',
                        'title' => 'Modules',
                        'value' => $moduleCount,
                        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                                            </svg>'
                    ],
                ];

                // foreach ($departments as $department) {
                // $stats[] = [
                // 'id' => $department->id,
                // 'title' => $department->department_name,
                // 'value' => $department->module_count,
                // 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none"
                //     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                //     <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                //     <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                // </svg>'
                // ];
                // }

            @endphp

            @foreach ($stats as $stat)
                    <div
                        class="relative group overflow-hidden rounded-xl border {{ isset($colors[$stat['id']]) ? $colors[$stat['id']]['border'] : $colors['default']['border'] }} p-6 shadow-sm transition-all duration-300 {{ isset($colors[$stat['id']]) ? $colors[$stat['id']]['hover'] : $colors['default']['hover'] }}">
                        <div
                            class="absolute -right-12 -bottom-12 h-40 w-40 rounded-full justify-between opacity-10 bg-gradient-to-br from-transparent to-{{ isset($colors[$stat['id']]) ? explode('-', $colors[$stat['id']]['text'])[1] : explode('-', $colors['default']['text'])[1] }}-900 transition-all duration-500 ">
                        </div>

                        <div class="flex items-start py-4 justify-between">
                            <div class="flex flex-col space-y-2">
                                <span class="text-xl font-medium text-zinc-700">{{ $stat['title'] }}</span>
                                <div class="flex items-baseline space-x-1">
                                    <span
                                        class="text-3xl font-bold {{ isset($colors[$stat['id']]) ? $colors[$stat['id']]['text'] : $colors['default']['text'] }}">{{
                number_format($stat['value']) }}</span>
                                    <span class="text-sm text-zinc-500">total</span>
                                </div>
                            </div>
                            <div
                                class="flex-shrink-0 p-3 rounded-lg {{ isset($colors[$stat['id']]) ? $colors[$stat['id']]['bg'] : $colors['default']['bg'] }} {{ isset($colors[$stat['id']]) ? $colors[$stat['id']]['text'] : $colors['default']['text'] }} transition-transform duration-300">
                                {!! $stat['icon'] !!}
                            </div>
                        </div>
                    </div>
            @endforeach
        </div>

        {{-- <h2 class="mt-14 text-base/7 font-semibold text-zinc-950 sm:text-sm/6">Recently added modules</h2> --}}

        <div class="inline-block min-w-full align-middle sm:px-[--gutter] mt-4">
            <div class="overflow-hidden rounded-xl border border-zinc-300 bg-white px-4 pb-3 pt-4 sm:px-6">
                <div class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="w-full">
                        <h3 class="text-lg font-semibold text-zinc-800 dark:text-white/90">
                            Department modules
                        </h3>
                        <div class="flex mt-4 flex-col lg:flex-row gap-4 items-start lg:items-center justify-between">
                            <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                                <div class="flex-1 w-full lg:w-auto">
                                    <x-search-bar id="dashboardSearch" placeholder="Search departments..." />
                                </div>
                                <div class="relative" x-data="{ open: false }">
                                    <x-my-secondary-button @click="open = !open" @click.away="open = false">
                                        <svg class="w-5 h-5 text-zinc-900" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Export
                                        <svg class="w-4 h-4 transition-transform duration-200"
                                            :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </x-my-secondary-button>

                                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100 scale-100"
                                        x-transition:leave-end="opacity-0 scale-95"
                                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-zinc-200 z-50">
                                        <div class="py-1">
                                            <button onclick="exportToPDF()"
                                                class="w-full text-left px-4 py-3 text-sm text-zinc-700 hover:bg-gray-100 flex items-center gap-3 transition-colors duration-150">
                                                <svg class="w-5 h-5 text-zinc-900" fill="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 1v5h5v10H6V3h7z" />
                                                    <path d="M8 13h8v2H8zm0 3h8v2H8zm0-6h3v2H8z" />
                                                </svg>
                                                Export to PDF
                                            </button>
                                            <button onclick="printTable()"
                                                class="w-full text-left px-4 py-3 text-sm text-zinc-700 hover:bg-gray-100 flex items-center gap-3 transition-colors duration-150">
                                                <svg class="w-5 h-5 text-zinc-900" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                </svg>
                                                Print Table
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

                <div class="w-full">
                    <table class="min-w-full text-left text-sm/6 text-zinc-950" id="table">
                        <thead class="text-zinc-500 dark:text-zinc-400">
                            <tr>
                                <th
                                    class="border-b border-b-zinc-950/10 px-4 py-2 font-medium first:pl-[var(--gutter,theme(spacing.2))] last:pr-[var(--gutter,theme(spacing.2))] dark:border-b-white/10 sm:first:pl-1 sm:last:pr-1">
                                    Department Name</th>
                                <th
                                    class="border-b border-b-zinc-950/10 px-4 py-2 font-medium first:pl-[var(--gutter,theme(spacing.2))] last:pr-[var(--gutter,theme(spacing.2))] dark:border-b-white/10 sm:first:pl-1 sm:last:pr-1">
                                    Number of modules</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($departmentsData as $department)
                                <tr class="department-row" data-department-id="{{ $department->id }}"
                                    data-department-name="{{ $department->department_name }}">
                                    <td
                                        class="relative px-4 first:pl-[var(--gutter,theme(spacing.2))] last:pr-[var(--gutter,theme(spacing.2))] border-b border-zinc-950/5 dark:border-white/5 py-4 sm:first:pl-1 sm:last:pr-1">
                                        <div class="flex gap-2 items-center">
                                            <div class="w-8 h-8 rounded-full bg-zinc-200 overflow-hidden flex-shrink-0">
                                                <img src="{{ $department->department_logo ? asset('images/' . $department->department_logo) : asset('images/default_logo.png') }}"
                                                    alt="Profile Picture" class="w-full h-full object-cover">
                                            </div>
                                            {{ $department->department_name }}
                                        </div>
                                    </td>
                                    <td
                                        class="relative px-4 first:pl-[var(--gutter,theme(spacing.2))] last:pr-[var(--gutter,theme(spacing.2))] border-b border-zinc-950/5 dark:border-white/5 py-4 sm:first:pl-1 sm:last:pr-1">
                                        {{ $department->modules_count }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-zinc-500">
                                        No modules have been added yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Enhanced search functionality
            const searchInput = document.getElementById('dashboardSearch');
            const searchStatus = document.getElementById('dashboardSearch-status');
            const searchResultsText = document.getElementById('dashboardSearch-results-text');
            const departmentRows = document.querySelectorAll('.department-row');

            function performSearch() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                let visibleCount = 0;

                departmentRows.forEach(row => {
                    const departmentName = row.dataset.departmentName.toLowerCase();
                    const departmentId = row.dataset.departmentId.toLowerCase();

                    if (departmentName.includes(searchTerm) || departmentId.includes(searchTerm)) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Update search status
                if (searchTerm) {
                    searchStatus.classList.remove('hidden');
                    searchResultsText.textContent = `Found ${visibleCount} department${visibleCount !== 1 ? 's' : ''} matching "${searchTerm}"`;
                } else {
                    searchStatus.classList.add('hidden');
                }
            }

            searchInput.addEventListener('input', performSearch);
        });

        // Export functions
        function exportToPDF() {
            const table = document.getElementById('table');
            const opt = {
                margin: 1,
                filename: `departments_${new Date().toISOString().split('T')[0]}.pdf`,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'landscape' }
            };
            html2pdf().set(opt).from(table).save();
        }

        function printTable() {
            window.open('{{ route('admin.departments-module.print') }}', '_blank');
        }
    </script>
</x-admin-layout>
