<x-guest-layout :title="'Forgot Password'">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
        <h2 class="mb-2 text-start text-2xl font-medium tracking-tight text-zinc-950">
            Forgot your password?
        </h2>
        <p class="text-sm text-zinc-600">
            No problem. Just let us know your email address and we will send you an OTP to reset your password.
        </p>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form class="space-y-6" method="POST" action="{{ route('password.email') }}">
            @csrf

            <div>
                <label class="mb-1.5 block text-sm font-medium text-zinc-700">
                    Email
                </label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                    placeholder="example@gmail.com" required autofocus
                    class="h-11 w-full rounded-lg border border-zinc-300 bg-transparent px-4 py-2.5 text-sm text-zinc-800 shadow-theme-xs placeholder:text-zinc-400 focus:border-primary focus:outline-hidden focus:ring-3 focus:ring-primary/10" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <button type="submit"
                    class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-primary shadow-theme-xs hover:bg-primary/90">
                    Send OTP
                </button>
            </div>

            <div class="mt-5">
                <p class="text-sm font-normal text-center text-zinc-500">
                    Remember your password?
                    <a href="{{ route('login') }}" class="text-primary hover:text-primary/90">Log in</a>
                </p>
            </div>
        </form>
    </div>
</x-guest-layout>
