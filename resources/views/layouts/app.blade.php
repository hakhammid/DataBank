<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Alpine.js -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- Styles -->
        @livewireStyles

        <!-- Livewire Scripts -->
        @livewireScripts
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <script>
            // Global Livewire error handling
            document.addEventListener('livewire:init', () => {
                Livewire.on('error', (error) => {
                    console.error('Livewire error:', error);
                    // If it's a 419 error (CSRF token mismatch), redirect to login
                    if (error.status === 419) {
                        // Show a user-friendly message before redirecting
                        alert('Session expired. Redirecting to login...');
                        setTimeout(() => {
                            window.location.href = '/login';
                        }, 1000);
                    }
                });

                // Handle Livewire session expiration
                Livewire.on('session-expired', () => {
                    alert('Session expired. Redirecting to login...');
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 1000);
                });
            });
        </script>
    </body>
</html>
