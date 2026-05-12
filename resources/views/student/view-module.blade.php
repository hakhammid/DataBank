<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $module->title }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        /* Toast Container */
        .toast-container {
            position: fixed;
            top: 2rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            width: 100%;
            max-width: 24rem;
            pointer-events: none;
            padding: 0 1rem;
        }

        /* Toast */
        .toast {
            pointer-events: auto;
            width: 100%;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 1.25rem;
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            animation: slideDown 0.3s ease-out;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        .toast.removing {
            animation: slideUp 0.3s ease-in forwards;
        }

        .toast-success {
            background-color: rgba(22, 196, 127, 0.95);
            color: white;
        }

        .toast-error {
            background-color: rgba(249, 84, 84, 0.95);
            color: white;
        }

        /* Toast Icon Container */
        .toast-icon {
            flex-shrink: 0;
            width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            background: rgba(255, 255, 255, 0.1);
        }

        /* Toast Content */
        .toast-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .toast-title {
            font-weight: 600;
            font-size: 0.875rem;
            line-height: 1.25rem;
        }

        .toast-message {
            font-size: 0.875rem;
            line-height: 1.25rem;
            opacity: 0.9;
        }

        /* Header Styles */
        .module-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background-color: white;
            z-index: 50;
            transition: all 0.3s ease;
        }

        .module-header.shadow-header {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        /* Module Title Container */
        .module-title-container {
            max-width: 100%;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            text-overflow: ellipsis;
        }

        /* PDF Container */
        .pdf-container {
            width: 100%;
            height: 100vh;
            padding-top: 4rem;
            background-color: #f3f4f6;
            position: relative;
        }

        .pdf-viewer {
            width: 100%;
            height: 100%;
            border: none;
            background: transparent;
        }

        /* Loading State */
        .pdf-loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        /* Responsive Adjustments */
        @media (max-width: 640px) {
            .module-header {
                padding: 0.75rem 1rem;
            }

            .module-title-container {
                font-size: 1rem;
            }

            .header-actions {
                gap: 0.5rem;
            }
        }

        @media (min-width: 641px) {
            .module-header {
                padding: 1rem 2rem;
            }

            .module-title-container {
                font-size: 1.25rem;
            }

            .header-actions {
                gap: 1rem;
            }
        }

        /* Animations */
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-100%);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                transform: translateY(-100%);
            }
        }

        /* Loading Animation */
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }

        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        /* Tooltip */
        .tooltip {
            position: relative;
            display: inline-block;
        }

        .tooltip .tooltip-text {
            visibility: hidden;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            color: white;
            text-align: center;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
            white-space: nowrap;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }

        /* Prevent body scroll when loading */
        body.loading {
            overflow: hidden;
        }
    </style>
</head>

<body class="bg-gray-100">
    <!-- Toast Container -->
    <div id="toast-container" class="toast-container"></div>

    <!-- Fixed Header -->
    <header class="module-header" id="moduleHeader">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3 flex-1 min-w-0">
                <div class="module-title-container">
                    <h1 class="font-semibold text-gray-900 truncate flex items-center gap-2">
                        <span class="hidden sm:inline">({{ $module->course_code }})</span>
                        <span class="truncate">{{ $module->title }}</span>
                    </h1>
                </div>
            </div>

            <div class="flex items-center header-actions">
                <!-- Quota Display -->
                @livewire('quota-display')

                <!-- Download Button -->
                <div class="tooltip">
                    @livewire('download-module', ['module' => $module], key('download-module-'.$module->id))
                    <span class="tooltip-text">Download Module</span>
                </div>

                <!-- Close Button -->
                <div class="tooltip">
                    <button onclick="handleBack()"
                        class="flex items-center justify-center w-10 h-10 rounded-full bg-zinc-900 hover:bg-zinc-800 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-zinc-600">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <span class="tooltip-text">Close</span>
                </div>
            </div>
        </div>

        <!-- Mobile Course Code -->
        <div class="sm:hidden mt-1 text-sm text-gray-500">
            {{ $module->course_code }}
        </div>
    </header>

    <!-- PDF Container -->
    <main class="pdf-container" id="pdfContainer">
        <!-- Loading State -->
        <div class="pdf-loading" id="pdfLoading">
            <div class="animate-pulse flex flex-col items-center">
                <svg class="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="mt-2 text-sm text-gray-500">Loading PDF...</span>
            </div>
        </div>

        <!-- PDF Viewer -->
        <iframe
            src="{{ asset('files/' . $module->file) }}#toolbar=0&navpanes=0&view=FitH"
            class="pdf-viewer"
            id="pdfViewer"
            type="application/pdf"
            onload="handlePdfLoad()"
            onerror="handlePdfError()">
        </iframe>
    </main>

    @livewireScripts
    <script>
        function handleBack() {
            const urlParams = new URLSearchParams(window.location.search);
            const source = urlParams.get('source');

            window.history.back();
        }

        // Toast Notification System
        function showToast(type, message) {
            const container = document.getElementById('toast-container');
            if (!container) return;

            // Remove any existing toasts
            while (container.firstChild) {
                container.removeChild(container.firstChild);
            }

            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;

            toast.innerHTML = `
                <div class="toast-icon">
                    ${type === 'success'
                        ? '<svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>'
                        : '<svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 0 0 0 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>'}
                </div>
                <div class="toast-content">
                    <div class="toast-title">${type === 'success' ? 'Success' : 'Error'}</div>
                    <div class="toast-message">${message}</div>
                </div>
            `;

            container.appendChild(toast);

            setTimeout(() => {
                toast.classList.add('removing');
                toast.addEventListener('animationend', () => {
                    if (toast.parentElement) {
                        toast.remove();
                    }
                });
            }, 3000);
        }

        // PDF Loading Handling
        function handlePdfLoad() {
            const loading = document.getElementById('pdfLoading');
            const viewer = document.getElementById('pdfViewer');
            if (loading) {
                loading.style.opacity = '0';
                setTimeout(() => {
                    loading.style.display = 'none';
                    viewer.style.opacity = '1';
                }, 300);
            }
            document.body.classList.remove('loading');
        }

        function handlePdfError() {
            const loading = document.getElementById('pdfLoading');
            if (loading) {
                loading.innerHTML = `
                    <div class="flex flex-col items-center text-center">
                        <svg class="w-12 h-12 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span class="mt-2 text-sm text-gray-500">Failed to load PDF</span>
                        <button onclick="location.reload()"
                            class="mt-4 px-4 py-2 bg-zinc-900 text-white rounded-lg hover:bg-zinc-800 transition-colors">
                            Retry
                        </button>
                    </div>
                `;
            }
        }

        // Header Shadow on Scroll
        function handleScroll() {
            const header = document.getElementById('moduleHeader');
            if (header) {
                if (window.scrollY > 0) {
                    header.classList.add('shadow-header');
                } else {
                    header.classList.remove('shadow-header');
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize loading state
            document.body.classList.add('loading');

            // Listen for Livewire events
            Livewire.on('toast', ({ type, message }) => {
                showToast(type, message);
            });

            // Listen for quota updates from any component
            Livewire.on('quotaUpdated', (data) => {
                if (data && typeof data.remainingQuota !== 'undefined') {
                    const quotaDisplay = document.getElementById('quota-display');
                    if (quotaDisplay) {
                        quotaDisplay.textContent = `${data.remainingQuota}/5`;
                    }
                }
            });

            // Add scroll listener
            window.addEventListener('scroll', handleScroll);

            // Handle escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    handleBack();
                }
            });
        });
    </script>
</body>

</html>
