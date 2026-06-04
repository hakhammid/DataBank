<div wire:poll.30s="updateUnreadCount">
    {{-- Desktop Notification Bell --}}
    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open"
            class="relative flex items-center justify-center w-10 h-10 rounded-full hover:bg-zinc-100 transition-colors duration-150 focus:outline-none"
            aria-label="Notifications">
            {{-- Bell Icon --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-zinc-600" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9" />
                <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0" />
            </svg>

            {{-- Unread Badge --}}
            @if($unreadCount > 0)
                <span
                    class="absolute -top-0.5 -right-0.5 flex items-center justify-center min-w-[18px] h-[18px] px-1 text-[10px] font-bold text-white bg-red-500 rounded-full ring-2 ring-white animate-pulse">
                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                </span>
            @endif
        </button>

        {{-- Dropdown Panel --}}
        <div x-show="open" x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-1 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-1 scale-95"
            class="fixed inset-x-4 top-16 sm:absolute sm:inset-auto sm:right-0 sm:top-auto sm:mt-2 w-auto sm:w-96 rounded-xl shadow-xl bg-white ring-1 ring-zinc-200/70 z-[100] overflow-hidden"
            style="max-height: 28rem;">

            {{-- Header --}}
            <div class="flex items-center justify-between px-4 py-3 border-b border-zinc-100 bg-zinc-50/50">
                <div class="flex items-center gap-2">
                    <h3 class="text-sm font-semibold text-zinc-900">Notifications</h3>
                    @if($unreadCount > 0)
                        <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 text-[10px] font-bold text-white bg-red-500 rounded-full">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </div>
                @if($unreadCount > 0)
                    <button wire:click="markAllAsRead"
                        class="text-xs font-medium text-zinc-500 hover:text-zinc-900 transition-colors duration-150">
                        Mark all as read
                    </button>
                @endif
            </div>

            {{-- Notification List --}}
            <div class="overflow-y-auto" style="max-height: 22rem;">
                @forelse($notifications as $notification)
                    <div wire:key="notification-{{ $notification->id }}"
                        class="group relative flex items-start gap-3 px-4 py-3 border-b border-zinc-50 transition-colors duration-150 cursor-pointer
                            {{ is_null($notification->read_at) ? 'bg-blue-50/40 hover:bg-blue-50/70' : 'hover:bg-zinc-50' }}">

                        {{-- Unread Indicator Dot --}}
                        @if(is_null($notification->read_at))
                            <div class="flex-shrink-0 mt-1.5">
                                <span class="block w-2 h-2 rounded-full bg-blue-500"></span>
                            </div>
                        @else
                            <div class="flex-shrink-0 mt-1.5">
                                <span class="block w-2 h-2 rounded-full bg-transparent"></span>
                            </div>
                        @endif

                        {{-- Module Icon --}}
                        <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-zinc-900 flex items-center justify-center mt-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                                <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                            </svg>
                        </div>

                        {{-- Content --}}
                        @php
                            $notifUrl = '#';
                            if ($notification->module) {
                                if (Auth::user()->usertype === 'admin') {
                                    $notifUrl = route('admin.modules', ['tab' => 'pending']);
                                } elseif (Auth::user()->usertype === 'faculty') {
                                    $moduleCourse = $notification->module->courses->first();
                                    if ($moduleCourse) {
                                        $notifUrl = route('faculty.home', [
                                            'course_id' => $moduleCourse->id,
                                            'course_code' => $notification->module->course_code,
                                        ]);
                                    } else {
                                        $notifUrl = route('faculty.home');
                                    }
                                } else {
                                    $notifUrl = route('view-module', $notification->module->id);
                                }
                            }
                        @endphp
                        <a href="{{ $notifUrl }}"
                            wire:click="markAsRead({{ $notification->id }})"
                            class="flex-1 min-w-0">
                            <p class="text-sm text-zinc-800 leading-snug {{ is_null($notification->read_at) ? 'font-medium' : 'font-normal' }}">
                                {{ $notification->message }}
                            </p>
                            <p class="text-xs text-zinc-400 mt-1">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </a>

                        {{-- Mark as Read Button (for unread only) --}}
                        @if(is_null($notification->read_at))
                            <button wire:click.stop="markAsRead({{ $notification->id }})"
                                class="flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity duration-150 mt-1"
                                title="Mark as read">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-zinc-400 hover:text-zinc-700"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 6 9 17l-5-5" />
                                </svg>
                            </button>
                        @endif
                    </div>
                @empty
                    {{-- Empty State --}}
                    <div class="flex flex-col items-center justify-center py-10 px-4">
                        <div class="w-14 h-14 rounded-full bg-zinc-100 flex items-center justify-center mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-zinc-300" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9" />
                                <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-zinc-400">No notifications yet</p>
                        <p class="text-xs text-zinc-300 mt-1">You'll be notified when new modules are uploaded</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
