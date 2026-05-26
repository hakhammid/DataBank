<x-faculty-layout :title="'Profile'">
    <main class="flex-1 max-h-full mx-0 md:mx-10 p-5 lg:mt-[6rem] mt-20">
        <h1 class="text-2xl/8 font-semibold text-zinc-950 sm:text-xl/8 ">Profile</h1>
        <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5 ">
        <form method="POST" class="mx-auto" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <section class="grid gap-x-8 gap-y-8 sm:grid-cols-2">
                <div class="space-y-1">
                    <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6 ">Profile Picture
                    </h2>
                    <img src="{{ Auth::user()->profile_picture ? asset('images/' . Auth::user()->profile_picture) : asset('images/default_profile.png') }}" alt="Profile Picture" class="w-20 h-20 rounded-full bg-gray-200 border-[0.5px] border-zinc-950/10 object-cover" id="preview-image">
                </div>
                <div class="flex gap-2 h-10 justify-start items-center">
                    <input type="file" name="profile_photo" id="profile_photo" class="hidden" accept="image/*" onchange="previewImage(this)">
                    <x-my-secondary-button type="button" class="rounded-full" onclick="document.getElementById('profile_photo').click()">
                        Upload new picture
                    </x-my-secondary-button>

                    <x-my-secondary-button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-photo-deletion')" class="rounded-full bg-gray-50 text-gray-900 hover:bg-gray-100">
                        Delete
                    </x-my-secondary-button>
                </div>
            </section>
            <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5 ">
            <section class="grid gap-x-8 gap-y-6 sm:grid-cols-2">
                <div class="space-y-1">
                    <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6 ">First Name
                    </h2>
                    <p data-slot="text" class="text-base/6 text-zinc-500 sm:text-sm/6 ">Please enter your first name.</p>
                </div>
                <div><span data-slot="control" class="relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white before:shadow after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent sm:after:focus-within:ring-2 sm:after:focus-within:ring-blue-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none before:has-[[data-invalid]]:shadow-red-500/10">
                        <input type="text" aria-label="First Name" class="relative block w-full appearance-none rounded-lg px-[calc(theme(spacing[3.5])-1px)] py-[calc(theme(spacing[2.5])-1px)] sm:px-[calc(theme(spacing[3])-1px)] sm:py-[calc(theme(spacing[1.5])-1px)] text-base/6 text-zinc-950 placeholder:text-zinc-500 sm:text-sm/6  border border-zinc-950/10 data-[hover]:border-zinc-950/20  bg-transparent focus:outline-none data-[invalid]:border-red-500 data-[invalid]:data-[hover]:border-red-500 data-[disabled]:border-zinc-950/20" id="first_name" data-headlessui-state="" placeholder="Enter your first name" value="{{ old('first_name', Auth::user()->first_name) }}" name="first_name" required autofocus>
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
                <div><span data-slot="control" class="relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white before:shadow after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent sm:after:focus-within:ring-2 sm:after:focus-within:ring-blue-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none before:has-[[data-invalid]]:shadow-red-500/10">
                        <input type="text" aria-label="Middle Initial" class="relative block w-full appearance-none rounded-lg px-[calc(theme(spacing[3.5])-1px)] py-[calc(theme(spacing[2.5])-1px)] sm:px-[calc(theme(spacing[3])-1px)] sm:py-[calc(theme(spacing[1.5])-1px)] text-base/6 text-zinc-950 placeholder:text-zinc-500 sm:text-sm/6  border border-zinc-950/10 data-[hover]:border-zinc-950/20  bg-transparent focus:outline-none data-[invalid]:border-red-500 data-[invalid]:data-[hover]:border-red-500 data-[disabled]:border-zinc-950/20" id="middle_initial" data-headlessui-state="" placeholder="Enter your middle initial" value="{{ old('middle_initial', Auth::user()->middle_initial) }}" name="middle_initial">
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
                <div><span data-slot="control" class="relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white before:shadow after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent sm:after:focus-within:ring-2 sm:after:focus-within:ring-blue-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none before:has-[[data-invalid]]:shadow-red-500/10">
                        <input type="text" aria-label="Last Name" class="relative block w-full appearance-none rounded-lg px-[calc(theme(spacing[3.5])-1px)] py-[calc(theme(spacing[2.5])-1px)] sm:px-[calc(theme(spacing[3])-1px)] sm:py-[calc(theme(spacing[1.5])-1px)] text-base/6 text-zinc-950 placeholder:text-zinc-500 sm:text-sm/6  border border-zinc-950/10 data-[hover]:border-zinc-950/20  bg-transparent focus:outline-none data-[invalid]:border-red-500 data-[invalid]:data-[hover]:border-red-500 data-[disabled]:border-zinc-950/20" id="last_name" data-headlessui-state="" placeholder="Enter your last name" value="{{ old('last_name', Auth::user()->last_name) }}" name="last_name" required>
                    </span>
                    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                </div>
            </section>

            {{-- <div role="presentation" class="my-10 w-full"></div> --}}
            <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5 ">

            <section class="grid gap-x-8 gap-y-6 sm:grid-cols-2">
                <div class="space-y-1">
                    <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6 ">Email
                    </h2>
                    <p data-slot="text" class="text-base/6 text-zinc-500 sm:text-sm/6 ">Use a valid email address.</p>
                </div>
                <div><span data-slot="control" class="relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white before:shadow after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent sm:after:focus-within:ring-2 sm:after:focus-within:ring-blue-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none before:has-[[data-invalid]]:shadow-red-500/10">
                        <input type="email" aria-label="Faculty Email" class="relative block w-full appearance-none rounded-lg px-[calc(theme(spacing[3.5])-1px)] py-[calc(theme(spacing[2.5])-1px)] sm:px-[calc(theme(spacing[3])-1px)] sm:py-[calc(theme(spacing[1.5])-1px)] text-base/6 text-zinc-950 placeholder:text-zinc-500 sm:text-sm/6  border border-zinc-950/10 data-[hover]:border-zinc-950/20  bg-transparent focus:outline-none data-[invalid]:border-red-500 data-[invalid]:data-[hover]:border-red-500 data-[disabled]:border-zinc-950/20" id="admin-email-input" data-headlessui-state="" value="{{ Auth::user()->email }}" placeholder="Enter your email" name="email" required></span>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
            </section>

            <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5 ">

            <section class="grid gap-x-8 gap-y-6 sm:grid-cols-2">
                <div class="space-y-1">
                    <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6 ">Department
                    </h2>
                    <p data-slot="text" class="text-base/6 text-zinc-500 sm:text-sm/6 ">Your assigned department (managed by administrator).</p>
                </div>
                <div>
                    <span data-slot="control" class="relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-zinc-50/50">
                        <input type="text" class="relative block w-full appearance-none rounded-lg px-[calc(theme(spacing[3.5])-1px)] py-[calc(theme(spacing[2.5])-1px)] sm:px-[calc(theme(spacing[3])-1px)] sm:py-[calc(theme(spacing[1.5])-1px)] text-base/6 text-zinc-500 sm:text-sm/6 border border-zinc-950/10 bg-zinc-50/50 cursor-not-allowed" value="{{ Auth::user()->department?->department_name ?? 'No Department Assigned' }}" readonly disabled>
                    </span>
                </div>
            </section>

            <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5 ">

            <div class="flex justify-end gap-2">
                <x-my-button type="submit">Save changes</x-my-button>
            </div>
            <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5 ">
            <section class="grid gap-x-8 gap-y-6 sm:grid-cols-2 mb-10">
                <div class="space-y-1">
                    <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6 ">Delete account
                    </h2>
                    <p data-slot="text" class="text-base/6 text-zinc-500 sm:text-sm/6 ">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>
                </div>
               <div class="flex justify-end items-end">
                    <x-my-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">Delete account</x-my-danger-button>
                </div>
            </section>
        </form>
    </main>
</x-faculty-layout>
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
