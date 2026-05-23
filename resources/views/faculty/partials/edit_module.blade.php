<x-faculty-layout :title="'Edit Module'">
    <main class="flex-1 max-h-full p-5 lg:mt-[5rem] my-20 md:px-20">
        <h1 class="text-2xl/8 font-semibold text-zinc-950 sm:text-xl/8">Edit Module</h1>
        <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5">

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

        <form method="POST" class="mx-auto" action="{{ route('faculty.module.update', ['module' => $module->id]) }}"
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
                    <textarea name="title" id="title" placeholder="" rows="2" required
                        class="h-[3rem] peer block w-full text-sm h-[100px] px-4 pt-2 pb-2 text-zinc-900 bg-white rounded-[8px] border border-gray-200 appearance-none focus:border-transparent focus:outline focus:outline-2 focus:outline-primary focus:ring-0 overflow-hidden resize-none font-normal">{{ $module->title }}</textarea>
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>
                <div class="space-y-1">
                    <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6">Course Status</h2>
                    <span data-slot="control"
                        class="relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white before:shadow after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent sm:after:focus-within:ring-2 sm:after:focus-within:ring-zinc-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none before:has-[[data-invalid]]:shadow-red-500/10">
                        <span data-slot="control"
                            class="group relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white dark:before:hidden after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent after:has-[[data-focus]]:ring-2 after:has-[[data-focus]]:ring-blue-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none">
                            <select
                                class="h-[3rem] relative block w-full appearance-none rounded-lg py-[calc(theme(spacing[2.5])-1px)] sm:py-[calc(theme(spacing[1.5])-1px)] pl-[calc(theme(spacing[3.5])-1px)] pr-[calc(theme(spacing.10)-1px)] sm:pl-[calc(theme(spacing.3)-1px)] sm:pr-[calc(theme(spacing.9)-1px)] [&_optgroup]:font-semibold text-base/6 text-zinc-950 placeholder:text-zinc-500 sm:text-sm/6 border border-zinc-950/10 data-[hover]:border-zinc-950/20 dark:border-white/10 dark:data-[hover]:border-white/20 bg-transparent dark:bg-white/5 dark:*:bg-zinc-800 focus:outline-none data-[invalid]:border-red-500 data-[invalid]:data-[hover]:border-red-500 data-[disabled]:border-zinc-950/20 data-[disabled]:opacity-100"
                                id="isMajor" data-headlessui-state="" name="isMajor" required>
                                <option value="" disabled selected>Select Course Status</option>
                                <option value="1" {{ $module->isMajor == 1 ? 'selected' : '' }}>Major subject</option>
                                <option value="0" {{ $module->isMajor == 0 ? 'selected' : '' }}>Minor subject</option>
                            </select>
                        </span>
                    </span>
                    <x-input-error :messages="$errors->get('isMajor')" class="mt-2" />
                </div>
                <div class="space-y-1">
                    <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6">Department</h2>
                    <span data-slot="control"
                        class="relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white before:shadow after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent sm:after:focus-within:ring-2 sm:after:focus-within:ring-zinc-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none before:has-[[data-invalid]]:shadow-red-500/10">
                        <span data-slot="control"
                            class="group relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white dark:before:hidden after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent after:has-[[data-focus]]:ring-2 after:has-[[data-focus]]:ring-blue-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none">
                            <select
                                class="h-[3rem] relative block w-full appearance-none rounded-lg py-[calc(theme(spacing[2.5])-1px)] sm:py-[calc(theme(spacing[1.5])-1px)] pl-[calc(theme(spacing[3.5])-1px)] pr-[calc(theme(spacing.10)-1px)] sm:pl-[calc(theme(spacing.3)-1px)] sm:pr-[calc(theme(spacing.9)-1px)] [&_optgroup]:font-semibold text-base/6 text-zinc-950 placeholder:text-zinc-500 sm:text-sm/6 border border-zinc-950/10 data-[hover]:border-zinc-950/20 dark:border-white/10 dark:data-[hover]:border-white/20 bg-transparent dark:bg-white/5 dark:*:bg-zinc-800 focus:outline-none data-[invalid]:border-red-500 data-[invalid]:data-[hover]:border-red-500 data-[disabled]:border-zinc-950/20 data-[disabled]:opacity-100"
                                id="department_id" data-headlessui-state="" name="department_id" required>
                                <option value="" disabled selected>Select Department</option>
                                @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ $module->department_id == $department->id ? 'selected' : ''
                                    }}>{{ $department->department_name }}</option>
                                @endforeach
                            </select>
                        </span>
                    </span>
                    <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
                </div>
            </section>

            <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5">

            <section class="grid gap-x-8 gap-y-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                <div class="space-y-1">
                    <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6">Degree Program</h2>
                    <span data-slot="control"
                        class="relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white before:shadow after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent sm:after:focus-within:ring-2 sm:after:focus-within:ring-zinc-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none before:has-[[data-invalid]]:shadow-red-500/10">
                        <span data-slot="control"
                            class="group relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white dark:before:hidden after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent after:has-[[data-focus]]:ring-2 after:has-[[data-focus]]:ring-blue-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none">
                            <select
                                class="h-[3rem] relative block w-full appearance-none rounded-lg py-[calc(theme(spacing[2.5])-1px)] sm:py-[calc(theme(spacing[1.5])-1px)] pl-[calc(theme(spacing[3.5])-1px)] pr-[calc(theme(spacing.10)-1px)] sm:pl-[calc(theme(spacing.3)-1px)] sm:pr-[calc(theme(spacing.9)-1px)] [&_optgroup]:font-semibold text-base/6 text-zinc-950 placeholder:text-zinc-500 sm:text-sm/6 border border-zinc-950/10 data-[hover]:border-zinc-950/20 dark:border-white/10 dark:data-[hover]:border-white/20 bg-transparent dark:bg-white/5 dark:*:bg-zinc-800 focus:outline-none data-[invalid]:border-red-500 data-[invalid]:data-[hover]:border-red-500 data-[disabled]:border-zinc-950/20 data-[disabled]:opacity-100 disabled:opacity-50 disabled:cursor-not-allowed"
                                id="course_id" data-headlessui-state="" name="course_id" required disabled>
                                <option value="" disabled selected>Loading...</option>
                            </select>
                        </span>
                    </span>
                    <x-input-error :messages="$errors->get('course_id')" class="mt-2" />
                </div>
                <div class="space-y-1">
                    <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6">Semester</h2>
                    <span data-slot="control"
                        class="relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white before:shadow after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent sm:after:focus-within:ring-2 sm:after:focus-within:ring-zinc-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none before:has-[[data-invalid]]:shadow-red-500/10">
                        <span data-slot="control"
                            class="group relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white dark:before:hidden after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent after:has-[[data-focus]]:ring-2 after:has-[[data-focus]]:ring-blue-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none">
                            <select
                                class="h-[3rem] relative block w-full appearance-none rounded-lg py-[calc(theme(spacing[2.5])-1px)] sm:py-[calc(theme(spacing[1.5])-1px)] pl-[calc(theme(spacing[3.5])-1px)] pr-[calc(theme(spacing.10)-1px)] sm:pl-[calc(theme(spacing.3)-1px)] sm:pr-[calc(theme(spacing.9)-1px)] [&_optgroup]:font-semibold text-base/6 text-zinc-950 placeholder:text-zinc-500 sm:text-sm/6 border border-zinc-950/10 data-[hover]:border-zinc-950/20 dark:border-white/10 dark:data-[hover]:border-white/20 bg-transparent dark:bg-white/5 dark:*:bg-zinc-800 focus:outline-none data-[invalid]:border-red-500 data-[invalid]:data-[hover]:border-red-500 data-[disabled]:border-zinc-950/20 data-[disabled]:opacity-100"
                                id="semester" data-headlessui-state="" name="semester" required>
                                <option value="" disabled selected>Select Semester</option>
                                <option value="1st" {{ $module->semester == '1st' ? 'selected' : '' }}>1st Semester</option>
                                <option value="2nd" {{ $module->semester == '2nd' ? 'selected' : '' }}>2nd Semester</option>
                            </select>
                        </span>
                    </span>
                    <x-input-error :messages="$errors->get('semester')" class="mt-2" />
                </div>
            </section>

            <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5">

            <div class="flex justify-end gap-2">
                <x-my-secondary-button type="button" onclick="window.location.href='{{ route('faculty.home') }}'">Cancel
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
            const successToast = document.getElementById("success-toast");
            const successToastMessage = document.getElementById("success-toast-message");
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

            function showSuccessToast(message) {
                successToastMessage.textContent = message;
                successToast.classList.remove('hidden');
                setTimeout(() => {
                    successToast.classList.add('hidden');
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
                e.preventDefault();
                const requiredFields = this.querySelectorAll('[required]');
                let isValid = true;
                let fileMissing = false;

                requiredFields.forEach(field => {
                    if (field !== fileInput && !field.value.trim()) {
                        isValid = false;
                        field.classList.add('border-red-500', 'focus:border-red-500');
                    } else if (field !== fileInput) {
                        field.classList.remove('border-red-500', 'focus:border-red-500');
                    }
                });

                // Check if a new file is uploaded or an existing file is present
                if (!fileInput.files.length && !existingPdfUrl) {
                    isValid = false;
                    fileMissing = true;
                    dropzone.classList.add('border-red-500');
                    fileError.classList.remove('hidden');
                } else {
                    dropzone.classList.remove('border-red-500');
                    fileError.classList.add('hidden');
                }

                if (!isValid) {
                    if (fileMissing && ![...requiredFields].some(field => field !== fileInput && !field.value.trim())) {
                        showErrorToast('A PDF file is required. Please upload a file.');
                    } else if (!fileMissing && [...requiredFields].some(field => field !== fileInput && !field.value.trim())) {
                        showErrorToast('Please fill in all required fields.');
                    } else {
                        showErrorToast('Please fill in all required fields and upload a PDF file.');
                    }
                    return;
                }

                form.submit();
            });

            // form.addEventListener('submit', function(e) {
            //     e.preventDefault();
            //     const requiredFields = this.querySelectorAll('[required]');
            //     let isValid = true;
            //     let fileMissing = false;

            //     requiredFields.forEach(field => {
            //         if (field !== fileInput && !field.value.trim()) {
            //             isValid = false;
            //             field.classList.add('border-red-500', 'focus:border-red-500');
            //         } else if (field !== fileInput) {
            //             field.classList.remove('border-red-500', 'focus:border-red-500');
            //         }
            //     });

            //     if (!fileInput.files.length) {
            //         isValid = false;
            //         fileMissing = true;
            //         dropzone.classList.add('border-red-500');
            //         fileError.classList.remove('hidden');
            //     } else {
            //         dropzone.classList.remove('border-red-500');
            //         fileError.classList.add('hidden');
            //     }

            //     if (!isValid) {
            //         if (fileMissing && ![...requiredFields].some(field => field !== fileInput && !field.value.trim())) {
            //             showErrorToast('A PDF file is required. Please upload a file.');
            //         } else if (!fileMissing && [...requiredFields].some(field => field !== fileInput && !field.value.trim())) {
            //             showErrorToast('Please fill in all required fields.');
            //         } else {
            //             showErrorToast('Please fill in all required fields and upload a PDF file.');
            //         }
            //         return;
            //     }

            //     // showSuccessToast('Creating module...');
            //     // setTimeout(() => {
            //         form.submit();
            //     // }, 1000);
            // });

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

            // Department -> Degree Program AJAX filter
            const deptSelect = document.getElementById('department_id');
            const courseSelect = document.getElementById('course_id');
            const currentCourseId = '{{ $module->course_id }}';

            function loadCourses(departmentId, preselectId) {
                if (!departmentId) {
                    courseSelect.innerHTML = '<option value="" disabled selected>Select a department first</option>';
                    courseSelect.disabled = true;
                    return;
                }
                courseSelect.innerHTML = '<option value="" disabled selected>Loading...</option>';
                courseSelect.disabled = true;

                fetch(`/api/departments/${departmentId}/courses`)
                    .then(r => r.json())
                    .then(courses => {
                        courseSelect.innerHTML = '<option value="" disabled selected>Select Degree Program</option>';
                        if (!courses.length) {
                            courseSelect.innerHTML = '<option value="" disabled selected>No programs available</option>';
                            courseSelect.disabled = true;
                            return;
                        }
                        courses.forEach(c => {
                            const opt = document.createElement('option');
                            opt.value = c.id;
                            opt.textContent = c.course_name;
                            if (preselectId && preselectId == c.id) opt.selected = true;
                            courseSelect.appendChild(opt);
                        });
                        courseSelect.disabled = false;
                    })
                    .catch(() => {
                        courseSelect.innerHTML = '<option value="" disabled selected>Error loading</option>';
                        courseSelect.disabled = true;
                    });
            }

            deptSelect.addEventListener('change', function () {
                loadCourses(this.value, null);
            });

            // On load, pre-select current course
            if (deptSelect.value) {
                loadCourses(deptSelect.value, currentCourseId);
            }
        });
    </script>
</x-faculty-layout>
