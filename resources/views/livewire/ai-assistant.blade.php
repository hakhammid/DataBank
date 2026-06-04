<div x-data="{ inputText: '' }">
    
    <!-- Floating AI Button -->
    @if(!$isOpen)
    <button wire:click="toggleChat" 
        class="fixed bottom-6 right-6 flex items-center gap-2 px-4 h-14 rounded-full bg-primary text-white shadow-lg hover:bg-primary/90 transition-all duration-300 hover:scale-105 hover:shadow-xl z-50 focus:outline-none focus:ring-4 focus:ring-primary/30">
        <!-- AI Sparkle Icon -->
        <svg class="w-6 h-6 shrink-0" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2L13.09 8.26L18 6L14.74 10.91L21 12L14.74 13.09L18 18L13.09 15.74L12 22L10.91 15.74L6 18L9.26 13.09L3 12L9.26 10.91L6 6L10.91 8.26L12 2Z"/>
        </svg>
        <span class="text-sm font-semibold whitespace-nowrap">AI Tutor</span>
    </button>
    @endif

    <!-- Chat Panel (right sidebar on all screen sizes) -->
    <div class="fixed top-0 right-0 h-full w-full sm:w-96 bg-white shadow-2xl z-40 transform transition-transform duration-300 ease-in-out {{ $isOpen ? 'translate-x-0' : 'translate-x-full' }} flex flex-col border-l border-zinc-200"
         @mouseenter="if(!$wire.isOpen) { /* prevent weird state */ }" >
         
        <!-- Header -->
        <div class="px-4 sm:px-6 py-4 border-b border-zinc-200 bg-zinc-50 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-primary" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2L13.09 8.26L18 6L14.74 10.91L21 12L14.74 13.09L18 18L13.09 15.74L12 22L10.91 15.74L6 18L9.26 13.09L3 12L9.26 10.91L6 6L10.91 8.26L12 2Z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-zinc-900">AI Tutor</h3>
                    <p class="text-xs text-zinc-500">Always here to help</p>
                </div>
            </div>
            
            <!-- Close Button -->
            <button wire:click="toggleChat" class="p-2 text-zinc-500 hover:text-zinc-800 bg-zinc-200/50 hover:bg-zinc-200 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-primary/20">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Messages Area -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-zinc-50/50">
            @foreach($messages as $message)
                <div class="flex {{ $message['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[85%] rounded-2xl px-4 py-3 text-sm shadow-sm {{ $message['role'] === 'user' ? 'bg-primary text-white rounded-br-sm' : 'bg-white border border-zinc-200 text-zinc-800 rounded-bl-sm' }}">
                        {!! nl2br(e($message['content'])) !!}
                    </div>
                </div>
            @endforeach
            
            <!-- Loading indicator -->
            <div wire:loading wire:target="sendMessage" class="flex justify-start">
                <div class="max-w-[85%] rounded-2xl px-4 py-3 text-sm shadow-sm bg-white border border-zinc-200 text-zinc-500 rounded-bl-sm flex items-center gap-2">
                    <div class="w-2 h-2 bg-zinc-400 rounded-full animate-bounce"></div>
                    <div class="w-2 h-2 bg-zinc-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                    <div class="w-2 h-2 bg-zinc-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-3 sm:p-4 bg-white border-t border-zinc-200">
            <form wire:submit.prevent="sendMessage" class="flex items-center gap-2">
                <div class="flex-1 min-w-0">
                    <textarea wire:model.live="input" x-model="inputText" rows="1" placeholder="Ask about this module..." 
                        class="w-full rounded-xl border border-zinc-300 bg-transparent px-4 py-2.5 text-sm text-zinc-900 shadow-theme-xs placeholder:text-zinc-400 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 resize-none overflow-hidden leading-normal" 
                        style="height: 42px; max-height: 120px;"
                        @keydown.enter.prevent="if(!$event.shiftKey && inputText.trim()) { $wire.sendMessage(); inputText = ''; this.style.height = '42px'; }"
                        @input="this.style.height = '42px'; this.style.height = Math.min(this.scrollHeight, 120) + 'px'"></textarea>
                </div>
                <button type="submit" 
                    class="flex-shrink-0 flex items-center justify-center w-[42px] h-[42px] rounded-xl bg-primary text-white shadow-theme-xs hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary/20 disabled:opacity-50 disabled:cursor-not-allowed"
                    wire:loading.attr="disabled"
                    :disabled="!inputText.trim()">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </button>
            </form>
            @error('input') <span class="text-xs text-error-500 mt-1">{{ $message }}</span> @enderror
        </div>
    </div>
</div>
