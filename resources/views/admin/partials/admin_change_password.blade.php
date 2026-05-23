<x-admin-layout :title="'Change Password'">
        <main class="flex-1 max-h-full p-5 lg:mt-0 mt-20">
            <h1 class="text-2xl/8 font-semibold text-zinc-950 sm:text-xl/8 ">Change Password</h1>
            <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5 ">
            <form method="POST" class="mx-auto" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')
                <section class="grid gap-x-8 gap-y-6 sm:grid-cols-2">
                    <div class="space-y-1">
                        <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6 ">Current Password</h2>
                        <p data-slot="text" class="text-base/6 text-zinc-500 sm:text-sm/6 ">Enter your current password to verify.</p>
                    </div>
                    <div><span data-slot="control"
                            class="relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white before:shadow after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent sm:after:focus-within:ring-2 sm:after:focus-within:ring-blue-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none before:has-[[data-invalid]]:shadow-red-500/10">
                            <input type="password" aria-label="Current Password"
                                class="relative block w-full appearance-none rounded-lg px-[calc(theme(spacing[3.5])-1px)] py-[calc(theme(spacing[2.5])-1px)] sm:px-[calc(theme(spacing[3])-1px)] sm:py-[calc(theme(spacing[1.5])-1px)] text-base/6 text-zinc-950 placeholder:text-zinc-500 sm:text-sm/6  border border-zinc-950/10 data-[hover]:border-zinc-950/20  bg-transparent focus:outline-none data-[invalid]:border-red-500 data-[invalid]:data-[hover]:border-red-500 data-[disabled]:border-zinc-950/20 @error('current_password', 'updatePassword') border-red-500 @enderror"
                                id="current_password" 
                                data-headlessui-state="current_password" 
                                placeholder="Enter your current password" 
                                name="current_password" 
                                required 
                                autofocus
                                autocomplete="current_password"
                                value="{{ old('current_password') }}"
                                aria-describedby="current-password-error">
                            </span>
                            @error('current_password', 'updatePassword')
                                <p class="mt-2 text-sm text-red-600" id="current-password-error">{{ $message }}</p>
                            @enderror
                    </div>
                </section>
                <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5 ">
                <section class="grid gap-x-8 gap-y-6 sm:grid-cols-2">
                    <div class="space-y-1">
                        <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6 ">New Password</h2>
                        <p data-slot="text" class="text-base/6 text-zinc-500 sm:text-sm/6 ">Password must be at least 8 characters with uppercase, lowercase, number, and symbol.</p>
                    </div>
                    <div><span data-slot="control"
                            class="relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white before:shadow after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent sm:after:focus-within:ring-2 sm:after:focus-within:ring-blue-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none before:has-[[data-invalid]]:shadow-red-500/10">
                            <input type="password" aria-label="New Password"
                                class="relative block w-full appearance-none rounded-lg px-[calc(theme(spacing[3.5])-1px)] py-[calc(theme(spacing[2.5])-1px)] sm:px-[calc(theme(spacing[3])-1px)] sm:py-[calc(theme(spacing[1.5])-1px)] text-base/6 text-zinc-950 placeholder:text-zinc-500 sm:text-sm/6  border border-zinc-950/10 data-[hover]:border-zinc-950/20  bg-transparent focus:outline-none data-[invalid]:border-red-500 data-[invalid]:data-[hover]:border-red-500 data-[disabled]:border-zinc-950/20 @error('password', 'updatePassword') border-red-500 @enderror"
                                id="password" 
                                data-headlessui-state="password" 
                                placeholder="Enter your new password"
                                name="password" 
                                required
                                minlength="8"
                                autocomplete="password"
                                pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                                title="Password must contain at least 8 characters, including uppercase and lowercase letters, numbers, and special characters"
                                aria-describedby="password-error"></span>
                                @error('password', 'updatePassword')
                                    <p class="mt-2 text-sm text-red-600" id="password-error">{{ $message }}</p>
                                @enderror
                    </div>
                </section>
                <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5 ">
                <section class="grid gap-x-8 gap-y-6 sm:grid-cols-2">
                    <div class="space-y-1">
                        <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6 ">Confirm New Password</h2>
                        <p data-slot="text" class="text-base/6 text-zinc-500 sm:text-sm/6 ">Re-enter your new password to confirm.</p>
                    </div>
                    <div><span data-slot="control"
                            class="relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white before:shadow after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent sm:after:focus-within:ring-2 sm:after:focus-within:ring-blue-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none before:has-[[data-invalid]]:shadow-red-500/10">
                            <input type="password" aria-label="Confirm New Password"
                                class="relative block w-full appearance-none rounded-lg px-[calc(theme(spacing[3.5])-1px)] py-[calc(theme(spacing[2.5])-1px)] sm:px-[calc(theme(spacing[3])-1px)] sm:py-[calc(theme(spacing[1.5])-1px)] text-base/6 text-zinc-950 placeholder:text-zinc-500 sm:text-sm/6  border border-zinc-950/10 data-[hover]:border-zinc-950/20  bg-transparent focus:outline-none data-[invalid]:border-red-500 data-[invalid]:data-[hover]:border-red-500 data-[disabled]:border-zinc-950/20 @error('password_confirmation', 'updatePassword') border-red-500 @enderror"
                                id="password_confirmation" 
                                data-headlessui-state="" 
                                placeholder="Re-enter your new password"
                                name="password_confirmation" 
                                required
                                autocomplete="password_confirmation"
                                minlength="8"
                                aria-describedby="password-confirmation-error"></span>
                                @error('password_confirmation', 'updatePassword')
                                    <p class="mt-2 text-sm text-red-600" id="password-confirmation-error">{{ $message }}</p>
                                @enderror
                    </div>
                </section>
                <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5 ">
                {{-- <div class="flex justify-end gap-2">
                    <x-my-button type="submit">Update Password</x-my-button>
                </div> --}}
                <div class="flex items-center justify-end gap-4">
                    <x-my-secondary-button type="button" onclick="window.history.back()">Cancel</x-my-secondary-button>
                    <x-my-button type="submit">
                        Change password
                    </x-my-button>
        
                    @if (session('status') === 'password-updated')
                        <p
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 2000)"
                            class="text-sm text-gray-600 dark:text-gray-400"
                        >Saved.</p>
                    @endif
                </div>
            </form>
        </main>
    </x-admin-layout>
            