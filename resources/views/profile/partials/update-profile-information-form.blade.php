<section>
    <header>
        <h2 class="text-xl font-medium text-gray-900">
            Profile Information
        </h2>

    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')
        <div class="flex justify-start items-center gap-6">
            <img src="{{ Auth::user()->profile_picture ? asset('images/' . Auth::user()->profile_picture) : asset('images/default_profile.png') }}"
                alt="Profile Picture"
                class="w-20 h-20 rounded-full bg-gray-200 border-[0.5px] border-zinc-950/10 object-cover"
                id="preview-image" draggable="false" ondragstart="return false;" onselectstart="return false;">
            <div class="flex justify-start items-center gap-2">
                <input type="file" name="profile_photo" id="file" class="hidden" accept="image/*"
                    onchange="previewImage(this)">
                <x-my-secondary-button type="button" class="rounded-full"
                    onclick="document.getElementById('file').click()">
                    Change profile
                </x-my-secondary-button>

                <x-my-secondary-button type="button" x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'confirm-photo-deletion')">
                    Remove profile
                </x-my-secondary-button>
            </div>
        </div>
        <div>
            <div class="mb-2">
                <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6 ">First name</h2>
            </div>

            <span data-slot="control"
                class="relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white before:shadow after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent sm:after:focus-within:ring-2 sm:after:focus-within:ring-blue-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none before:has-[[data-invalid]]:shadow-red-500/10">
                <input type="text" aria-label="First Name"
                    class="relative block w-full appearance-none rounded-lg px-[calc(theme(spacing[3.5])-1px)] py-[calc(theme(spacing[2.5])-1px)] sm:px-[calc(theme(spacing[3])-1px)] sm:py-[calc(theme(spacing[1.5])-1px)] text-base/6 text-zinc-950 placeholder:text-zinc-500 sm:text-sm/6  border border-zinc-950/10 data-[hover]:border-zinc-950/20  bg-transparent focus:outline-none data-[invalid]:border-red-500 data-[invalid]:data-[hover]:border-red-500 data-[disabled]:border-zinc-950/20"
                    id="first_name" data-headlessui-state="" placeholder="Enter your first name"
                    value="{{ old('first_name', $user->first_name) }}" name="first_name" required autofocus>
            </span>

            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>

        <div>
            <div class="mb-2">
                <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6 ">Middle Initial</h2>
            </div>

            <span data-slot="control"
                class="relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white before:shadow after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent sm:after:focus-within:ring-2 sm:after:focus-within:ring-blue-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none before:has-[[data-invalid]]:shadow-red-500/10">
                <input type="text" aria-label="Middle Initial"
                    class="relative block w-full appearance-none rounded-lg px-[calc(theme(spacing[3.5])-1px)] py-[calc(theme(spacing[2.5])-1px)] sm:px-[calc(theme(spacing[3])-1px)] sm:py-[calc(theme(spacing[1.5])-1px)] text-base/6 text-zinc-950 placeholder:text-zinc-500 sm:text-sm/6  border border-zinc-950/10 data-[hover]:border-zinc-950/20  bg-transparent focus:outline-none data-[invalid]:border-red-500 data-[invalid]:data-[hover]:border-red-500 data-[disabled]:border-zinc-950/20"
                    id="middle_initial" data-headlessui-state="" placeholder="Enter your middle initial"
                    value="{{ old('middle_initial', $user->middle_initial) }}" name="middle_initial" autofocus>
            </span>

            <x-input-error :messages="$errors->get('middle_initial')" class="mt-2" />
        </div>

        <div>
            <div class="mb-2">
                <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6 ">Last name</h2>
            </div>

            <span data-slot="control"
                class="relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white before:shadow after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent sm:after:focus-within:ring-2 sm:after:focus-within:ring-blue-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none before:has-[[data-invalid]]:shadow-red-500/10">
                <input type="text" aria-label="Last Name"
                    class="relative block w-full appearance-none rounded-lg px-[calc(theme(spacing[3.5])-1px)] py-[calc(theme(spacing[2.5])-1px)] sm:px-[calc(theme(spacing[3])-1px)] sm:py-[calc(theme(spacing[1.5])-1px)] text-base/6 text-zinc-950 placeholder:text-zinc-500 sm:text-sm/6  border border-zinc-950/10 data-[hover]:border-zinc-950/20  bg-transparent focus:outline-none data-[invalid]:border-red-500 data-[invalid]:data-[hover]:border-red-500 data-[disabled]:border-zinc-950/20"
                    id="last_name" data-headlessui-state="" placeholder="Enter your last name"
                    value="{{ old('last_name', $user->last_name) }}" name="last_name" required autofocus>
            </span>

            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
        </div>

        <div>
            {{-- <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" /> --}}
            <div class="mb-2">
                <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6 ">Email
            </div>

            <span data-slot="control"
                class="relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white before:shadow after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent sm:after:focus-within:ring-2 sm:after:focus-within:ring-blue-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none before:has-[[data-invalid]]:shadow-red-500/10">
                <input type="email" aria-label="Email Address"
                    class="relative block w-full appearance-none rounded-lg px-[calc(theme(spacing[3.5])-1px)] py-[calc(theme(spacing[2.5])-1px)] sm:px-[calc(theme(spacing[3])-1px)] sm:py-[calc(theme(spacing[1.5])-1px)] text-base/6 text-zinc-950 placeholder:text-zinc-500 sm:text-sm/6  border border-zinc-950/10 data-[hover]:border-zinc-950/20  bg-transparent focus:outline-none data-[invalid]:border-red-500 data-[invalid]:data-[hover]:border-red-500 data-[disabled]:border-zinc-950/20"
                    id="email" data-headlessui-state="" placeholder="Enter your email address"
                    value="{{ old('email', $user->email) }}" name="email" required>
            </span>

            <x-input-error :messages="$errors->get('email')" class="mt-2" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div>
                <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                    Your email address is unverified.

                    <button form="send-verification"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Click here to re-send the verification email.
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                <p class="mt-2 font-medium text-sm text-green-600">
                    A new verification link has been sent to your email address.
                </p>
                @endif
            </div>
            @endif
        </div>

        <div class="flex items-center justify-end gap-4">
            <x-my-button type="submit">Save changes</x-my-button>
            @if (session('status') === 'profile-updated')
            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-600">Saved.</p>
            @endif
        </div>
    </form>

    <x-modal name="confirm-photo-deletion" focusable>
        <form method="post" action="{{ route('profile.delete-photo') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                Are you sure you want to delete your profile picture?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Once your profile picture is deleted, it will be replaced with the default avatar.
            </p>

            <div class="mt-6 flex justify-end">
                <x-secondary-button type="button" class="rounded-full bg-gray-50 text-gray-900 hover:bg-gray-100"
                    x-on:click="$dispatch('close-modal', 'confirm-photo-deletion')">
                    Cancel
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    Delete Picture
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>

<script>
    function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            document.getElementById('preview-image').src = e.target.result;
        }

        reader.readAsDataURL(input.files[0]);
    }
}
</script>
