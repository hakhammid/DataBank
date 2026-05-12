<x-guest-layout :title="'Password Reset Success'">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
        <div class="text-center">
            <!-- Success Icon -->
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-zinc-100 mb-4">
                <svg class="h-6 w-6 text-zinc-900" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>

            <h2 class="mb-2 text-center text-2xl font-medium tracking-tight text-zinc-900">
                Password Reset Successful
            </h2>

            <p class="text-sm text-center text-zinc-500 dark:text-zinc-400">
                Your password has been successfully updated. You can now log in with your new password.
            </p>
        </div>

        <div class="mt-8">
            <a href="{{ route('login') }}"
                class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-primary shadow-theme-xs hover:bg-primary/90">
                Back to Login
            </a>
        </div>
    </div>
</x-guest-layout>
