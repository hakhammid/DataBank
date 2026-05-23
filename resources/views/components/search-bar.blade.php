@props([
    'id' => 'search',
    'placeholder' => 'Search...',
    'name' => null,
])

<div class="w-full max-w-xs">
    <div class="relative">
        <input
            type="text"
            id="{{ $id }}"
            @if($name) name="{{ $name }}" @endif
            placeholder="{{ $placeholder }}"
            class="block w-full py-2 pl-3 pr-9 border border-zinc-300 rounded-lg bg-white text-sm text-zinc-900 placeholder-zinc-400 focus:ring-2 focus:ring-zinc-900 focus:border-zinc-900 transition-all duration-200"
            {{ $attributes }}
        >
        <!-- Magnifying glass icon (visible when input is empty) -->
        <div id="{{ $id }}-search-icon" class="absolute inset-y-0 right-0 mr-2.5 flex items-center pointer-events-none transition-opacity duration-200">
            <svg class="h-4 w-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
        </div>
        <!-- Clear button (visible when input has text) -->
        <button
            type="button"
            id="{{ $id }}-clear"
            class="absolute inset-y-0 right-0 mr-2 flex items-center opacity-0 pointer-events-none transition-opacity duration-200 hover:text-zinc-600"
        >
            <svg class="h-4 w-4 text-zinc-400 hover:text-zinc-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <!-- Search Results Status -->
    <div id="{{ $id }}-status" class="mt-2 hidden">
        <div class="flex items-center gap-1.5 text-xs text-zinc-500">
            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span id="{{ $id }}-results-text"></span>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('{{ $id }}');
    const clearBtn = document.getElementById('{{ $id }}-clear');
    const searchIcon = document.getElementById('{{ $id }}-search-icon');

    if (searchInput && clearBtn && searchIcon) {
        function toggleIcons() {
            const hasText = searchInput.value.trim().length > 0;
            clearBtn.style.opacity = hasText ? '1' : '0';
            clearBtn.style.pointerEvents = hasText ? 'auto' : 'none';
            searchIcon.style.opacity = hasText ? '0' : '1';
        }

        searchInput.addEventListener('input', toggleIcons);

        clearBtn.addEventListener('click', function () {
            searchInput.value = '';
            toggleIcons();
            searchInput.focus();
            searchInput.dispatchEvent(new Event('input', { bubbles: true }));
        });

        searchInput.addEventListener('keyup', function (e) {
            if (e.key === 'Escape') {
                searchInput.value = '';
                toggleIcons();
                searchInput.blur();
                searchInput.dispatchEvent(new Event('input', { bubbles: true }));
            }
        });

        toggleIcons();
    }
});
</script>
