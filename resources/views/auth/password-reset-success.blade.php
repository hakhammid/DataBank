<x-auth-layout :title="'Password Reset Success - ' . config('constants.APP_TITLE')">
    <div class="relative flex flex-col w-full min-h-screen sm:p-0 lg:flex-row">

        <div class="relative hidden lg:block lg:w-1/2 min-h-screen">
            <img src="{{ asset('logo/sign-in.jpg') }}" alt="Sign in background"
                class="absolute inset-0 w-full h-full object-cover" />
        </div>

        <div class="flex flex-col flex-1 w-full lg:w-1/2">
            <div class="flex flex-col justify-center flex-1 w-full max-w-md mx-auto px-4 sm:px-0">
                <div>
                    <div class="sm:mx-auto sm:w-full sm:max-w-sm flex flex-col items-center">
                        <!-- Success Icon -->
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-zinc-100 mb-6 border border-zinc-200">
                            <svg class="h-8 w-8 text-zinc-900" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        </div>
                        <h1 class="text-center text-3xl font-bold tracking-tight text-zinc-950">
                            Password Reset<br/>Successful
                        </h1>
                    </div>
                    <div class="mb-5 sm:mb-8 mt-4 text-center sm:mx-auto sm:w-full sm:max-w-sm">
                        <p class="text-sm text-zinc-600">
                            Your password has been successfully updated. You can now log in with your new password.
                        </p>
                    </div>

                    <div>
                        <div class="mt-8">
                            <a href="{{ route('login') }}"
                                class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-primary shadow-theme-xs hover:bg-primary/90">
                                Back to Login
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-auth-layout>
