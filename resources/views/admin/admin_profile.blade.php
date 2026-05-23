<x-admin-layout :title="'Profile'">
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
                <x-my-secondary-button type="button" x-on:click="$dispatch('close-modal', 'confirm-photo-deletion')">
                    Cancel
                </x-my-secondary-button>

                <x-my-danger-button class="ms-3">
                    Delete profile
                </x-my-danger-button>
            </div>
        </form>
    </x-modal>
    <main class="flex-1 max-h-full p-5 lg:mt-0 mt-20">
        <h1 class="text-2xl/8 font-semibold text-zinc-950 sm:text-xl/8 ">Profile</h1>
        <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5 ">
        <form method="POST" class="mx-auto" action="{{ route('admin.update-admin', Auth::user()->id) }}"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <section class="grid gap-x-8 gap-y-8 sm:grid-cols-2">
                <div class="space-y-1">
                    <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6 ">Profile Picture
                    </h2>
                    <img src="{{ Auth::user()->profile_picture ? asset('images/' . Auth::user()->profile_picture) : asset('images/default_profile.png') }}"
                        alt="Profile Picture"
                        class="w-20 h-20 rounded-full bg-gray-200 border-[0.5px] border-zinc-950/10 object-cover"
                        id="preview-image" draggable="false" ondragstart="return false;" onselectstart="return false;">
                </div>
                {{-- <div class="flex gap-2 h-10 justify-start items-center">
                    <input type="file" name="profile_photo" id="profile_photo" class="hidden" accept="image/*"
                        onchange="previewImage(this)">
                    <x-my-secondary-button type="button" class="rounded-full"
                        onclick="document.getElementById('profile_photo').click()">
                        Upload new picture
                    </x-my-secondary-button>

                    <x-my-secondary-button type="button" x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'confirm-photo-deletion')"
                        class="rounded-full bg-gray-50 text-gray-900 hover:bg-gray-100">
                        Delete
                    </x-my-secondary-button>
                </div> --}}
                <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
                    <input type="file" name="profile_photo" id="profile_photo" class="hidden" accept="image/*"
                        onchange="previewImage(this)">
                    <x-my-secondary-button type="button" class="rounded-full py-2 px-4 text-sm"
                        onclick="document.getElementById('profile_photo').click()">
                        Upload new picture
                    </x-my-secondary-button>
                    <x-my-secondary-button type="button" x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'confirm-photo-deletion')"
                        class="rounded-full py-2 px-4 text-sm bg-gray-50 text-gray-900 hover:bg-gray-100">
                        Delete
                    </x-my-secondary-button>
                </div>
            </section>

            <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5 ">

            <section class="grid gap-x-8 gap-y-6 sm:grid-cols-2">
                <div class="space-y-1">
                    <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6 ">First Name
                    </h2>
                    <p data-slot="text" class="text-base/6 text-zinc-500 sm:text-sm/6 ">Please enter your first
                        name.</p>
                </div>
                <div><span data-slot="control"
                        class="relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white before:shadow after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent sm:after:focus-within:ring-2 sm:after:focus-within:ring-blue-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none before:has-[[data-invalid]]:shadow-red-500/10">
                        <input type="text" aria-label="First Name"
                            class="relative block w-full appearance-none rounded-lg px-[calc(theme(spacing[3.5])-1px)] py-[calc(theme(spacing[2.5])-1px)] sm:px-[calc(theme(spacing[3])-1px)] sm:py-[calc(theme(spacing[1.5])-1px)] text-base/6 text-zinc-950 placeholder:text-zinc-500 sm:text-sm/6  border border-zinc-950/10 data-[hover]:border-zinc-950/20  bg-transparent focus:outline-none data-[invalid]:border-red-500 data-[invalid]:data-[hover]:border-red-500 data-[disabled]:border-zinc-950/20"
                            id="first_name" data-headlessui-state="" placeholder="Enter your first name"
                            value="{{ old('first_name', Auth::user()->first_name) }}" name="first_name" required autofocus>
                    </span>
                    <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                </div>
            </section>

            <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5 ">

            <section class="grid gap-x-8 gap-y-6 sm:grid-cols-2">
                <div class="space-y-1">
                    <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6 ">Middle Initial
                    </h2>
                    <p data-slot="text" class="text-base/6 text-zinc-500 sm:text-sm/6 ">Please enter your middle initial (Optional).</p>
                </div>
                <div><span data-slot="control"
                        class="relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white before:shadow after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent sm:after:focus-within:ring-2 sm:after:focus-within:ring-blue-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none before:has-[[data-invalid]]:shadow-red-500/10">
                        <input type="text" aria-label="Middle Initial"
                            class="relative block w-full appearance-none rounded-lg px-[calc(theme(spacing[3.5])-1px)] py-[calc(theme(spacing[2.5])-1px)] sm:px-[calc(theme(spacing[3])-1px)] sm:py-[calc(theme(spacing[1.5])-1px)] text-base/6 text-zinc-950 placeholder:text-zinc-500 sm:text-sm/6  border border-zinc-950/10 data-[hover]:border-zinc-950/20  bg-transparent focus:outline-none data-[invalid]:border-red-500 data-[invalid]:data-[hover]:border-red-500 data-[disabled]:border-zinc-950/20"
                            id="middle_initial" data-headlessui-state="" placeholder="Enter your middle initial"
                            value="{{ old('middle_initial', Auth::user()->middle_initial) }}" name="middle_initial">
                    </span>
                    <x-input-error :messages="$errors->get('middle_initial')" class="mt-2" />
                </div>
            </section>

            <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5 ">

            <section class="grid gap-x-8 gap-y-6 sm:grid-cols-2">
                <div class="space-y-1">
                    <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6 ">Last Name
                    </h2>
                    <p data-slot="text" class="text-base/6 text-zinc-500 sm:text-sm/6 ">Please enter your last name.</p>
                </div>
                <div><span data-slot="control"
                        class="relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white before:shadow after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent sm:after:focus-within:ring-2 sm:after:focus-within:ring-blue-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none before:has-[[data-invalid]]:shadow-red-500/10">
                        <input type="text" aria-label="Last Name"
                            class="relative block w-full appearance-none rounded-lg px-[calc(theme(spacing[3.5])-1px)] py-[calc(theme(spacing[2.5])-1px)] sm:px-[calc(theme(spacing[3])-1px)] sm:py-[calc(theme(spacing[1.5])-1px)] text-base/6 text-zinc-950 placeholder:text-zinc-500 sm:text-sm/6  border border-zinc-950/10 data-[hover]:border-zinc-950/20  bg-transparent focus:outline-none data-[invalid]:border-red-500 data-[invalid]:data-[hover]:border-red-500 data-[disabled]:border-zinc-950/20"
                            id="last_name" data-headlessui-state="" placeholder="Enter your last name"
                            value="{{ old('last_name', Auth::user()->last_name) }}" name="last_name" required>
                    </span>
                    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                </div>
            </section>
            <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5 ">
            <section class="grid gap-x-8 gap-y-6 sm:grid-cols-2">
                <div class="space-y-1">
                    <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6 ">Email
                    </h2>
                    <p data-slot="text" class="text-base/6 text-zinc-500 sm:text-sm/6 ">Use a valid email address.</p>
                </div>
                <div><span data-slot="control"
                        class="relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white before:shadow after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent sm:after:focus-within:ring-2 sm:after:focus-within:ring-blue-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none before:has-[[data-invalid]]:shadow-red-500/10">
                        <input type="email" aria-label="Faculty Email"
                            class="relative block w-full appearance-none rounded-lg px-[calc(theme(spacing[3.5])-1px)] py-[calc(theme(spacing[2.5])-1px)] sm:px-[calc(theme(spacing[3])-1px)] sm:py-[calc(theme(spacing[1.5])-1px)] text-base/6 text-zinc-950 placeholder:text-zinc-500 sm:text-sm/6  border border-zinc-950/10 data-[hover]:border-zinc-950/20  bg-transparent focus:outline-none data-[invalid]:border-red-500 data-[invalid]:data-[hover]:border-red-500 data-[disabled]:border-zinc-950/20"
                            id="admin-email-input" data-headlessui-state="" value="{{ Auth::user()->email }}"
                            placeholder="Enter your email" name="email" required></span>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
            </section>
            <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5 ">
            <div class="flex justify-end gap-2">
                <x-my-secondary-button type="button" onclick="window.history.back()">Cancel</x-my-secondary-button>
                <x-my-button type="submit">Save changes</x-my-button>
            </div>
        </form>
    </main>
</x-admin-layout>
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            // Validate file size (2MB)
            if (input.files[0].size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB');
                input.value = '';
                return;
            }

            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(input.files[0].type)) {
                alert('Only JPG, PNG, and GIF files are allowed');
                input.value = '';
                return;
            }

            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-image').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>