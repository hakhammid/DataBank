<x-guest-layout :title="'Verify OTP'">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
        <h2 class="mb-2 text-start text-2xl font-medium tracking-tight text-zinc-900">
            Verify OTP
        </h2>
        <p class="text-sm text-zinc-500 dark:text-zinc-400">
            We have sent a One-Time Password (OTP) to your email address. Please enter it below to continue.
        </p>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form class="space-y-6" method="POST" action="{{ route('password.verify.otp') }}">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">

            <div>
                <input type="text" id="otp" name="otp"
                    placeholder="Enter OTP" required autofocus
                    class="h-11 w-full rounded-lg border border-zinc-300 bg-transparent px-4 py-2.5 text-sm text-zinc-800 shadow-theme-xs placeholder:text-zinc-400 focus:border-primary focus:outline-hidden focus:ring-3 focus:ring-primary/10" />
                    <x-input-error :messages="$errors->get('otp')" class="mt-2" />
            </div>

            <div>
                <button type="submit"
                    class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-primary shadow-theme-xs hover:bg-primary/90">
                    Verify OTP
                </button>
            </div>
        </form>

        <div class="mt-6">
            <form method="POST" action="{{ route('password.resend.otp') }}" class="text-center">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <button type="submit" class="text-sm font-normal text-center text-zinc-900">
                    Didn't receive the OTP? Click here to resend
                </button>
            </form>
        </div>

        <div class="mt-5">
            <p class="text-sm font-normal text-center text-zinc-500">
                Remember your password?
                <a href="{{ route('login') }}" class="text-primary hover:text-primary/90">Log in</a>
            </p>
        </div>
    </div>
</x-guest-layout>
