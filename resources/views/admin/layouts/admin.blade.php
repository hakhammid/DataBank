<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full text-zinc-950 antialiased bg-white ">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Document' }}</title>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-bold-rounded/css/uicons-bold-rounded.css'>
    <!-- DataTables and Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

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

                /* Enhanced table styles */
                .enhanced-table {
                    @apply border-collapse w-full;
                }

                .enhanced-table th {
                    @apply bg-zinc-50 text-zinc-700 font-semibold px-4 py-3 border-b border-zinc-200;
                }

                .enhanced-table td {
                    @apply px-4 py-3 border-b border-zinc-100;
                }

                .enhanced-table tbody tr:hover {
                    @apply bg-zinc-50 transition-colors duration-150;
                }

                /* Export button styles */
                .export-btn {
                    @apply inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition-all duration-200;
                }

                .export-btn-excel {
                    @apply bg-green-600 text-white hover:bg-green-700;
                }

                .export-btn-pdf {
                    @apply bg-red-600 text-white hover:bg-red-700;
                }

                .export-btn-print {
                    @apply bg-blue-600 text-white hover:bg-blue-700;
                }

                /* Search input enhancements */
                .search-input-enhanced {
                    @apply block w-full pl-10 pr-10 py-3 border border-zinc-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200;
                }

                /* Dropdown animations */
                .dropdown-enter {
                    @apply transition-all duration-200 ease-out;
                }

                .dropdown-enter-start {
                    @apply opacity-0 scale-95;
                }

                .dropdown-enter-end {
                    @apply opacity-100 scale-100;
                }

                .dropdown-leave {
                    @apply transition-all duration-150 ease-in;
                }

                .dropdown-leave-start {
                    @apply opacity-100 scale-100;
                }

                .dropdown-leave-end {
                    @apply opacity-0 scale-95;
                }
            }
    </style>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="h-full" x-data="{ 'loaded': true}" x-cloak>
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
                    <form action="{{ route('logout') }}" method="POST" class="flex flex-col sm:flex-row justify-center gap-4">
                        @csrf
                        <x-my-secondary-button @click="showLogoutModal = false" type="button"
                            class="w-full sm:w-auto justify-center bg-white text-primary">
                            Cancel
                        </x-my-secondary-button>
                        <x-my-button type='submit' class="w-full sm:w-auto justify-center bg-white text-primary">
                            Log out
                        </x-my-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="flex h-screen" x-data="{
            ...setup(),
            loaded: false,
            init() {
                this.$refs.loading.classList.remove('hidden');
                setup().init.call(this);
                // Show loading for 3 seconds before showing content
                setTimeout(() => {
                    this.loaded = true;
                    this.$refs.loading.classList.add('hidden');
                }, 300);
            }
        }" x-ref="root">
            <!-- Loading element to reference with x-init -->
            <div x-ref="loading" class="hidden">
            </div>

            <!-- Sidebar backdrop -->
            <div x-show="isSidebarOpen" x-cloak
                x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="closeSidebar()"
                class="fixed inset-0 z-40 bg-black/50 lg:hidden" style="
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
          "></div>

            <!-- SIDEBAR -->
            <aside id="sidebar"
                class="fixed inset-y-0 left-0 z-50 flex flex-col flex-shrink-0 w-64 max-h-screen overflow-hidden bg-white shadow-2xl transition-transform duration-300 ease-in-out lg:z-auto lg:static lg:shadow-none lg:translate-x-0"
                :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full'">
                <!-- SEPARATOR -->
                <div class="border-t border-zinc-950/10 dark:border-white/10 w-full mb-4 lg:hidden"></div>
                <!-- SIDEBAR HEADER TITLE -->
                <div class="fixed inset-y-0 left-0 w-64 flex flex-col pointer-events-none">
                    <nav class="flex h-full min-h-0 flex-col pointer-events-auto">
                        <div class="flex items-center justify-between border-b border-zinc-950/5 p-4 dark:border-white/5"
                            aria-hidden="true" inert="">
                            <span class="relative">
                                <button id="headlessui-menu-button-:r2:" type="button" aria-haspopup="menu"
                                    aria-expanded="false" data-headlessui-state=""
                                    class="cursor-default flex w-full items-center gap-3 rounded-lg py-2.5 text-left text-base/6 font-medium text-zinc-950 sm:py-2 sm:text-sm/5">
                                    <span class="truncate text-xl font-bold">
                                        {{ config('constants.APP_TITLE') }}
                                    </span>
                                </button>
                            </span>
                        </div>
                        <!-- aria-hidden="true" inert="" -->
                        <div
                            class="flex flex-1 flex-col overflow-y-auto p-4 [&amp;>[data-slot=section]+[data-slot=section]]:mt-8">
                            <div data-slot="section" class="flex flex-col gap-0.5">
                                <div>
                                    <x-admin-nav-link href="{{ route('admin.dashboard') }}"
                                        :active="request()->is('admin/dashboard')">
                                        <x-slot:icon>
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="h-5 w-5">
                                                <rect width="7" height="9" x="3" y="3" rx="1" />
                                                <rect width="7" height="5" x="14" y="3" rx="1" />
                                                <rect width="7" height="9" x="14" y="12" rx="1" />
                                                <rect width="7" height="5" x="3" y="16" rx="1" />
                                            </svg>
                                        </x-slot:icon>
                                        Admin Dashboard
                                    </x-admin-nav-link>
                                </div>

                                <div>
                                    <x-admin-nav-link href="{{ route('admin.modules') }}"
                                        :active="request()->is('admin/modules')">
                                        <x-slot:icon>
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="h-5 w-5">
                                                <path d="m16 6 4 14" />
                                                <path d="M12 6v14" />
                                                <path d="M8 8v12" />
                                                <path d="M4 4v16" />
                                            </svg>
                                        </x-slot:icon>
                                        Modules
                                    </x-admin-nav-link>
                                </div>
                                <div>
                                    <x-admin-nav-link href="{{ route('admin.students') }}"
                                        :active="request()->is('admin/students')">
                                        <x-slot:icon>
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="h-5 w-5">
                                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                                <circle cx="9" cy="7" r="4" />
                                                <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                            </svg>
                                        </x-slot:icon>
                                        Students
                                    </x-admin-nav-link>
                                </div>
                                <div>
                                    <x-admin-nav-link href="{{ route('admin.faculties') }}"
                                        :active="request()->is('admin/faculties')">
                                        <x-slot:icon>
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="h-5 w-5">
                                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                                <circle cx="9" cy="7" r="4" />
                                                <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                            </svg>
                                        </x-slot:icon>
                                        Faculty
                                    </x-admin-nav-link>
                                </div>

                                <div>
                                    <x-admin-nav-link href="{{ route('admin.departments') }}"
                                        :active="request()->is('admin/departments')">
                                        <x-slot:icon>
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="h-5 w-5">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M3 21l18 0" />
                                                <path d="M5 21v-14l8 -4v18" />
                                                <path d="M19 21v-10l-6 -4" />
                                                <path d="M9 9l0 .01" />
                                                <path d="M9 12l0 .01" />
                                                <path d="M9 15l0 .01" />
                                                <path d="M9 18l0 .01" />
                                            </svg>
                                        </x-slot:icon>
                                        Departments
                                    </x-admin-nav-link>
                                </div>

                                <div>
                                    <x-admin-nav-link href="{{ route('admin.degree-program') }}"
                                        :active="request()->is('admin/degree-program')">
                                        <x-slot:icon>
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="h-5 w-5">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M22 9l-10 -4l-10 4l10 4l10 -4v6" />
                                                <path d="M6 10.6v5.4a6 3 0 0 0 12 0v-5.4" />
                                            </svg>
                                        </x-slot:icon>
                                        Degree Programs
                                    </x-admin-nav-link>
                                </div>
                                <div>
                                    <x-admin-nav-link href="{{ route('reports.summary') }}"
                                        :active="request()->is('reports*')">
                                        <x-slot:icon>
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="h-5 w-5">
                                                <path d="M15 12h-5" />
                                                <path d="M15 8h-5" />
                                                <path d="M19 17V5a2 2 0 0 0-2-2H4" />
                                                <path
                                                    d="M8 21h12a2 2 0 0 0 2-2v-1a1 1 0 0 0-1-1H11a1 1 0 0 0-1 1v1a2 2 0 1 1-4 0V5a2 2 0 1 0-4 0v2a1 1 0 0 0 1 1h3" />
                                            </svg>
                                        </x-slot:icon>
                                        Reports
                                    </x-admin-nav-link>
                                </div>
                            </div>

                            {{-- SET THE BOTTOM SIDEBAR LINKS TO END --}}
                            <div aria-hidden="true" class="mt-1 flex-1">

                            </div>

                    </nav>
                </div>
            </aside>

            <!-- MAIN CONTENT CONTAINER -->
            <div class="flex flex-col flex-1 h-full overflow-y-scroll pb-2 pr-2">
                <div class="flex-shrink-0 rounded-lg lg:border-[0.5px] md:border-[0px] border-zinc-950/10">
                    <!-- NAVBAR MENU -->
                    <header
                        class="flex-shrink-0 sticky top-0 z-30 bg-white border-b border-zinc-950/10 w-full transition-all duration-300 rounded-t-lg">
                        <div class="flex items-center justify-between p-2 lg:px-4 lg:py-3">
                            <div class="flex lg:hidden items-center space-x-3">
                                <button id="sidebar-toggle" @click="toggleSidebarMenu()" class="p-2 rounded-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="md:h-6 md:w-6 w-7 h-7">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4 8l16 0" />
                                        <path d="M4 16l16 0" />
                                    </svg>
                                </button>
                            </div>

                            <div class="hidden lg:flex items-center">
                                <!-- Top Left Desktop Content -->
                            </div>

                            <!-- NAVBAR PROFILE -->
                            <div class="relative flex items-center space-x-4 ml-auto">
                                <!-- Notification Bell -->
                                @livewire('notification-bell')
                                
                                <div class="relative" x-data="{ isProfileMenuOpen: false }">
                                    <button @click="isProfileMenuOpen = !isProfileMenuOpen" class="flex items-center gap-2 p-1 rounded-md hover:bg-zinc-100 transition-colors focus:outline-none">
                                        <img class="object-cover w-9 h-9 md:w-10 md:h-10 rounded-full bg-gray-200 border-[0.5px] border-zinc-950/10"
                                            src="{{ Auth::user()->profile_picture ? asset('images/' . Auth::user()->profile_picture) : asset('images/default_profile.png') }}"
                                            alt="Profile Picture" draggable="false" ondragstart="return false;"
                                            onselectstart="return false;" />
                                        <div class="hidden lg:block text-left mr-1">
                                            <p class="text-sm font-medium text-zinc-900 leading-tight">{{ Auth::user()->name }}</p>
                                            <p class="text-xs text-zinc-500 leading-tight">{{ Auth::user()->email }}</p>
                                        </div>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="hidden lg:block h-4 w-4 text-zinc-500 transition-transform duration-300" 
                                             viewBox="0 0 20 20" fill="currentColor"
                                             :class="{ 'transform rotate-180': isProfileMenuOpen }">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <!-- NAVBAR PROFILE DROPDOWN -->
                                    <div @click.away="isProfileMenuOpen = false" x-show="isProfileMenuOpen" x-cloak
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 transform scale-95"
                                        x-transition:enter-end="opacity-100 transform scale-100"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100 transform scale-100"
                                        x-transition:leave-end="opacity-0 transform scale-95"
                                        class="z-50 absolute right-0 mt-2 bg-white rounded-md shadow-lg w-[14rem] origin-top-right border border-zinc-100">
                                        <ul class="flex flex-col p-2 my-2 space-y-1">
                                            <div>
                                                <x-admin-nav-link href="{{ route('admin-profile') }}"
                                                    :active="request()->is('admin-profile')">
                                                    <x-slot:icon>
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="h-5 w-5">
                                                            <path d="M18 20a6 6 0 0 0-12 0" />
                                                            <circle cx="12" cy="10" r="4" />
                                                            <circle cx="12" cy="12" r="10" />
                                                        </svg>
                                                    </x-slot:icon>
                                                    Profile
                                                </x-admin-nav-link>
                                            </div>
                                            <div>
                                                <x-admin-nav-link href="{{ route('admin-change-password') }}"
                                                    :active="request()->is('admin-change-password')">
                                                    <x-slot:icon>
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="h-5 w-5">
                                                            <rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
                                                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                                        </svg>
                                                    </x-slot:icon>
                                                    Change Password
                                                </x-admin-nav-link>
                                            </div>
                                            <!-- SEPARATOR -->
                                            <div class="border-t border-zinc-950/10 dark:border-white/10 w-full my-1">
                                            </div>
                                            <!-- SIGN OUT -->
                                            <div>
                                                <x-admin-nav-link type="button" @click.prevent="$dispatch('open-logout-modal')"
                                                    style="cursor: pointer;">
                                                    <x-slot:icon>
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor" stroke-width="2"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            class="h-5 w-5">
                                                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                                            <polyline points="16 17 21 12 16 7" />
                                                            <line x1="21" x2="9" y1="12" y2="12" />
                                                        </svg>
                                                    </x-slot:icon>
                                                    Sign Out
                                                </x-admin-nav-link>
                                            </div>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </header>
                    <!-- MAIN CONTENT -->
                    <div class="relative min-h-[calc(100vh-4rem)]">
                        {{-- LOADING STATE --}}
                        {{-- <div x-ref="loading" x-show="!loaded"
                            class="absolute inset-0 z-[50] flex items-center justify-center bg-white bg-opacity-100">
                            <div
                                class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-primary border-t-transparent">
                            </div>
                        </div> --}}
                        {{-- x-show="loaded" --}}
                        <div x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Additional Libraries for Export -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

        <!-- Initialize Flowbite -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
        <script>
            const setup = () => {
                const isLargeScreen = window.innerWidth >= 1024;
                return {
                    loading: true
                    , isSidebarOpen: isLargeScreen
                    , toggleSidebarMenu() {
                        this.isSidebarOpen = !this.isSidebarOpen;
                    }
                    , closeSidebar() {
                        if (window.innerWidth < 1024) {
                            this.isSidebarOpen = false;
                        }
                    }
                    , isSettingsPanelOpen: false
                    , isSearchBoxOpen: false
                    , isProfileMenuOpen: false
                    , init() {
                        window.addEventListener('resize', () => {
                            if (window.innerWidth >= 1024) {
                                this.isSidebarOpen = true;
                            }
                        });

                        document.addEventListener('click', (e) => {
                            const sidebar = document.querySelector('#sidebar');
                            const toggleBtn = document.querySelector('#sidebar-toggle');
                            if (this.isSidebarOpen && window.innerWidth < 1024 &&
                                sidebar && !sidebar.contains(e.target) &&
                                toggleBtn && !toggleBtn.contains(e.target)) {
                                this.closeSidebar();
                            }
                        });

                        // Close modal when clicking outside
                    }
                };
            };

        </script>

        <script>
            function previewImage(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        document.getElementById('preview-image').src = e.target.result;
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }

        </script>
    </div>
</body>

</html>
