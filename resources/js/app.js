import './bootstrap';
import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';

// Initialize Alpine.js only once
if (typeof window.Alpine === 'undefined') {
    window.Alpine = Alpine;
    Alpine.plugin(persist);
    Alpine.start();
}

// // Initialize Livewire event listeners only once
// if (typeof window.livewireInitialized === 'undefined') {
//     document.addEventListener('livewire:init', () => {
//         Livewire.on('toast', ({ type, message }) => {
//             showToast(type, message);
//         });

//         Livewire.on('updateSearchStatus', ({ search, hasResults }) => {
//             const stickySearchWrapper = document.getElementById('stickySearchWrapper');
//             const searchingMessage = document.getElementById('searchingMessage');
//             const resultsMessage = document.getElementById('resultsMessage');
//             const noResults = document.getElementById('noResults');
//             const searchQuery = document.getElementById('searchQuery');

//             if (!stickySearchWrapper || !searchingMessage || !resultsMessage || !noResults || !searchQuery) return;

//             if (!search) {
//                 stickySearchWrapper.classList.add('hidden');
//                 return;
//             }

//             stickySearchWrapper.classList.remove('hidden');
//             searchingMessage.classList.add('hidden');

//             if (hasResults) {
//                 resultsMessage.classList.remove('hidden');
//                 noResults.classList.add('hidden');
//                 searchQuery.textContent = search;
//             } else {
//                 resultsMessage.classList.add('hidden');
//                 noResults.classList.remove('hidden');
//             }
//         });
//     });
//     window.livewireInitialized = true;
// }

// // Toast Notification System
// function showToast(type, message) {
//     const container = document.getElementById('toast-container');
//     if (!container) return;

//     // Remove any existing toasts
//     while (container.firstChild) {
//         container.removeChild(container.firstChild);
//     }

//     const toast = document.createElement('div');
//     toast.className = `toast toast-${type}`;

//     toast.innerHTML = `
//         <div class="flex-shrink-0 w-6 h-6 flex items-center justify-center rounded-full bg-white/20">
//             ${type === 'success'
//                 ? '<svg class="w-4 h-4 text-white" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>'
//                 : '<svg class="w-4 h-4 text-white" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 0 0 0 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>'}
//         </div>
//         <p class="text-sm font-medium text-white flex-grow">${message}</p>
//     `;

//     container.appendChild(toast);

//     // Remove toast after 3 seconds
//     setTimeout(() => {
//         toast.classList.add('removing');
//         toast.addEventListener('animationend', () => {
//             if (toast.parentElement) {
//                 toast.remove();
//             }
//         });
//     }, 3000);
// }

// import './bootstrap';
// import Alpine from 'alpinejs';
// import focus from '@alpinejs/focus';

// // Initialize Alpine
// // window.Alpine = Alpine;
// Alpine.plugin(focus);
// Alpine.start();
