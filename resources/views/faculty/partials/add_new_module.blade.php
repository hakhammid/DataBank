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
                    <label class="text-sm font-medium text-zinc-900">Course Description / Topic</label>
                    <textarea name="description"
                        class="mt-1 w-full rounded-lg border border-zinc-200 p-3 text-sm focus:ring-2 focus:ring-zinc-900"
                        placeholder="Enter course description or topic (optional)">{{ old('description') }}</textarea>
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
                    <label class="text-sm font-medium text-zinc-900">Visibility Status</label>
                    <select name="status" required
                        class="mt-1 w-full rounded-lg border border-zinc-200 p-2 text-sm">
                        <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>Published (Visible to Students)</option>
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft (Hidden from Students)</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-zinc-900">Department</label>
                    <select name="department_id" required
                        class="mt-1 w-full rounded-lg border border-zinc-200 p-2 text-sm">
                        <option value="" disabled selected>Select department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->department_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-zinc-900">Degree Program</label>
                    <select name="course_id" required
                        class="mt-1 w-full rounded-lg border border-zinc-200 p-2 text-sm">
                        <option value="" disabled selected>Select program</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->course_name }}
                            </option>
                        @endforeach
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
            if (!store.files.length) {
                e.preventDefault();
                error.classList.remove('hidden');
                showToast('Please upload at least one PDF file.');
            }
        });
    </script>
</x-faculty-layout>
