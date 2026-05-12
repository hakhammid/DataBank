<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style type="text/tailwindcss">
        @layer utilities {
            .checkbox-wrapper input:checked ~ .checkmark::after {
                position: absolute;
                top: 50%;
                left: 50%;
                height: 7px;
                width: 7px;
                --tw-translate-y: -50%;
                --tw-translate-x: -50%;
                transform: translate(var(--tw-translate-x), var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y));
                border-radius: 9999px;
                --tw-bg-opacity: 1;
                background-color: rgb(16 23 73 / var(--tw-bg-opacity));
                content: '';
            }

            .group:hover .group-hover\:invert {
                --tw-invert: invert(100%);
                filter: var(--tw-blur) var(--tw-brightness) var(--tw-contrast) var(--tw-grayscale) var(--tw-hue-rotate) var(--tw-invert) var(--tw-saturate) var(--tw-sepia) var(--tw-drop-shadow);
            }

            /* Alert animations */
            @keyframes slideIn {
                from { transform: translate(-50%, -100%); opacity: 0; }
                to { transform: translate(-50%, 0); opacity: 1; }
            }

            @keyframes slideOut {
                from { transform: translate(-50%, 0); opacity: 1; }
                to { transform: translate(-50%, -100%); opacity: 0; }
            }

            .alert-enter {
                animation: slideIn 0.3s ease-out forwards;
            }

            .alert-leave {
                animation: slideOut 0.3s ease-in forwards;
            }

            /* Mobile menu backdrop */
            .mobile-menu-backdrop {
                @apply fixed inset-0 bg-black/20 backdrop-blur-sm z-40 transition-opacity duration-300;
            }

            /* Navigation transitions */
            .nav-list {
                @apply transition-transform duration-300 ease-in-out;
            }

            /* Content fade-in animation */
            .page-content {
                @apply animate-fadeIn;
            }

            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
        }
    </style>
    <style>
        html {
            overflow-y: scroll;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <!-- Global Success/Error Alert Modal -->
    <div x-data="{ show: @if(session('success') || session('error')) true @else false @endif }"
         x-init="setTimeout(() => show = false, 3000)"
         x-show="show"
         x-transition:enter="alert-enter"
         x-transition:leave="alert-leave"
         class="fixed top-4 left-1/2 transform -translate-x-1/2 z-[100] w-full max-w-md">
        @if(session('success'))
        <div class="rounded-lg p-4 bg-[#16C47F] flex items-center gap-3 shadow-lg">
            <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-white/20">
                <svg class="w-5 h-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                          d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                          clip-rule="evenodd" />
                </svg>
            </div>
            <p class="text-sm font-medium text-white flex-grow">{{ session('success') }}</p>
            <button @click="show = false" class="text-white/80 hover:text-white transition-colors">
                <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                          d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                          clip-rule="evenodd" />
                </svg>
            </button>
        </div>
        @endif

        @if(session('error'))
        <div class="rounded-lg p-4 bg-[#F95454] flex items-center gap-3 shadow-lg">
            <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-white/20">
                <svg class="w-5 h-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                          d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                          clip-rule="evenodd" />
                </svg>
            </div>
            <p class="text-sm font-medium text-white flex-grow">{{ session('error') }}</p>
            <button @click="show = false" class="text-white/80 hover:text-white transition-colors">
                <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                          d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                          clip-rule="evenodd" />
                </svg>
            </button>
        </div>
        @endif
    </div>

    <div class="min-h-screen bg-white">
        @include('faculty.layouts.faculty-navigation')

        <!-- Page Content -->
        <main class="mt-5">
            {{ $slot }}
        </main>

        <!-- Logout Confirmation Modal -->
        <div x-data="{ showLogoutModal: false }"
             @open-logout-modal.window="showLogoutModal = true"
             @keydown.escape.window="showLogoutModal = false"
             x-show="showLogoutModal"
             x-cloak
             id="logout-modal" tabindex="-1" role="dialog" aria-labelledby="logout-modal-title" aria-modal="true"
             class="fixed inset-0 z-[100] flex justify-center items-center w-full h-full bg-black/50 p-4">
            
            <div class="relative w-full max-w-md max-h-full"
                 @click.away="showLogoutModal = false"
                 x-show="showLogoutModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <div class="relative bg-primary rounded-2xl shadow-2xl text-white">
                    <div class="p-6 text-center">
                        <h3 id="logout-modal-title"
                            class="mb-6 text-center text-white text-lg font-medium">
                            Are you sure you want to logout?
                        </h3>
                        <form action="{{ route('logout') }}" method="POST" class="flex flex-col sm:flex-row justify-center gap-4">
                            @csrf
                            <x-my-secondary-button @click="showLogoutModal = false" type="button" class="w-full sm:w-auto justify-center bg-white text-primary">
                                Cancel
                            </x-my-secondary-button>
                            <x-my-button type="submit" class="w-full sm:w-auto justify-center bg-white text-primary">
                                Log out
                            </x-my-button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
            <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900">
                    Are you sure you want to delete your account?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Once your account is deleted, all of its resources and data will be permanently deleted.
                    <br>Please enter your username and password to confirm.
                </p>

                <div class="mt-6">
                    <!-- Hidden Username Field for Accessibility -->
                    <input type="text" name="username" value="{{ Auth::user()->email ?? '' }}" autocomplete="username" class="hidden">

                    <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                    <div class="mt-2">
                        <span data-slot="control"
                              class="relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white before:shadow after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent sm:after:focus-within:ring-2 sm:after:focus-within:ring-zinc-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none before:has-[[data-invalid]]:shadow-red-500/10">
                            <input type="password" autocomplete="current-password" aria-label="User Password"
                                   class="relative block w-full appearance-none rounded-lg px-[calc(theme(spacing[3.5])-1px)] py-[calc(theme(spacing[2.5])-1px)] sm:px-[calc(theme(spacing[3])-1px)] sm:py-[calc(theme(spacing[1.5])-1px)] text-base/6 text-zinc-950 placeholder:text-zinc-500 sm:text-sm/6 border border-zinc-950/10 data-[hover]:border-zinc-950/20 bg-transparent focus:outline-none data-[invalid]:border-red-500 data-[invalid]:data-[hover]:border-red-500 data-[disabled]:border-zinc-950/20"
                                   id="password" placeholder="Password" value="{{ old('password') }}"
                                   name="password" required autofocus>
                        </span>
                        <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-my-secondary-button type="button" x-on:click="$dispatch('close-modal', 'confirm-user-deletion')">
                        Cancel
                    </x-my-secondary-button>
                    <x-my-danger-button class="ms-3">
                        Delete Account
                    </x-my-danger-button>
                </div>
            </form>
        </x-modal>
    </div>

    @livewireScripts
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Search Functionality
            const searchInput = document.getElementById('searchInput');
            const clearBtn = document.getElementById('clearBtn');
            const searchStatus = document.getElementById('searchStatus');
            const searchingMsg = document.getElementById('searchingMessage');
            const resultsMsg = document.getElementById('resultsMessage');
            const searchQuery = document.getElementById('searchQuery');
            const noResults = document.getElementById('noResults');
            const stickySearchWrapper = document.getElementById('stickySearchWrapper');

            if (searchInput && clearBtn && searchStatus && searchingMsg && resultsMsg && searchQuery && noResults && stickySearchWrapper) {
                function toggleClearButton() {
                    clearBtn.style.opacity = searchInput.value === '' ? '0' : '1';
                }

                function normalizeText(text) {
                    return text.toLowerCase().replace(/\s+/g, '');
                }

                function clearSearch() {
                    searchInput.value = '';
                    toggleClearButton();
                    searchInput.focus();
                    updateSearchResults();
                }

                function updateSearchResults() {
                    const searchTerm = normalizeText(searchInput.value.trim());
                    const cards = document.querySelectorAll('.module-card');
                    let found = false;

                    cards.forEach(card => {
                        const name = normalizeText(card.dataset.name || '');
                        const code = normalizeText(card.dataset.code || '');
                        if (searchTerm === '' || name.includes(searchTerm) || code.includes(searchTerm)) {
                            card.classList.remove('hidden');
                            found = true;
                        } else {
                            card.classList.add('hidden');
                        }
                    });

                    searchingMsg.classList.add('hidden');
                    if (searchTerm) {
                        resultsMsg.classList.remove('hidden');
                        searchQuery.textContent = searchInput.value;
                        noResults.classList.toggle('hidden', found);
                    } else {
                        resultsMsg.classList.add('hidden');
                        noResults.classList.add('hidden');
                    }

                    stickySearchWrapper.classList.toggle('hidden', searchTerm === '');
                }

                searchInput.addEventListener('input', () => {
                    toggleClearButton();
                    updateSearchResults();
                });
            }

            // Module Upload/Edit Handling
            function setupModuleForm(moduleId) {
                const updateDropzone = document.getElementById(`update-dropzone-${moduleId}`);
                const updateFileInput = document.getElementById(`update-dropzone-file-${moduleId}`);
                const updatePreview = document.getElementById(`update-file-preview-${moduleId}`);
                const updateFileNameSpan = document.getElementById(`update-selected-file-name-${moduleId}`);
                const updateModuleIdInput = document.getElementById(`updateModuleId-${moduleId}`);

                if (updateDropzone && updateFileInput && updatePreview && updateFileNameSpan && updateModuleIdInput) {
                    const selectedModuleId = updateModuleIdInput.value;

                    updateDropzone.addEventListener("dragenter", (e) => {
                        e.preventDefault();
                        updateDropzone.classList.remove("border-gray-200");
                        updateDropzone.classList.add("border-blue-600");
                    }, { passive: true });

                    updateDropzone.addEventListener("dragover", (e) => {
                        e.preventDefault();
                        updateDropzone.classList.add("border-blue-600");
                    }, { passive: true });

                    updateDropzone.addEventListener("dragleave", () => {
                        updateDropzone.classList.remove("border-blue-600");
                        updateDropzone.classList.add("border-gray-200");
                    }, { passive: true });

                    updateDropzone.addEventListener("drop", (e) => {
                        e.preventDefault();
                        updateDropzone.classList.remove("border-blue-600");
                        updateDropzone.classList.add("border-gray-200");

                        const file = e.dataTransfer.files[0];
                        if (file && file.type === "application/pdf") {
                            updateFileInput.files = e.dataTransfer.files;
                            updateFileNameSpan.textContent = file.name;
                            updatePreview.classList.remove("hidden");
                            console.log("PDF dropped:", file.name);
                        } else {
                            window.dispatchEvent(new CustomEvent('open-modal', { detail: selectedModuleId }));
                        }
                    });

                    updateFileInput.addEventListener("change", function() {
                        const file = this.files[0];
                        if (file && file.type === "application/pdf") {
                            updateFileNameSpan.textContent = file.name;
                            updatePreview.classList.remove("hidden");
                        } else {
                            window.dispatchEvent(new CustomEvent('open-modal', { detail: selectedModuleId }));
                            this.value = "";
                        }
                    });
                }

                const uploadModuleForm = document.getElementById(`updateModuleForm-${moduleId}`);
                if (uploadModuleForm) {
                    uploadModuleForm.addEventListener('submit', function(e) {
                        const requiredFields = this.querySelectorAll('[required]');
                        let isValid = true;

                        requiredFields.forEach(field => {
                            if (!field.value) {
                                isValid = false;
                                field.classList.add('border-error-500', 'focus:border-error-500');
                                const label = this.querySelector(`label[for="${field.id}"]`);
                                if (label) label.classList.add('text-error-500');
                            }
                        });

                        const fileInput = document.getElementById(`update-dropzone-file-${moduleId}`);
                        if (!fileInput || !fileInput.files.length) {
                            isValid = false;
                            const dropzone = document.getElementById(`update-dropzone-${moduleId}`);
                            if (dropzone) dropzone.classList.add('border-error-500');
                        }

                        if (!isValid) {
                            e.preventDefault();
                            const modalHtml = `
                                <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-data="{ show: true }" x-show="show">
                                    <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                                        <div class="flex items-center gap-3 mb-4">
                                            <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full bg-red-100">
                                                <svg class="w-6 h-6 text-red-600" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-medium text-gray-900">Missing Information</h3>
                                        </div>
                                        <p class="text-sm text-gray-600 mb-6">Please fill in all required fields and upload a PDF file.</p>
                                        <div class="flex justify-end">
                                            <button @click="show = false" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                OK
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                            const modalContainer = document.createElement('div');
                            modalContainer.innerHTML = modalHtml;
                            document.body.appendChild(modalContainer);
                            setTimeout(() => modalContainer.remove(), 5000);
                        }
                    });

                    document.querySelectorAll(`#updateModuleForm-${moduleId} [required]`).forEach(field => {
                        field.addEventListener('input', function() {
                            this.classList.remove('border-error-500', 'focus:border-error-500');
                            const label = document.querySelector(`label[for="${this.id}"]`);
                            if (label) label.classList.remove('text-error-500');
                        });

                        if (field.tagName === 'SELECT') {
                            field.addEventListener('change', function() {
                                this.classList.remove('border-error-500', 'focus:border-error-500');
                                const label = document.querySelector(`label[for="${this.id}"]`);
                                if (label) label.classList.remove('text-error-500');
                            });
                        }
                    });

                    const dropzoneFile = document.getElementById(`update-dropzone-file-${moduleId}`);
                    if (dropzoneFile) {
                        dropzoneFile.addEventListener('change', function() {
                            if (this.files.length) {
                                const dropzone = document.getElementById(`update-dropzone-${moduleId}`);
                                if (dropzone) dropzone.classList.remove('border-error-500');
                            }
                        });
                    }
                }
            }

            // Initialize module forms for each module
            document.querySelectorAll('[id^="updateModuleId-"]').forEach(input => {
                const moduleId = input.id.replace('updateModuleId-', '');
                setupModuleForm(moduleId);
            });

            // Remove file function for edit module form
            window.removeUpdateFile = function(moduleId) {
                const updateFileInput = document.getElementById(`update-dropzone-file-${moduleId}`);
                const updatePreview = document.getElementById(`update-file-preview-${moduleId}`);
                const updateFileNameSpan = document.getElementById(`update-selected-file-name-${moduleId}`);
                if (updateFileInput && updatePreview && updateFileNameSpan) {
                    updateFileInput.value = "";
                    updatePreview.classList.add("hidden");
                    updateFileNameSpan.textContent = "No file selected";
                }
            };
        });

        document.addEventListener('alpine:init', () => {
            Alpine.data('moduleModal', () => ({
                init() {
                    this.$watch('showModal', value => {
                        if (!value) {
                            document.body.style.overflow = '';
                            document.body.classList.remove('backdrop-blur');
                        }
                    });
                }
            }));
        });
    </script>
</body>
</html>
