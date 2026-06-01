<x-faculty-layout :title="'Create Modules'">
    <main class="flex-1 max-h-full p-5 lg:mt-[5rem] my-20 md:px-20">
        <h1 class="text-2xl font-semibold text-zinc-900">Create Modules</h1>
        <hr class="my-8 border-zinc-200">

        <!-- TOAST -->
        <div id="toast"
            class="hidden fixed top-5 left-1/2 -translate-x-1/2 z-50 bg-red-600 text-white px-5 py-3 rounded-xl shadow-lg text-sm">
            <span id="toast-message"></span>
        </div>

        <form id="module-form"
              action="{{ route('faculty.module.store-multiple') }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-10">
            @csrf

            <!-- ================= MODULE FILES ================= -->
            <section class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-zinc-900">Module Files</h2>
                    <span id="file-count" class="text-sm text-zinc-500">No files added</span>
                </div>

                <div id="dropzone"
                     class="rounded-2xl border border-zinc-200 bg-white p-6 transition hover:border-zinc-400">

                    <!-- Empty State -->
                    <div id="empty-state"
                         class="flex flex-col items-center justify-center text-center py-12">
                        <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-zinc-100">
                            📄
                        </div>
                        <p class="text-sm font-medium text-zinc-800">
                            Upload your module PDFs
                        </p>
                        <p class="text-sm text-zinc-500 mt-1">
                            Drag & drop files here or click below
                        </p>

                        <button type="button"
                                onclick="document.getElementById('file-input').click()"
                                class="mt-4 rounded-lg bg-zinc-900 px-4 py-2 text-sm text-white hover:bg-zinc-800">
                            Add PDF files
                        </button>

                        <p class="mt-3 text-xs text-zinc-400">
                            PDF only • Multiple files supported
                        </p>
                    </div>

                    <!-- File List -->
                    <div id="files-wrapper" class="hidden">
                        <div id="files-list" class="space-y-2"></div>

                        <button type="button"
                                onclick="document.getElementById('file-input').click()"
                                class="mt-4 text-sm font-medium text-zinc-700 hover:text-zinc-900">
                            + Add more files
                        </button>
                    </div>

                    <input id="file-input"
                           type="file"
                           name="files[]"
                           class="hidden"
                           accept=".pdf"
                           multiple>
                </div>

                <p id="file-error" class="hidden text-sm text-red-600">
                    Please upload at least one PDF file.
                </p>
            </section>

            <hr class="border-zinc-200">

            <!-- ================= COURSE INFO ================= -->
            <section class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div>
                    <label class="text-sm font-medium text-zinc-900">Course Code</label>
                    <input type="text" name="course_code" required
                        class="mt-1 w-full rounded-lg border border-zinc-200 p-3 text-sm focus:ring-2 focus:ring-zinc-900"
                        placeholder="Enter course code" value="{{ old('course_code') }}">
                    @error('course_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-sm font-medium text-zinc-900">Course Title</label>
                    <textarea name="title" required
                        class="mt-1 w-full rounded-lg border border-zinc-200 p-3 text-sm focus:ring-2 focus:ring-zinc-900"
                        placeholder="Enter course title">{{ old('title') }}</textarea>
                </div>

                <div>
                    <label class="text-sm font-medium text-zinc-900">Course Status</label>
                    <select name="isMajor" required
                        class="mt-1 w-full rounded-lg border border-zinc-200 p-2 text-sm">
                        <option value="" disabled selected>Select status</option>
                        <option value="1" {{ old('isMajor') == '1' ? 'selected' : '' }}>Major subject</option>
                        <option value="0" {{ old('isMajor') == '0' ? 'selected' : '' }}>Minor subject</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-zinc-900">Semester</label>
                    <select name="semester" required
                        class="mt-1 w-full rounded-lg border border-zinc-200 p-2 text-sm">
                        <option value="" disabled selected>Select semester</option>
                        <option value="1st" {{ old('semester') == '1st' ? 'selected' : '' }}>1st Semester</option>
                        <option value="2nd" {{ old('semester') == '2nd' ? 'selected' : '' }}>2nd Semester</option>
                    </select>
                </div>
            </section>

            <hr class="border-zinc-200">

            <!-- ================= DEPARTMENTS (multi-select filter) ================= -->
            <section class="space-y-4">
                <div>
                    <h2 class="text-lg font-semibold text-zinc-900">Departments <span class="text-zinc-400 text-sm font-normal">(select to load degree programs)</span></h2>
                    <p class="text-sm text-zinc-500 mt-1">Select one or more departments to see their degree programs below.</p>
                </div>

                <div id="department-checkboxes" class="rounded-lg border border-zinc-200 bg-white p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                    @foreach($departments as $department)
                        <div class="flex items-center gap-2">
                            <input type="checkbox" class="dept-checkbox rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900"
                                value="{{ $department->id }}" id="dept-{{ $department->id }}"
                                {{ in_array($department->id, old('department_ids', [])) ? 'checked' : '' }}>
                            <label for="dept-{{ $department->id }}" class="text-sm text-zinc-700 cursor-pointer">{{ $department->department_name }}</label>
                        </div>
                    @endforeach
                </div>
                <!-- Hidden input for department_id (auto-set to first selected) -->
                <input type="hidden" name="department_id" id="primary-department-id" value="{{ old('department_id') }}">
            </section>

            <!-- ================= DEGREE PROGRAMS (multi-select) ================= -->
            <section class="space-y-4">
                <div>
                    <h2 class="text-lg font-semibold text-zinc-900">Degree Programs <span class="text-zinc-400 text-sm font-normal">(select multiple)</span></h2>
                    <p class="text-sm text-zinc-500 mt-1">Select the degree programs whose students should see this module.</p>
                </div>

                <div id="course-checkboxes" class="rounded-lg border border-zinc-200 bg-white p-4 max-h-60 overflow-y-auto">
                    <p class="text-sm text-zinc-400">Select a department above first</p>
                </div>
                @error('course_ids')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </section>

            <hr class="border-zinc-200">

            <!-- ================= STUDENT ENROLLMENT (required) ================= -->
            <section class="space-y-4">
                <div>
                    <h2 class="text-lg font-semibold text-zinc-900">Enroll Students</h2>
                    <p class="text-sm text-zinc-500 mt-1">Search and enroll students into this course code. Enrolled students will see this module regardless of their degree program.</p>
                </div>

                <div class="relative">
                    <input type="text" id="student-search"
                        class="w-full rounded-lg border border-zinc-200 p-3 text-sm focus:ring-2 focus:ring-zinc-900"
                        placeholder="Search students by name, ID number, or email..." autocomplete="off">
                    <div id="student-results"
                        class="hidden absolute z-30 w-full mt-1 bg-white border border-zinc-200 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                    </div>
                </div>

                <!-- Selected students tags -->
                <div id="enrolled-students-tags" class="flex flex-wrap gap-2"></div>

                <!-- Hidden inputs for enrolled student IDs -->
                <div id="enrolled-students-inputs"></div>

                @error('enrolled_students')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p id="enrollment-error" class="hidden text-sm text-red-600">Please enroll at least one student.</p>
            </section>

            <hr class="border-zinc-200">

            <!-- ACTIONS -->
            <div class="flex justify-end gap-3">
                <x-my-secondary-button type="button"
                    onclick="window.location.href='{{ route('faculty.home') }}'">
                    Cancel
                </x-my-secondary-button>
                <x-my-button type="submit">
                    Create Modules
                </x-my-button>
            </div>
        </form>
    </main>

    <!-- ================= SCRIPT ================= -->
    <script>
        const input = document.getElementById('file-input');
        const list = document.getElementById('files-list');
        const empty = document.getElementById('empty-state');
        const wrapper = document.getElementById('files-wrapper');
        const count = document.getElementById('file-count');
        const error = document.getElementById('file-error');
        const toast = document.getElementById('toast');
        const toastMsg = document.getElementById('toast-message');

        let store = new DataTransfer();

        input.addEventListener('change', () => addFiles(input.files));

        function addFiles(files) {
            [...files].forEach(file => {
                if (file.type !== 'application/pdf') {
                    showToast('Only PDF files are allowed.');
                    return;
                }
                store.items.add(file);
            });
            input.files = store.files;
            render();
        }

        function render() {
            list.innerHTML = '';
            const files = [...store.files];

            if (!files.length) {
                empty.classList.remove('hidden');
                wrapper.classList.add('hidden');
                count.textContent = 'No files added';
                return;
            }

            empty.classList.add('hidden');
            wrapper.classList.remove('hidden');
            count.textContent = `${files.length} file${files.length > 1 ? 's' : ''}`;

            files.forEach((file, i) => {
                const row = document.createElement('div');
                row.className =
                    'flex items-center justify-between rounded-xl border border-zinc-200 bg-white px-4 py-3';

                row.innerHTML = `
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="rounded-md bg-red-100 px-2 py-1 text-xs font-medium text-red-700">PDF</span>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-zinc-900">${file.name}</p>
                            <p class="text-xs text-zinc-500">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                        </div>
                    </div>
                    <button type="button"
                        onclick="removeFile(${i})"
                        class="text-zinc-400 hover:text-red-600 text-lg">
                        ×
                    </button>
                `;
                list.appendChild(row);
            });
        }

        function removeFile(index) {
            const dt = new DataTransfer();
            [...store.files].forEach((f, i) => i !== index && dt.items.add(f));
            store = dt;
            input.files = dt.files;
            render();
        }

        function showToast(msg) {
            toastMsg.textContent = msg;
            toast.classList.remove('hidden');
            setTimeout(() => toast.classList.add('hidden'), 3000);
        }

        document.getElementById('module-form').addEventListener('submit', e => {
            let hasError = false;

            if (!store.files.length) {
                e.preventDefault();
                error.classList.remove('hidden');
                showToast('Please upload at least one PDF file.');
                hasError = true;
            }

            const checkedCourses = document.querySelectorAll('input[name="course_ids[]"]:checked');
            if (checkedCourses.length === 0) {
                e.preventDefault();
                showToast('Please select at least one degree program.');
                hasError = true;
            }

            if (enrolledStudents.size === 0) {
                e.preventDefault();
                document.getElementById('enrollment-error').classList.remove('hidden');
                if (!hasError) showToast('Please enroll at least one student.');
                hasError = true;
            } else {
                document.getElementById('enrollment-error').classList.add('hidden');
            }

            if (hasError) return;
        });

        // ================= DEPARTMENT CHECKBOXES -> DEGREE PROGRAM CHECKBOXES =================
        const deptCheckboxes = document.querySelectorAll('.dept-checkbox');
        const courseCheckboxes = document.getElementById('course-checkboxes');
        const primaryDeptInput = document.getElementById('primary-department-id');
        const oldCourseIds = @json(old('course_ids', []));

        function getSelectedDepartmentIds() {
            return [...document.querySelectorAll('.dept-checkbox:checked')].map(cb => cb.value);
        }

        function loadCoursesForDepartments(departmentIds) {
            // Set primary department_id to first selected
            primaryDeptInput.value = departmentIds.length > 0 ? departmentIds[0] : '';

            if (departmentIds.length === 0) {
                courseCheckboxes.innerHTML = '<p class="text-sm text-zinc-400">Select a department above first</p>';
                return;
            }

            courseCheckboxes.innerHTML = '<p class="text-sm text-zinc-400">Loading programs...</p>';

            // Fetch courses for all selected departments
            const fetches = departmentIds.map(id =>
                fetch(`/api/departments/${id}/courses`).then(r => r.json())
            );

            Promise.all(fetches)
                .then(results => {
                    // Flatten and deduplicate
                    const allCourses = [];
                    const seen = new Set();
                    results.forEach(courses => {
                        courses.forEach(c => {
                            if (!seen.has(c.id)) {
                                seen.add(c.id);
                                allCourses.push(c);
                            }
                        });
                    });

                    if (!allCourses.length) {
                        courseCheckboxes.innerHTML = '<p class="text-sm text-zinc-400">No programs available for the selected departments</p>';
                        return;
                    }

                    courseCheckboxes.innerHTML = '';
                    allCourses.forEach(c => {
                        const isChecked = oldCourseIds.includes(String(c.id)) || oldCourseIds.includes(c.id);
                        const div = document.createElement('div');
                        div.className = 'flex items-center gap-2 py-1';
                        div.innerHTML = `
                            <input type="checkbox" name="course_ids[]" value="${c.id}" id="course-${c.id}"
                                class="rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900"
                                ${isChecked ? 'checked' : ''}>
                            <label for="course-${c.id}" class="text-sm text-zinc-700 cursor-pointer">${c.course_name}</label>
                        `;
                        courseCheckboxes.appendChild(div);
                    });
                })
                .catch(() => {
                    courseCheckboxes.innerHTML = '<p class="text-sm text-red-500">Error loading programs</p>';
                });
        }

        deptCheckboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                loadCoursesForDepartments(getSelectedDepartmentIds());
            });
        });

        // On load, if departments were pre-selected
        if (getSelectedDepartmentIds().length > 0) {
            loadCoursesForDepartments(getSelectedDepartmentIds());
        }

        // ================= STUDENT ENROLLMENT SEARCH =================
        const studentSearch = document.getElementById('student-search');
        const studentResults = document.getElementById('student-results');
        const enrolledTags = document.getElementById('enrolled-students-tags');
        const enrolledInputs = document.getElementById('enrolled-students-inputs');
        let enrolledStudents = new Map();
        let searchTimeout;

        studentSearch.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            const query = this.value.trim();

            if (query.length < 2) {
                studentResults.classList.add('hidden');
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`/api/students/search?q=${encodeURIComponent(query)}`)
                    .then(r => r.json())
                    .then(students => {
                        if (!students.length) {
                            studentResults.innerHTML = '<div class="p-3 text-sm text-zinc-400">No students found</div>';
                            studentResults.classList.remove('hidden');
                            return;
                        }

                        studentResults.innerHTML = '';
                        students.forEach(s => {
                            if (enrolledStudents.has(s.id)) return;

                            const item = document.createElement('div');
                            item.className = 'p-3 hover:bg-zinc-50 cursor-pointer border-b border-zinc-100 last:border-0';
                            item.innerHTML = `
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-zinc-900">${s.name}</p>
                                        <p class="text-xs text-zinc-500">${s.id_number} • ${s.department} • ${s.course}</p>
                                    </div>
                                    <span class="text-xs text-zinc-400">Click to enroll</span>
                                </div>
                            `;
                            item.addEventListener('click', () => {
                                addEnrolledStudent(s);
                                studentResults.classList.add('hidden');
                                studentSearch.value = '';
                            });
                            studentResults.appendChild(item);
                        });
                        studentResults.classList.remove('hidden');
                    })
                    .catch(() => {
                        studentResults.innerHTML = '<div class="p-3 text-sm text-red-500">Error searching students</div>';
                        studentResults.classList.remove('hidden');
                    });
            }, 300);
        });

        document.addEventListener('click', function (e) {
            if (!studentSearch.contains(e.target) && !studentResults.contains(e.target)) {
                studentResults.classList.add('hidden');
            }
        });

        function addEnrolledStudent(student) {
            if (enrolledStudents.has(student.id)) return;
            enrolledStudents.set(student.id, student);
            document.getElementById('enrollment-error').classList.add('hidden');
            renderEnrolledStudents();
        }

        function removeEnrolledStudent(id) {
            enrolledStudents.delete(id);
            renderEnrolledStudents();
        }

        function renderEnrolledStudents() {
            enrolledTags.innerHTML = '';
            enrolledInputs.innerHTML = '';

            enrolledStudents.forEach((student, id) => {
                const tag = document.createElement('span');
                tag.className = 'inline-flex items-center gap-1 rounded-full bg-zinc-100 px-3 py-1.5 text-sm text-zinc-700';
                tag.innerHTML = `
                    <span class="font-medium">${student.name}</span>
                    <span class="text-zinc-400">(${student.id_number})</span>
                    <button type="button" onclick="removeEnrolledStudent(${id})" class="ml-1 text-zinc-400 hover:text-red-500">×</button>
                `;
                enrolledTags.appendChild(tag);

                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'enrolled_students[]';
                hiddenInput.value = id;
                enrolledInputs.appendChild(hiddenInput);
            });
        }
    </script>
</x-faculty-layout>
