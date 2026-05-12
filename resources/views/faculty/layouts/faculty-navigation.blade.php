<nav x-data="{ open: false }"
     class="fixed top-0 left-0 lg:px-8 w-full z-50 bg-white lg:bg-transparent border-b lg:border-none shadow-sm lg:shadow-none">
    <div class="mx-auto max-w-8xl px-4 lg:px-4 py-2 lg:py-0 rounded-lg bg-white lg:bg-white/30 lg:backdrop-blur-lg">
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
                    <span class="text-xl font-bold">
                        {{ config('constants.APP_TITLE') }}
                    </span>
                </div>

                <div class="flex md:ml-6 md:items-center">
                    <div class="flex hidden md:flex">
                        <x-student-nav-link :href="route('faculty.home')" :active="request()->routeIs('faculty.home')">
                            Home
                        </x-student-nav-link>
                        <x-student-nav-link :href="route('faculty.module.create.view')" :active="request()->routeIs('faculty.module.create.view')">
                            Create Module
                        </x-student-nav-link>
                    </div>

                    {{-- @if (Request::is('faculty') || Request::is('faculty/home'))
                    <div class="items-center flex-1 max-w-3xl mx-4">
                        <div class="relative w-full max-w-3xl mx-auto">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <svg class="w-5 h-5 text-zinc-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1 0 6.75 6.75a7.5 7.5 0 0 0 9.9 9.9Z" />
                                </svg>
                            </div>
                            <input id="searchInput" type="text" placeholder="Search..."
                                   class="block w-full pl-12 pr-24 py-3 text-base text-zinc-900 placeholder-zinc-400
                                   bg-secondary/60 rounded-full border-none
                                   hover:bg-secondary
                                   focus:outline-none focus:ring-0 focus:border-transparent"
                                   autocomplete="off" />
                            <div class="absolute inset-y-0 right-0 flex items-center gap-2 pr-4">
                                <button id="clearBtn" type="button"
                                        class="text-zinc-900 hover:text-zinc-600 transition-opacity duration-200 ease-in-out opacity-0"
                                        onclick="clearSearch()">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif --}}
                </div>
            </div>

            <!-- Right Side -->
            <div class="flex items-center">
                <div x-data="{ open: false }" class="flex items-center relative group">
                    <button @click="open = !open" class="relative flex items-center gap-3 focus:outline-none"
                            id="profile-menu-button">
                        <div class="w-10 h-10 md:w-11 md:h-11 rounded-full overflow-hidden border border-zinc-300 shadow-sm">
                            <img src="{{ Auth::user()->profile_picture ? asset('images/' . Auth::user()->profile_picture) : asset('images/default_profile.png') }}"
                                 alt="Profile" class="w-full h-full object-cover" draggable="false"
                                 ondragstart="return false;" onselectstart="return false;" />
                        </div>
                    </button>

                    <div x-show="open" x-cloak
                         @click.away="if (!event.target.closest('#logout-modal')) { open = false }"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         class="absolute right-0 mt-10 w-48 z-50 origin-top-right">
                        <div class="absolute right-0 mt-2 w-56 rounded-lg shadow-lg bg-white ring-1 ring-zinc-100 block"
                             role="menu">
                            <div class="py-1">
                                <!-- Profile Shortcut -->
                                <a href="{{ route('faculty-profile') }}"
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
                                <a href="{{ route('faculty.change.password') }}"
                                   class="flex items-center gap-2 px-2 py-1 text-sm text-zinc-900 hover:bg-secondary/40 {{ request()->is('faculty/change-password') ? 'bg-secondary/40' : '' }}">
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
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round">
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
        <div class="md:hidden" x-show="open" x-cloak x-transition>
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white shadow-lg">
                <x-responsive-nav-link :href="route('faculty.home')" :active="request()->routeIs('faculty.home')">
                    Home
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('faculty.module.create.view')" :active="request()->routeIs('faculty.module.create.view')">
                    Create
                </x-responsive-nav-link>
            </div>
        </div>
    </div>
</nav>
