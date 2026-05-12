<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>

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

            .mobile-menu-backdrop {
                @apply fixed inset-0 bg-black/20 backdrop-blur-sm z-40 transition-opacity duration-300;
            }

            .nav-list {
                @apply transition-transform duration-300 ease-in-out;
            }

            .page-content {
                @apply animate-fadeIn;
            }

            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }

            /* Toast Container */
            #toast-container {
                position: fixed;
                top: 2rem;
                left: 50%;
                transform: translateX(-50%);
                z-index: 9999;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 0.5rem;
                width: 100%;
                max-width: 24rem;
                pointer-events: none;
            }

            .toast {
                pointer-events: auto;
                width: 100%;
                padding: 1rem;
                border-radius: 0.5rem;
                background-color: white;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                display: flex;
                align-items: center;
                gap: 0.75rem;
                animation: slideDown 0.3s ease-out;
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
            }

            .toast.removing {
                animation: slideUp 0.3s ease-in forwards;
            }

            .toast-success {
                background-color: rgba(22, 196, 127, 0.95);
                color: white;
            }

            .toast-error {
                background-color: rgba(249, 84, 84, 0.95);
                color: white;
            }

            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateY(-100%);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes slideUp {
                from {
                    opacity: 1;
                    transform: translateY(0);
                }
                to {
                    opacity: 0;
                    transform: translateY(-100%);
                }
            }
        }
    </style>
    <style>
        html {
            overflow-y: scroll;
        }

        /* Mobile Menu Styles */
        .mobile-menu-enter {
            transition: opacity 0.2s ease-out, transform 0.2s ease-out;
        }

        .mobile-menu-enter-from {
            opacity: 0;
            transform: translateY(-8px);
        }

        .mobile-menu-enter-to {
            opacity: 1;
            transform: translateY(0);
        }

        .mobile-menu-leave {
            transition: opacity 0.15s ease-in, transform 0.15s ease-in;
        }

        .mobile-menu-leave-from {
            opacity: 1;
            transform: translateY(0);
        }

        .mobile-menu-leave-to {
            opacity: 0;
            transform: translateY(-8px);
        }

        .mobile-menu-backdrop {
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 40;
            transition: opacity 0.2s ease-in-out;
        }

        .mobile-menu-backdrop.entering {
            animation: fadeIn 0.2s ease-out forwards;
        }

        .mobile-menu-backdrop.leaving {
            animation: fadeOut 0.15s ease-in forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }

            to {
                opacity: 0;
            }
        }

        /* Prevent body scroll when menu is open */
        body.menu-open {
            overflow: hidden;
            position: fixed;
            width: 100%;
        }

        /* Mobile menu container */
        .mobile-menu-container {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
            padding-top: env(safe-area-inset-top);
            padding-bottom: env(safe-area-inset-bottom);
        }

        /* Mobile menu content */
        .mobile-menu-content {
            background: white;
            border-radius: 0 0 1rem 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            max-height: calc(100vh - env(safe-area-inset-top));
            overflow-y: auto;
            overscroll-behavior: contain;
            -webkit-overflow-scrolling: touch;
        }

        /* Hide scrollbar but allow scrolling */
        .mobile-menu-content::-webkit-scrollbar {
            display: none;
        }

        .mobile-menu-content {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Active state for mobile menu items */
        .mobile-menu-item.active {
            background-color: #18181b;
            color: white;
        }

        .mobile-menu-item.active:hover {
            background-color: #27272a;
        }

        /* Hover state for mobile menu items */
        .mobile-menu-item:hover:not(.active) {
            background-color: #f4f4f5;
        }

        /* Transition for mobile menu items */
        .mobile-menu-item {
            transition: all 0.2s ease-in-out;
        }
    </style>
    <style>
        html {
            overflow-y: scroll;
        }

        /* B  ackdrop Animation Styles */
        .backdrop-blur-none {
            backdrop-filter: blur(0);
            -webkit-backdrop-filter: blur(0);
            background-color: rgba(0, 0, 0, 0);
        }

        [x-show="showQuotaModal"] {
            transition-property: backdrop-filter, -webkit-backdrop-filter, background-color, opacity;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }

        [x-show="showQuotaModal"][x-transition\:enter-end],
        [x-show="showQuotaModal"][x-transition\:leave-start] {
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            background-color: rgba(0, 0, 0, 0.3);
        }
    </style>
</head>

<body class="font-sans antialiased">
    <!-- Toast Container -->
    <div id="toast-container" class="toast-container"></div>

    <!-- Global Success/Error Alert Modal -->
    <div x-data="{ show: @if(session('success') || session('error')) true @else false @endif }"
        x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition:enter="alert-enter"
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
                            d="M4.293 4.293a1 1 0 011.414 0L5.707 10l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
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
                            d="M10 18a8 8 0 100-16 8 8 0 0 0 0 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <p class="text-sm font-medium text-white flex-grow">{{ session('error') }}</p>
                <button @click="show = false" class="text-white/80 hover:text-white transition-colors">
                    <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L5.707 10l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        @endif
    </div>

    <div class="min-h-screen bg-white">
        @include('student.layouts.student-navigation')
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
                        Are you sure you want to logout?</h3>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="flex flex-col sm:flex-row justify-center gap-4">
                        @csrf
                        <x-my-secondary-button @click="showLogoutModal = false" type="button" class="w-full sm:w-auto justify-center bg-white text-primary">
                            Cancel
                        </x-my-secondary-button>
                        <x-my-button @click.prevent="if (typeof Livewire !== 'undefined') { Livewire.dispatch('logout'); } setTimeout(() => $event.target.closest('form').submit(), 50)" type='submit' class="w-full sm:w-auto justify-center bg-white text-primary">
                            Log out
                        </x-my-button>
                    </form>
                </div>
            </div>
        </div>
    </div>

        <!-- Delete Account Confirmation Modal -->
        <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
            <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900">
                    Are you sure you want to delete your account?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Once your account is deleted, all of its resources and data will be permanently deleted. Please
                    enter your password to confirm you would like to permanently delete your account.
                </p>

                <div class="mt-6">
                    <x-input-label for="password" value="Password" class="sr-only" />
                    <div class="mt-2">
                        <span data-slot="control"
                            class="relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white before:shadow after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent sm:after:focus-within:ring-2 sm:after:focus-within:ring-blue-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none before:has-[[data-invalid]]:shadow-red-500/10">
                            <input type="password" aria-label="Password"
                                class="relative block w-full appearance-none rounded-lg px-[calc(theme(spacing[3.5])-1px)] py-[calc(theme(spacing[2.5])-1px)] sm:px-[calc(theme(spacing[3])-1px)] sm:py-[calc(theme(spacing[1.5])-1px)] text-base/6 text-zinc-950 placeholder:text-zinc-500 sm:text-sm/6 border border-zinc-950/10 data-[hover]:border-zinc-950/20 bg-transparent focus:outline-none data-[invalid]:border-red-500 data-[invalid]:data-[hover]:border-red-500 data-[disabled]:border-zinc-950/20"
                                id="password" data-headlessui-state="" placeholder="" value="{{ old('password') }}"
                                name="password" required autofocus autocomplete="password">
                        </span>
                        <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-my-secondary-button type="button" x-on:click="$dispatch('close')">
                        Cancel
                    </x-my-secondary-button>
                    <x-my-danger-button class="ms-3">
                        Delete Account
                    </x-my-danger-button>
                </div>
            </form>
        </x-modal>
    </div>

    <!-- Load Scripts -->
    @livewireScripts
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>

    <script>
        if (!window.livewireInitialized) {
            document.addEventListener('livewire:init', () => {
                // Intercept 419 (CSRF expired) errors BEFORE Livewire shows the default dialog
                Livewire.hook('request', ({ fail }) => {
                    fail(({ status, preventDefault }) => {
                        if (status === 419) {
                            preventDefault();
                            // Silently reload page to get a fresh CSRF token
                            window.location.reload();
                        }
                    });
                });

                // Listen for Livewire events
                Livewire.on('toast', ({ type, message }) => {
                    showToast(type, message);
                });

                Livewire.on('updateSearchStatus', ({ search, hasResults }) => {
                    const stickySearchWrapper = document.getElementById('stickySearchWrapper');
                    const searchingMessage = document.getElementById('searchingMessage');
                    const resultsMessage = document.getElementById('resultsMessage');
                    const noResults = document.getElementById('noResults');
                    const searchQuery = document.getElementById('searchQuery');

                    if (!stickySearchWrapper || !searchingMessage || !resultsMessage || !noResults || !searchQuery) return;

                    if (!search) {
                        stickySearchWrapper.classList.add('hidden');
                        return;
                    }

                    stickySearchWrapper.classList.remove('hidden');
                    searchingMessage.classList.add('hidden');

                    if (hasResults) {
                        resultsMessage.classList.remove('hidden');
                        noResults.classList.add('hidden');
                        searchQuery.textContent = search;
                    } else {
                        resultsMessage.classList.add('hidden');
                        noResults.classList.remove('hidden');
                    }
                });
            });
            window.livewireInitialized = true;
        }

        // Initialize Alpine.js components only once
        if (!window.alpineComponentsInitialized) {
            document.addEventListener('alpine:init', () => {
                // Mobile Menu
                Alpine.data('mobileMenu', () => ({
                    open: false,
                    toggleMenu() {
                        this.open = !this.open;
                        document.body.classList.toggle('menu-open', this.open);
                        if (this.open) {
                            this.showBackdrop();
                        } else {
                            this.hideBackdrop();
                        }
                    },
                    showBackdrop() {
                        const backdrop = document.createElement('div');
                        backdrop.className = 'mobile-menu-backdrop entering';
                        backdrop.id = 'mobile-menu-backdrop';
                        backdrop.addEventListener('click', () => this.toggleMenu());
                        document.body.appendChild(backdrop);
                    },
                    hideBackdrop() {
                        const backdrop = document.getElementById('mobile-menu-backdrop');
                        if (backdrop) {
                            backdrop.classList.remove('entering');
                            backdrop.classList.add('leaving');
                            backdrop.addEventListener('animationend', () => backdrop.remove());
                        }
                    }
                }));
            });
            window.alpineComponentsInitialized = true;
        }

        // Toast Notification System
        function showToast(type, message) {
            const container = document.getElementById('toast-container');
            if (!container) return;

            // Remove any existing toasts
            while (container.firstChild) {
                container.removeChild(container.firstChild);
            }

            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;

            toast.innerHTML = `
            <div class="flex-shrink-0 w-6 h-6 flex items-center justify-center rounded-full bg-white/20">
                ${type === 'success'
                    ? '<svg class="w-4 h-4 text-white" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>'
                    : '<svg class="w-4 h-4 text-white" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 0 0 0 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>'}
            </div>
            <p class="text-sm font-medium text-white flex-grow">${message}</p>
        `;

            container.appendChild(toast);

            // Remove toast after 3 seconds
            setTimeout(() => {
                toast.classList.add('removing');
                toast.addEventListener('animationend', () => {
                    if (toast.parentElement) {
                        toast.remove();
                    }
                });
            }, 3000);
        }
    </script>
</body>

</html>
