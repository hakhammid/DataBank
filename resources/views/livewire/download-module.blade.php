<div>
    <!-- Download Button (Triggers Modal) -->
    <button x-data
            x-on:click.prevent="$dispatch('open-modal', 'confirm-download-{{ $module->id }}')"
            wire:loading.attr="disabled"
            wire:target="download"
            class="ml-4 bg-zinc-900 rounded-full p-2 transition-all duration-200 focus:outline-none relative group hover:bg-zinc-800 w-10 h-10 inline-flex items-center justify-center">
        <div wire:target="download" class="w-6 h-6 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6 text-white">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                <polyline points="7 10 12 15 17 10" />
                <line x1="12" x2="12" y1="15" y2="3" />
            </svg>
        </div>
        <span class="absolute top-full left-1/2 -translate-x-1/2 mt-2 px-3 py-1.5 bg-white text-zinc-800 text-xs rounded shadow-md opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 whitespace-nowrap border border-zinc-100 z-50">
            <span wire:loading.remove wire:target="download">Download</span>
            <span wire:loading wire:target="download">Preparing Download...</span>
        </span>
    </button>

    <!-- Confirmation Modal -->
    <x-modal name="confirm-download-{{ $module->id }}" maxWidth="md" focusable>
        <div class="p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="flex size-10 shrink-0 items-center justify-center rounded-full bg-zinc-100">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-5 text-zinc-900">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-zinc-900">
                    Confirm Download
                </h3>
            </div>

            <p class="text-sm text-zinc-600 leading-relaxed">
                Are you sure you want to download this module? This action will reduce your daily download quota by 1.
            </p>

            <div class="mt-6 flex justify-end gap-3">
                <x-my-secondary-button type="button" x-on:click="$dispatch('close')">
                    Cancel
                </x-my-secondary-button>
                <x-my-button wire:click="download" x-on:click="$dispatch('close')">
                    Confirm
                </x-my-button>
            </div>
        </div>
    </x-modal>
</div>

<script>
document.addEventListener('livewire:initialized', () => {
    // Listen for toast events
    Livewire.on('toast', (data) => {
        const params = Array.isArray(data) ? data[0] : data;
        if (typeof showToast === 'function') {
            showToast(params.type, params.message);
        }
    });

    // Listen for quota updates
    Livewire.on('quotaUpdated', (data) => {
        const params = Array.isArray(data) ? data[0] : data;
        if (params && typeof params.remainingQuota !== 'undefined') {
            const quotaDisplays = document.querySelectorAll('#quota-display');
            quotaDisplays.forEach(display => {
                display.textContent = `${params.remainingQuota}/5`;
            });
        }
    });

    // Listen for download initiation
    Livewire.on('initiate-download', (data) => {
        const params = Array.isArray(data) ? data[0] : data;
        const token = params?.token;

        if (token) {
            // Use a hidden iframe to trigger the download without navigating away
            setTimeout(() => {
                const iframe = document.createElement('iframe');
                iframe.style.display = 'none';
                iframe.src = `/download/file/${token}`;
                document.body.appendChild(iframe);

                // Clean up the iframe after download starts
                setTimeout(() => {
                    if (iframe.parentNode) {
                        iframe.parentNode.removeChild(iframe);
                    }
                }, 10000);
            }, 500);
        }
    });
});
</script>
