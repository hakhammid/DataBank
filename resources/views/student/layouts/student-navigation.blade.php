<nav x-data="{ open: false }"
        class="fixed top-0 left-0 lg:px-4 w-full z-50 bg-white lg:bg-transparent border-b lg:border-none shadow-sm lg:shadow-none">
        <div class="mx-auto max-w-8xl px-4 lg:px-4 py-2 lg:py-0 rounded-lg bg-white shadow-sm">
            <div class="flex h-16 items-center justify-between">
                <div class="flex items-center">
                    <!-- Mobile menu button -->
                    <div class="flex md:hidden mr-4">
                        <button @click="open = !open" type="button"
                            class="inline-flex items-center justify-center rounded-md text-zinc-900 hover:text-zinc-900/90">
                            <span class="sr-only">Open main menu</span>
                            <!-- Hamburger -->
                            <svg class="h-6 w-6" x-show="!open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M4 12h16" />
                                <path d="M4 18h16" />
                                <path d="M4 6h16" />
                            </svg>
                            <!-- Close -->
                            <svg class="h-6 w-6" x-show="open" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="block text-xl font-bold">
                            {{ config('constants.APP_TITLE') }}
                        </span>
                    </div>

                    <div class="flex md:ml-6 md:items-center ">
                        <div class="flex hidden md:flex">
                            <x-student-nav-link :href="route('student')" :active="request()->routeIs('student')">
                                Home
                            </x-student-nav-link>

                        </div>
                    </div>
                </div>

                <!-- Right Side -->
                <div class="flex md:gap-5 gap-0 items-center">
                    <!-- Download Quota -->
                    @livewire('download-quota')

                    <!-- Profile Dropdown -->
                    <div x-data="{ open: false }" class="hidden md:flex items-center relative group">
                        <button @click="open = !open" class="relative flex items-center gap-3 focus:outline-none"
                            id="profile-menu-button">
                            <div
                                class="w-10 h-10 md:w-11 md:h-11 rounded-full overflow-hidden border border-zinc-300 shadow-sm">
                                <img src="{{ Auth::user()->profile_picture ? asset('images/' . Auth::user()->profile_picture) : asset('images/default_profile.png') }}"
                                    alt="Profile" class="w-full h-full object-cover" draggable="false"
                                    ondragstart="return false;" onselectstart="return false;" />
                            </div>
                        </button>
                        <div x-show="open" x-cloak
                            @click.away="if (!event.target.closest('#logout-modal')) { open = false }"
                            class="absolute right-0 mt-10 w-48">
                            <div class="absolute right-0 mt-2 w-56 rounded-lg shadow-lg bg-white ring-1 ring-zinc-100 z-50 block"
                                role="menu">
                                <div class="py-1">
                                    <a href="{{ route('student-profile') }}"
                                        class="flex items-center gap-3 p-2 hover:bg-zinc-100 rounded-lg transition">
                                        <img src="{{ Auth::user()->profile_picture ? asset('images/' . Auth::user()->profile_picture) : asset('images/default_profile.png') }}"
                                            alt="Profile"
                                            class="w-10 h-10 rounded-full object-cover border border-zinc-300 shadow-sm"
                                            draggable="false" ondragstart="return false;" onselectstart="return false;">
                                        <div>
                                            <p class="text-sm font-medium text-zinc-900">{{ Auth::user()->name }}</p>
                                            <p class="text-xs text-zinc-500">View Profile</p>
                                        </div>
                                    </a>
                                    <a href="{{ route('student.change.password.view') }}"
                                        class="flex items-center gap-2 px-2 py-1 text-sm text-zinc-900 hover:bg-secondary/40">
                                        <div class="rounded-full bg-secondary p-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-zinc-900"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <rect width="18" height="11" x="3" y="11" rx="2" ry="2" />
                                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                            </svg>
                                        </div>
                                        Change Password
                                    </a>
                                    <button type="button" @click.prevent="$dispatch('open-logout-modal')"
                                        class="w-full text-zinc-900 text-left flex items-center gap-2 px-2 py-1 text-sm hover:bg-secondary/40">
                                        <div class="rounded-full bg-secondary p-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-zinc-900"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="m16 17 5-5-5-5" />
                                                <path d="M21 12H9" />
                                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                            </svg>
                                        </div>
                                        Log out
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div class="md:hidden" x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4">
                <div class="px-2 pt-2 pb-3 space-y-1 bg-white shadow-lg rounded-b-xl">
                    <!-- Profile Section -->
                    <div class="p-4 border-b">
                        <a href="{{ route('student-profile') }}" class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full overflow-hidden border border-zinc-200">
                                <img src="{{ Auth::user()->profile_picture ? asset('images/' . Auth::user()->profile_picture) : asset('images/default_profile.png') }}"
                                    alt="Profile" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-zinc-900">{{ Auth::user()->name }}</h4>
                                <p class="text-xs text-zinc-500">View Profile</p>
                            </div>
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="px-3 py-2">
                        <div class="space-y-1">
                            <a href="{{ route('student') }}"
                                class="flex items-center gap-3 w-full px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('student') ? 'bg-zinc-900 text-white' : 'text-zinc-700 hover:bg-zinc-100' }} transition-colors duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Home
                            </a>

                        </div>
                    </div>

                    <!-- Download Quota (Mobile) -->
                    {{-- <div class="px-3 py-2 border-t">
                        <div class="flex items-center justify-between px-3 py-2 text-sm font-medium text-zinc-700">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-zinc-100 flex items-center justify-center">
                                    <img src="{{ asset('images/thunder.png') }}" class="w-5 h-5 object-contain"
                                        alt="Quota">
                                </div>
                                <span>Download Quota</span>
                            </div>
                            <span class="text-zinc-900 font-semibold">{{ $remainingQuota }}/5</span>
                        </div>
                    </div> --}}

                    <!-- Account Actions -->
                    <div class="px-3 py-2 border-t space-y-1">
                        <a href="{{ route('student.change.password.view') }}"
                            class="flex items-center gap-3 w-full px-3 py-2 text-sm font-medium text-zinc-700 rounded-lg hover:bg-zinc-100 transition-colors duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Change Password
                        </a>

                        <button type="button" @click.prevent="$dispatch('open-logout-modal')"
                            class="flex items-center gap-3 w-full px-3 py-2 text-sm font-medium text-zinc-700 rounded-lg hover:bg-zinc-100 transition-colors duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Log out
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>
