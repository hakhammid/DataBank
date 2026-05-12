<div>
    <div class="hidden md:flex items-center gap-1 px-2 rounded-full cursor-pointer group relative" wire:poll.5s x-data="{
                    showQuotaModal: false,
                    toggleModal() {
                        this.showQuotaModal = !this.showQuotaModal;
                        document.body.style.overflow = this.showQuotaModal ? 'hidden' : '';
                    }
                }" @click="toggleModal">
        <img src="{{ asset('images/thunder.png') }}" class="w-6 h-6 object-contain" alt="Quota" draggable="false"
            ondragstart="return false;" onselectstart="return false;">
        <span class="text-sm font-semibold text-gray-800" wire:model="remainingQuota">{{ $remainingQuota }}</span>

        <!-- Quota Info Modal -->
        <template x-teleport="body">
            <div x-show="showQuotaModal" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 backdrop-blur-none"
                x-transition:enter-end="opacity-100 backdrop-blur-[4px]"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 backdrop-blur-[4px]"
                x-transition:leave-end="opacity-0 backdrop-blur-none" class="fixed inset-0 z-50"
                @click.self="toggleModal" style="background: rgba(0, 0, 0, 0);">

                <!-- Modal Content -->
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div class="relative bg-white rounded-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto overscroll-contain p-8 shadow-xl"
                        style="scroll-behavior: smooth;">
                        <!-- Close Button -->
                        <button @click="toggleModal"
                            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <!-- Modal Header -->
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-indigo-50 rounded-full flex items-center justify-center">
                                <img src="{{ asset('images/thunder.png') }}" class="w-8 h-8 object-contain" alt="Quota">
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Download Quota System</h3>
                                <p class="text-sm text-gray-600">Understanding your daily module downloads</p>
                            </div>
                        </div>

                        <!-- Current Quota Status -->
                        <div class="bg-gray-50 rounded-xl p-4 mb-6">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-600">Today's Remaining Downloads</span>
                                <span class="text-2xl font-bold text-gray-900">{{ $remainingQuota }}/5</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-500"
                                    style="width: {{ ($remainingQuota / 5) * 100 }}%"></div>
                            </div>
                        </div>

                        <!-- Quota Information -->
                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <div
                                    class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900">Daily Refresh</h4>
                                    <p class="text-sm text-gray-600">Your quota resets every day at midnight, giving you
                                        5 new downloads.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div
                                    class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900">Download Tracking</h4>
                                    <p class="text-sm text-gray-600">Each successful module download reduces your daily
                                        quota by one.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div
                                    class="flex-shrink-0 w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-yellow-600" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900">Important Note</h4>
                                    <p class="text-sm text-gray-600">Unused downloads don't carry over to the next day.
                                        Make the most of your daily quota!</p>
                                </div>
                            </div>
                        </div>

                        <!-- Tips Section -->
                        <div class="mt-6 bg-indigo-50 rounded-xl p-4">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                                <h4 class="text-sm font-semibold text-indigo-900">Pro Tips</h4>
                            </div>
                            <ul class="text-sm text-indigo-800 space-y-1 ml-4 list-disc">
                                <li>Plan your downloads to make the most of your daily quota</li>
                                <li>Check your remaining quota before downloading</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>
