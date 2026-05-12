<div class="tooltip" wire:poll.5s>
    <div class="flex items-center gap-1 px-2 py-1 rounded-full bg-gray-50">
        <img src="{{ asset('images/thunder.png') }}" class="w-5 h-5 sm:w-6 sm:h-6 object-contain" alt="Quota"
            draggable="false">
        <span class="text-sm font-semibold text-gray-800" id="quota-display">
            {{ $remainingQuota }}/5
        </span>
    </div>
    <span class="tooltip-text">Daily Download Quota</span>
</div> 