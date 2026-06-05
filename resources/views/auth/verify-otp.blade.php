<x-auth-layout :title="'Verify OTP - ' . config('constants.APP_TITLE')">
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
                            Verify OTP
                        </h1>
                    </div>
                    <div class="mb-5 sm:mb-8 mt-3 text-center sm:mx-auto sm:w-full sm:max-w-sm">
                        <p class="text-sm text-zinc-600">
                            We have sent a One-Time Password (OTP) to your email address. Please enter it below to continue.
                        </p>
                    </div>

                    <div>
                        <!-- Session Status -->
                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <form method="POST" action="{{ route('password.verify.otp') }}">
                            @csrf
                            <input type="hidden" name="email" value="{{ $email }}">

                            <div class="space-y-6" x-data="{
                                otp: ['', '', '', '', '', ''],
                                onInput(event, index) {
                                    let value = event.target.value.replace(/[^0-9]/g, '');
                                    
                                    if (value.length > 1) {
                                        let digits = value.split('').slice(0, 6);
                                        for(let i=0; i<digits.length; i++) {
                                            if(index + i < 6) this.otp[index + i] = digits[i];
                                        }
                                        let nextIndex = Math.min(index + digits.length, 5);
                                        document.getElementById('otp_' + nextIndex).focus();
                                    } else {
                                        this.otp[index] = value;
                                        if (value !== '' && index < 5) {
                                            document.getElementById('otp_' + (index + 1)).focus();
                                        }
                                    }
                                },
                                onKeydown(event, index) {
                                    if (event.key === 'Backspace' && !this.otp[index] && index > 0) {
                                        document.getElementById('otp_' + (index - 1)).focus();
                                    }
                                }
                            }">
                                <!-- Hidden input for form submission -->
                                <input type="hidden" name="otp" :value="otp.join('')">

                                <!-- OTP -->
                                <div>
                                    <label class="mb-3 block text-sm font-medium text-zinc-950 text-center">
                                        Enter 6-digit OTP<span class="text-error-500">*</span>
                                    </label>
                                    <div class="flex justify-center gap-2 sm:gap-3">
                                        <template x-for="index in 6" :key="index">
                                            <input type="text" inputmode="numeric" pattern="\d*" maxlength="6"
                                                :id="'otp_' + (index - 1)"
                                                x-model="otp[index - 1]"
                                                @input="onInput($event, index - 1)"
                                                @keydown="onKeydown($event, index - 1)"
                                                class="h-12 w-10 sm:h-14 sm:w-12 text-center rounded-xl border border-zinc-600 bg-transparent text-xl font-bold text-zinc-950 shadow-theme-xs focus:border-primary focus:outline-hidden focus:ring-3 focus:ring-primary/10" />
                                        </template>
                                    </div>
                                    <x-input-error :messages="$errors->get('otp')" class="mt-2 text-center" />
                                </div>

                                <!-- Button -->
                                <div>
                                    <button type="submit"
                                        class="flex items-center justify-center w-full px-4 py-3 text-sm font-medium text-white transition rounded-lg bg-primary shadow-theme-xs hover:bg-primary/90 mt-2">
                                        Verify OTP
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="mt-6 flex justify-center">
                            <form method="POST" action="{{ route('password.resend.otp') }}" class="text-center">
                                @csrf
                                <input type="hidden" name="email" value="{{ $email }}">
                                <button type="submit" class="text-sm font-normal text-center text-zinc-900 hover:text-primary transition-colors">
                                    Didn't receive the OTP? Click here to resend
                                </button>
                            </form>
                        </div>

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
