<x-admin-layout :title="'Edit Module'">
    <main class="flex-1 max-h-full p-5 lg:mt-0 mt-20">
        <div class="flex items-center gap-0">
            <a href="{{ route('admin.modules') }}" class="flex items-center gap-2">
                <h1 class="text-2xl/8 font-semibold text-zinc-950 sm:text-xl/8 ">Modules</h1>
            </a>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-7 w-7">
                <path d="m9 18 6-6-6-6" />
            </svg>
            <h1 class="text-2xl/8 font-semibold text-zinc-950 sm:text-xl/8 ">Edit Module</h1>
        </div>
        <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5 ">
        <!-- Toast Notification -->
        <div id="toast-container" class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 w-full max-w-md">
            <div id="toast"
                class="hidden bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg flex items-center space-x-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z">
                    </path>
                </svg>
                <span id="toast-message"></span>
            </div>
        </div>
        <style>
            #toast.hidden {
                opacity: 0;
                transform: translateY(-20px);
                visibility: hidden;
            }
            #toast {
                opacity: 1;
                transform: translateY(0);
                visibility: visible;
                transition: opacity 400ms ease-in-out, transform 400ms cubic-bezier(0.4, 0, 0.2, 1), visibility 400ms ease-in-out;
            }
        </style>

        <form method="POST" class="mx-auto" action="{{ route('admin.module.update', ['module' => $module->id]) }}"
            enctype="multipart/form-data" id="module-form">
            @csrf
            @method('PUT')
            <section class="flex justify-center">
                <div>
                    <label for="dropzone-file" id="dropzone"
                        class="transition-colors duration-300 ease-in-out flex flex-col px-20 items-center justify-center w-full h-80 border-2 border-dashed border-gray-200 rounded-xl cursor-pointer bg-white hover:border-primary relative overflow-hidden">
                        <!-- Default state -->
                        <div id="dropzone-content"
                            class="flex flex-col items-center justify-center pt-5 pb-6 pointer-events-none">
                            <div class="mb-4 flex justify-center">
                                <div
                                    class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 text-zinc-700">
                                    <svg class="fill-current w-8 h-8" viewBox="0 0 29 28" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M14.5019 3.91699C14.2852 3.91699 14.0899 4.00891 13.953 4.15589L8.57363 9.53186C8.28065 9.82466 8.2805 9.2995 8.5733 10.5925C8.8661 10.8855 9.34097 10.8857 9.63396 10.5929L13.7519 6.47752V18.667C13.7519 19.0812 14.0877 19.417 14.5019 19.417C14.9161 19.417 15.2519 19.0812 15.2519 18.667V6.48234L19.3653 10.5929C19.6583 10.8857 20.1332 10.8855 20.426 10.5925C20.7188 10.2995 20.7186 9.82463 20.4256 9.53184L15.0838 4.19378C14.9463 4.02488 14.7367 3.91699 14.5019 3.91699ZM5.91626 18.667C5.91626 18.2528 5.58047 17.917 5.16626 17.917C4.75205 17.917 4.41626 18.2528 4.41626 18.667V21.8337C4.41626 23.0763 5.42362 24.0837 6.66626 24.0837H22.3339C23.5766 24.0837 24.5839 23.0763 24.5839 21.8337V18.667C24.5839 18.2528 24.2482 17.917 23.8339 17.917C23.4197 17.917 23.0839 18.2528 23.0839 18.667V21.8337C23.0839 22.2479 22.7482 22.5837 22.3339 22.5837H6.66626C6.25205 22.5837 5.91626 22.2479 5.91626 21.8337V18.667Z"
                                            fill="" />
                                    </svg>
                                </div>
                            </div>
                            <p class="mb-2 px-4 text-sm text-gray-600 text-center">Drag and drop your PDF here or click
                                to browse</p>
                        </div>

                        <!-- PDF Preview -->
                        <div id="pdf-preview"
                            class="hidden w-full h-full flex-col items-center justify-center p-6 bg-white">
                            <div class="w-full max-w-md">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-zinc-600"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p id="pdf-filename" class="font-medium text-zinc-900 truncate max-w-xs">
                                            </p>
                                            <p id="pdf-filesize" class="text-xs text-zinc-500 mt-1"></p>
                                        </div>
                                    </div>
                                    <button id="remove-file" type="button"
                                        class="text-red-600 hover:text-red-800 text-sm font-medium">Remove</button>
                                </div>
                                <div id="pdf-canvas-container"
                                    class="w-full h-48 bg-white rounded-lg shadow-sm overflow-hidden">
                                    <canvas id="pdf-canvas" class="w-full h-full"></canvas>
                                </div>
                            </div>
                        </div>

                        <input id="dropzone-file" type="file" class="hidden" name="file" accept=".pdf"/>
                    </label>
                    <div id="file-error" class="mt-2 text-sm text-red-600 hidden">The module file field is required.
                    </div>
                    <x-input-error :messages="$errors->get('file')" class="mt-2" />
                </div>
                <div class="space-y-1">
                </div>
            </section>

            <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5">

            <section class="grid gap-x-8 gap-y-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                <div class="space-y-1">
                    <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6">Course Code</h2>
                    <input type="text" name="course_code" id="course_code" required
                        class="h-[3rem] peer block w-full text-sm px-4 pt-2 pb-2 text-zinc-900 bg-white rounded-[8px] border border-gray-200 appearance-none focus:border-transparent focus:outline focus:outline-2 focus:outline-primary focus:ring-0 font-normal"
                        value="{{ $module->course_code }}">
                    <x-input-error :messages="$errors->get('course_code')" class="mt-2" />
                </div>
                <div class="space-y-1">
                    <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6">Course Title</h2>
                    <input type="text" name="title" id="title" placeholder="" required
                        class="h-[3rem] peer block w-full text-sm px-4 pt-2 pb-2 text-zinc-900 bg-white rounded-[8px] border border-gray-200 appearance-none focus:border-transparent focus:outline focus:outline-2 focus:outline-primary focus:ring-0 font-normal"
                        value="{{ $module->title }}">
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>
                <div class="space-y-1">
                    <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6">Course Status</h2>
                    <select name="isMajor" id="isMajor" required
                        class="h-[3rem] w-full rounded-lg border border-gray-200 px-3 text-sm focus:outline focus:outline-2 focus:outline-primary">
                        <option value="" disabled selected>Select Course Status</option>
                        <option value="1" {{ $module->isMajor == 1 ? 'selected' : '' }}>Major subject</option>
                        <option value="0" {{ $module->isMajor == 0 ? 'selected' : '' }}>Minor subject</option>
                    </select>
                    <x-input-error :messages="$errors->get('isMajor')" class="mt-2" />
                </div>
                <div class="space-y-1">
                    <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6">Semester</h2>
                    <select name="semester" id="semester" required
                        class="h-[3rem] w-full rounded-lg border border-gray-200 px-3 text-sm focus:outline focus:outline-2 focus:outline-primary">
                        <option value="" disabled selected>Select Semester</option>
                        <option value="1st" {{ $module->semester == '1st' ? 'selected' : '' }}>1st Semester</option>
                        <option value="2nd" {{ $module->semester == '2nd' ? 'selected' : '' }}>2nd Semester</option>
                    </select>
                    <x-input-error :messages="$errors->get('semester')" class="mt-2" />
                </div>
            </section>

            <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5">

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
                                {{ $module->department_id == $department->id || in_array($department->id, old('department_ids', [])) ? 'checked' : '' }}>
                            <label for="dept-{{ $department->id }}" class="text-sm text-zinc-700 cursor-pointer">{{ $department->department_name }}</label>
                        </div>
                    @endforeach
                </div>
                <!-- Hidden input for department_id (auto-set to first selected) -->
                <input type="hidden" name="department_id" id="primary-department-id" value="{{ $module->department_id }}">
            </section>

            <!-- ================= DEGREE PROGRAMS (multi-select) ================= -->
            <section class="space-y-4">
                <div>
                    <h2 class="text-lg font-semibold text-zinc-900">Degree Programs <span class="text-zinc-400 text-sm font-normal">(select multiple)</span></h2>
                    <p class="text-sm text-zinc-500 mt-1">Select the degree programs whose students should see this module.</p>
                </div>

                <div id="course-checkboxes" class="rounded-lg border border-zinc-200 bg-white p-4 max-h-60 overflow-y-auto">
                    <p class="text-sm text-zinc-400">Loading programs...</p>
                </div>
                @error('course_ids')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </section>

            <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5">

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

                <div id="enrolled-students-tags" class="flex flex-wrap gap-2"></div>
                <div id="enrolled-students-inputs"></div>

                @error('enrolled_students')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p id="enrollment-error" class="hidden text-sm text-red-600">Please enroll at least one student.</p>
            </section>

            <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5">

            <div class="flex justify-end gap-2">
                <x-my-secondary-button type="button" onclick="window.location.href='{{ route('admin.modules') }}'">Cancel
                </x-my-secondary-button>
                <x-my-button type="submit">Save changes</x-my-button>
            </div>
        </form>
    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropzone = document.getElementById("dropzone");
            const dropzoneContent = document.getElementById("dropzone-content");
            const pdfPreview = document.getElementById("pdf-preview");
            const pdfFilename = document.getElementById("pdf-filename");
            const pdfFilesize = document.getElementById("pdf-filesize");
            const fileInput = document.getElementById("dropzone-file");
            const form = document.getElementById("module-form");
            const removeButton = document.getElementById("remove-file");
            const pdfCanvas = document.getElementById("pdf-canvas");
            const toast = document.getElementById("toast");
            const toastMessage = document.getElementById("toast-message");
            const fileError = document.getElementById("file-error");

            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';

            const existingPdfUrl = @json(asset('files/' . $module->file));
            if (existingPdfUrl) {
                fetch(existingPdfUrl)
                    .then(res => res.arrayBuffer())
                    .then(async (buffer) => {
                        const typedarray = new Uint8Array(buffer);
                        const pdf = await pdfjsLib.getDocument(typedarray).promise;
                        const page = await pdf.getPage(1);
                        const viewport = page.getViewport({ scale: 0.5 });

                        dropzoneContent.classList.add('hidden');
                        pdfPreview.classList.remove('hidden');
                        dropzone.classList.add('border-zinc-300', 'bg-zinc-50');

                        pdfFilename.textContent = "{{ $module->file }}";
                        pdfFilesize.textContent = 'Existing file';

                        const canvas = pdfCanvas;
                        const context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;

                        await page.render({
                            canvasContext: context,
                            viewport: viewport
                        }).promise;
                    })
                    .catch((err) => {
                        console.error("Failed to preview existing file:", err);
                    });
            }

            function showErrorToast(message) {
                toastMessage.textContent = message;
                toast.classList.remove('hidden');
                setTimeout(() => {
                    toast.classList.add('hidden');
                }, 3000);
            }

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, unhighlight, false);
            });

            function highlight() {
                dropzone.classList.add("border-primary", "bg-zinc-50");
            }

            function unhighlight() {
                dropzone.classList.remove("border-primary", "bg-zinc-50");
                dropzone.classList.add("border-gray-200");
            }

            dropzone.addEventListener('drop', handleDrop, false);
            fileInput.addEventListener('change', handleFileSelect);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                handleFiles(files);
            }

            function handleFileSelect() {
                handleFiles(this.files);
            }

            async function handleFiles(files) {
                const file = files[0];
                if (!file) return;

                if (file.type !== "application/pdf") {
                    showErrorToast('Please upload a PDF file only');
                    resetDropzone();
                    return;
                }

                fileError.classList.add('hidden');
                dropzone.classList.remove('border-red-500');

                await showPdfPreview(file);
            }

            async function showPdfPreview(file) {
                pdfFilename.textContent = file.name;
                pdfFilesize.textContent = formatFileSize(file.size);

                dropzoneContent.classList.add('hidden');
                pdfPreview.classList.remove('hidden');
                dropzone.classList.add('border-zinc-300', 'bg-zinc-50');

                try {
                    const fileReader = new FileReader();
                    fileReader.onload = async function() {
                        const typedarray = new Uint8Array(this.result);
                        const pdf = await pdfjsLib.getDocument(typedarray).promise;
                        const page = await pdf.getPage(1);
                        const viewport = page.getViewport({ scale: 0.5 });

                        const canvas = pdfCanvas;
                        const context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;

                        await page.render({
                            canvasContext: context,
                            viewport: viewport
                        }).promise;
                    };
                    fileReader.readAsArrayBuffer(file);
                } catch (error) {
                    console.error('PDF preview error:', error);
                    pdfCanvas.style.display = 'none';
                }
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            function resetDropzone() {
                fileInput.value = "";
                pdfPreview.classList.add('hidden');
                dropzoneContent.classList.remove('hidden');
                dropzone.classList.remove('border-zinc-300', 'bg-zinc-50', 'border-red-500');
                dropzone.classList.add('border-gray-200');
                pdfCanvas.style.display = 'block';
                pdfCanvas.getContext('2d').clearRect(0, 0, pdfCanvas.width, pdfCanvas.height);
                fileError.classList.add('hidden');
            }

            removeButton.addEventListener('click', resetDropzone);

            @if ($errors->any())
                showErrorToast('{{ $errors->first() }}');
            @elseif (session('error'))
                showErrorToast('{{ session('error') }}');
            @endif

            form.addEventListener('submit', function(e) {
                let hasError = false;

                if (!fileInput.files.length && !existingPdfUrl) {
                    e.preventDefault();
                    fileError.classList.remove('hidden');
                    dropzone.classList.add('border-red-500');
                    showErrorToast('A PDF file is required.');
                    hasError = true;
                } else {
                    dropzone.classList.remove('border-red-500');
                    fileError.classList.add('hidden');
                }

                const checkedCourses = document.querySelectorAll('input[name="course_ids[]"]:checked');
                if (checkedCourses.length === 0) {
                    e.preventDefault();
                    if (!hasError) showErrorToast('Please select at least one degree program.');
                    hasError = true;
                }

                if (enrolledStudents.size === 0) {
                    e.preventDefault();
                    document.getElementById('enrollment-error').classList.remove('hidden');
                    if (!hasError) showErrorToast('Please enroll at least one student.');
                    hasError = true;
                } else {
                    document.getElementById('enrollment-error').classList.add('hidden');
                }

                if (hasError) return;
            });

            document.querySelectorAll('[required]').forEach(field => {
                field.addEventListener('input', function() {
                    this.classList.remove('border-red-500', 'focus:border-red-500');
                    if (this === fileInput && this.files.length) {
                        fileError.classList.add('hidden');
                        dropzone.classList.remove('border-red-500');
                    }
                });
            });

            dropzone.addEventListener('click', function(e) {
                if (!pdfPreview.classList.contains('hidden') && e.target !== removeButton) {
                    fileInput.click();
                }
            });

            // ================= DEPARTMENT CHECKBOXES -> DEGREE PROGRAM CHECKBOXES =================
            const deptCheckboxes = document.querySelectorAll('.dept-checkbox');
            const courseCheckboxes = document.getElementById('course-checkboxes');
            const primaryDeptInput = document.getElementById('primary-department-id');
            const selectedCourseIds = @json($selectedCourseIds ?? []);

            function getSelectedDepartmentIds() {
                return [...document.querySelectorAll('.dept-checkbox:checked')].map(cb => cb.value);
            }

            function loadCourseCheckboxes(departmentIds) {
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
                            courseCheckboxes.innerHTML = '<p class="text-sm text-zinc-400">No programs available</p>';
                            return;
                        }

                        courseCheckboxes.innerHTML = '';
                        allCourses.forEach(c => {
                            const isChecked = selectedCourseIds.includes(c.id);
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
                        courseCheckboxes.innerHTML = '<p class="text-sm text-red-500">Error loading</p>';
                    });
            }

            deptCheckboxes.forEach(cb => {
                cb.addEventListener('change', () => {
                    loadCourseCheckboxes(getSelectedDepartmentIds());
                });
            });

            // On load, if departments were pre-selected
            if (getSelectedDepartmentIds().length > 0) {
                loadCourseCheckboxes(getSelectedDepartmentIds());
            }

            // ================= STUDENT ENROLLMENT SEARCH =================
            const studentSearch = document.getElementById('student-search');
            const studentResults = document.getElementById('student-results');
            const enrolledTags = document.getElementById('enrolled-students-tags');
            const enrolledInputsContainer = document.getElementById('enrolled-students-inputs');
            const enrolledStudents = new Map();
            let searchTimeout;

            // Pre-populate enrolled students from server
            const existingEnrollments = @json($enrolledStudents ?? []);
            existingEnrollments.forEach(enrollment => {
                if (enrollment.student) {
                    const s = enrollment.student;
                    const middle = s.middle_initial ? s.middle_initial + '. ' : '';
                    enrolledStudents.set(s.id, {
                        id: s.id,
                        id_number: s.id_number,
                        name: `${s.first_name} ${middle}${s.last_name}`,
                        email: s.email,
                        department: s.department ? s.department.department_name : 'N/A',
                        course: s.course ? s.course.course_name : 'N/A',
                    });
                }
            });
            renderEnrolledStudents();

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

            window.removeEnrolledStudent = function(id) {
                enrolledStudents.delete(id);
                renderEnrolledStudents();
            }

            function renderEnrolledStudents() {
                enrolledTags.innerHTML = '';
                enrolledInputsContainer.innerHTML = '';

                enrolledStudents.forEach((student, id) => {
                    const tag = document.createElement('span');
                    tag.className = 'inline-flex items-center gap-1 rounded-full bg-zinc-100 px-3 py-1.5 text-sm text-zinc-700';
                    tag.innerHTML = `
                        <span class="font-medium">${student.name}</span>
                        <span class="text-zinc-400">(${student.id_number})</span>
                        <button type="button" onclick="removeEnrolledStudent(${id})" class="ml-1 text-zinc-400 hover:text-red-500">×</button>
                    `;
                    enrolledTags.appendChild(tag);

                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'enrolled_students[]';
                    input.value = id;
                    enrolledInputsContainer.appendChild(input);
                });
            }
        });
    </script>
</x-admin-layout>
