<?php
$title = 'Home';
?>

<x-student-layout :title="$title">
    <header>
        <style>
            @keyframes grow {
                0% {
                    transform: scale(1);
                }

                50% {
                    transform: scale(1.5);
                }

                100% {
                    transform: scale(1);
                }
            }

            .animate-grow {
                animation: grow 0.3s ease-in-out;
            }

            .backdrop-blur {
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
            }

            .backdrop-blur body {
                overflow: hidden;
            }

            input[type="search"]::-webkit-search-cancel-button {
                -webkit-appearance: none;
                appearance: none;
                display: none;
            }

            .hover-actions {
                position: absolute;
                bottom: 1rem;
                left: 50%;
                transform: translateX(-50%);
                opacity: 0;
                transition: all 0.2s ease-in-out;
                background-color: rgba(0, 0, 0, 0.6);
                padding: 0.75rem 1rem;
                border-radius: 0.75rem;
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
                display: flex;
                gap: 0.5rem;
                flex-wrap: wrap;
                justify-content: center;
            }

            .pdf-card:hover .hover-actions {
                opacity: 1;
                transform: translateX(-50%) translateY(-5px);
            }

            .border-error-500 {
                border-color: #ef4444 !important;
            }

            .text-error-500 {
                color: #ef4444 !important;
            }

            .focus\:border-error-500:focus {
                border-color: #ef4444 !important;
            }

            .sticky-search-status {
                position: fixed;
                top: 4.5rem;
                left: 50%;
                transform: translateX(-50%);
                z-index: 50;
                padding: 1rem;
                pointer-events: none;
            }

            .search-status-container {
                margin: 0 auto;
                padding: 0 1rem;
                pointer-events: auto;
            }

            .filter-container {
                position: sticky;
                top: 4rem;
                left: 0;
                right: 0;
                z-index: 40;
                margin-top: 1rem;
                background: white;
                padding-bottom: 1rem;
            }

            .filter-wrapper {
                max-width: 1480px;
            }

            .choice-chips {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
            }

            @media (max-width: 767px) {
                .choice-chips {
                    display: flex;
                    flex-wrap: nowrap;
                    overflow-x: auto;
                    -webkit-overflow-scrolling: touch;
                    scrollbar-width: none;
                    padding-bottom: 0.5rem;
                    justify-content: flex-start;
                }

                .choice-chips::-webkit-scrollbar {
                    display: none;
                }

                .choice-chip {
                    flex-shrink: 0;
                    white-space: nowrap;
                }
            }

            .choice-chip {
                display: inline-flex;
                align-items: center;
                cursor: pointer;
                transition: all 0.2s ease;
            }

            .choice-chip.active {
                background-color: #000 !important;
                color: white !important;
            }

            .choice-chip.active:hover {
                background-color: rgba(0, 0, 0, 0.9) !important;
            }

            /* Search bar positioning */
            .search-bar-container {
                position: relative;
                max-width: 600px;
                width: 100%;
            }

            .search-input {
                width: 100%;
                padding-left: 3rem;
                padding-right: 3rem;
                border-radius: 9999px;
                background-color: rgba(245, 245, 245, 0.6);
                border: none;
                transition: all 0.2s ease-in-out;
            }

            .search-input:hover {
                background-color: rgb(245, 245, 245);
            }

            .search-input:focus {
                outline: none;
                background-color: rgb(245, 245, 245);
                box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.1);
            }

            .search-icon {
                position: absolute;
                left: 1rem;
                top: 50%;
                transform: translateY(-50%);
                pointer-events: none;
            }

            .clear-button {
                position: absolute;
                right: 1rem;
                top: 50%;
                transform: translateY(-50%);
                transition: opacity 0.2s ease-in-out;
            }
        </style>
    </header>
    @livewire('browse-modules')

    <script>
        // Wait   for DOM content to be loaded
       if (typeof window.searchModuleInitialized === 'undefined') {
           document.addEventListener('DOMContentLoaded', function() {
                 const searchInput = document.getElementById('searchInput');
                 const clearBtn = document.getElementById('clearBtn');
                 const searchStatus = document.getElementById('searchStatus');
                 const searchingMessage = document.getElementById('searchingMessage');
                 const resultsMessage = document.getElementById('resultsMessage');
                 const noResults = document.getElementById('noResults');
                 const searchQuery = document.getElementById('searchQuery');
                 const stickySearchWrapper = document.getElementById('stickySearchWrapper');

                 if (!searchInput || !clearBtn || !searchStatus || !searchingMessage || !resultsMessage || !noResults || !searchQuery || !stickySearchWrapper) return;

                 // Initial state
                 toggleClearButton();

                 // L isten for input changes
                 searchInput.addEventListener('input', () => {
                 toggleClearButton();
                     showSearchingMessage();
               });

               function toggleClearButton() {
                     clearBtn.style.opacity = searchInput.value ? '1' : '0';
                 }

                    function  showSearchingMessage() {
                     if (!searchInput.value) {
                     stickySearchWrapper.classList.add('hidden');
                         return;
                     }

                       stickySearchWrapper.classList.remove('hidden');
                     searchingMessage.classList.remove('hidden');
                     resultsMessage.classList.add('hidden');
                 noResults.classList.add('hidden');
                 }
             });

                // Initialize Livewire event listeners only once
             document.addEventListener('livewire:init', () => {
                 Livewire.on('updateSearchStatus', ({ search, hasResults }) => {
                     const stickySearchWrapper = document.getElementById('stickySearchWrapper');
                     const searchingMessage = document.getElementById('searchingMessage');
                 const resultsMessage = document.getElementById('resultsMessage');
                     const noResults = document.getElementById('noResults');
                 const searchQuery = document.getElementById('searchQuery');

                     if ( !stickySearchWrapper || !searchingMessage || !resultsMessage || !noResults || !searchQuery) return;

                       if (!search) {
                     stickySearchWrapper.classList.add('hidden');
                         return;
                     }

                     stickySearchWrapper.classList.remove('hidden');
                  searchingMessage.classList.add('hidden');

                        if (hasResults) {
                         resultsMessage.classList.remove('hidden');
                         noResults.classList.add('hidden');
                         searchQuery.textContent = search;
                     } else {
                         resultsMessage.classList.add('hidden');
                         noResults.classList.remove('hidden');
                 }
                 });

                 // Handle logout event
                 Livewire.on('logout', () => {
                     // Clear any search state
                     const searchInput = document.getElementById('searchInput');
                     if (searchInput) {
                         searchInput.value = '';
                     }

                     // Hide search status
                     const stickySearchWrapper = document.getElementById('stickySearchWrapper');
                     if (stickySearchWrapper) {
                         stickySearchWrapper.classList.add('hidden');
                     }
                 });
             });

            window.searchModuleInitialized = true;
         }
     </script>

</x-student-layout>
