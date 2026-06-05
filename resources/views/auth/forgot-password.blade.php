<x-auth-layout :title="'Forgot Password - ' . config('constants.APP_TITLE')">
    <div class="relative flex flex-col w-full min-h-screen sm:p-0 lg:flex-row">

        <div class="relative hidden lg:block lg:w-1/2 min-h-screen">
            <img src="{{ asset('logo/sign-in.jpg') }}" alt="Sign in background"
                class="absolute inset-0 w-full h-full object-cover" />
        </div>

        <div class="flex flex-col flex-1 w-full lg:w-1/2">
            <div class="flex flex-col justify-center flex-1 w-full max-w-md mx-auto px-4 sm:px-0">
                <div>
                    <div class="sm:mx-auto sm:w-full sm:max-w-sm flex flex-col items-center">
                        <h1 class="text-center text-3xl font-bold tracking-tight text-zinc-950">
                            Forgot your password?
                        </h1>
                    </div>
                    <div class="mb-5 sm:mb-8 mt-3 text-center sm:mx-auto sm:w-full sm:max-w-sm">
                        <p class="text-sm text-zinc-600">
                            No problem. Just let us know your email address and we will send you an OTP to reset your password.
                        </p>
                    </div>

                    <div>
                        <!-- Session Status -->
                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="space-y-5">
                                <!-- Email -->
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium text-zinc-950">
                                        Email<span class="text-error-500">*</span>
                                    </label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                                        placeholder="example@gmail.com" required autofocus
                                        class="h-11 w-full rounded-lg border border-zinc-600 bg-transparent px-4 py-2.5 text-sm text-zinc-950 shadow-theme-xs placeholder:text-zinc-400 focus:border-primary focus:outline-hidden focus:ring-3 focus:ring-primary/10" />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <!-- Button -->
                                <div>
                                    <button type="submit"
                                        class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-primary shadow-theme-xs hover:bg-primary/90">
                                        Send OTP
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="mt-5 flex justify-center">
                            <p class="text-sm font-normal text-center text-zinc-500">
                                Remember your password?
                                <a href="{{ route('login') }}" class="text-primary hover:text-primary/90">
                                    Log in
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-auth-layout>
