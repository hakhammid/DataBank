@props([
    'id' => 'custom-modal',
    'title' => 'Modal Title',
    'showIcon' => true,
    'iconType' => 'warning', 
    'maxWidth' => 'lg'
])

<div id="{{ $id }}" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
  <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"></div>
  <div class="fixed inset-0 z-50 w-screen overflow-y-auto">
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
      <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-{{ $maxWidth }}">
        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
          <div class="sm:flex sm:items-start">
            @if($showIcon)
            <div class="mx-auto flex size-12 shrink-0 items-center justify-center rounded-full {{ $iconType === 'warning' ? 'bg-red-100' : ($iconType === 'info' ? 'bg-blue-100' : ($iconType === 'success' ? 'bg-green-100' : 'bg-gray-100')) }} sm:mx-0 sm:size-10">
              @if($iconType === 'warning')
              <svg class="size-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
              </svg>
              @elseif($iconType === 'info')
              <svg class="size-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
              </svg>
              @elseif($iconType === 'success')
              <svg class="size-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              @else
              <svg class="size-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
              </svg>
              @endif
            </div>
            @endif
            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left {{ !$showIcon ? 'w-full' : '' }}">
              <h3 class="text-base font-semibold text-gray-900" id="modal-title">{{ $title }}</h3>
              <div class="mt-2">
                {{ $slot }}
              </div>
            </div>
          </div>
        </div>
        <div class="bg-white px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
          {{ $footer ?? '' }}
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Find triggers for this specific modal
    const modalId = "{{ $id }}";
    const openModalButtons = document.querySelectorAll(`[data-modal-target="${modalId}"]`);
    const modal = document.getElementById(modalId);
    
    if (!modal) return;
    
    // Function to open the modal
    const openModal = () => {
      modal.classList.remove("hidden");
      document.body.classList.add("overflow-hidden");
    };
    
    // Function to close the modal
    const closeModal = () => {
      modal.classList.add("hidden");
      document.body.classList.remove("overflow-hidden");
    };
    
    // Event listeners to open the modal
    openModalButtons.forEach(button => {
      button.addEventListener("click", openModal);
    });
    
    // Event listeners to close the modal
    const closeModalButtons = modal.querySelectorAll("[data-modal-close]");
    closeModalButtons.forEach(button => {
      button.addEventListener("click", closeModal);
    });
    
    // Close on background click
    modal.addEventListener("click", (e) => {
      if (e.target === modal) {
        closeModal();
      }
    });
    
    // Close on ESC key
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && !modal.classList.contains("hidden")) {
        closeModal();
      }
    });
});
</script>