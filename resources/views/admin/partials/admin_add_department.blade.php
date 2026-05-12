<x-admin-layout :title="'Add Department'">
    <main class="flex-1 max-h-full p-5 lg:mt-0 mt-20">
        <div class="flex items-center gap-0">
            <a href="{{ route('admin.departments') }}" class="flex items-center gap-2">
                <h1 class="text-2xl/8 font-semibold text-zinc-950 sm:text-xl/8 ">Manage Department</h1>
            </a>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-7 w-7">
                <path d="m9 18 6-6-6-6" />
            </svg>
            <h1 class="text-2xl/8 font-semibold text-zinc-950 sm:text-xl/8 ">Create Department</h1>
        </div>
        <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5 ">
        <form method="POST" class="mx-auto" action="{{ route('admin.create.department') }}"
            enctype="multipart/form-data">
            @csrf
            <section class="grid gap-x-8 gap-y-6 sm:grid-cols-2">
                <div class="space-y-1">
                    <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6 ">Department Logo (Optional)
                    </h2>
                    <img src="{{ asset('images/default_logo.png') }}" alt="Department Logo"
                        class="w-20 h-20 md:h-[10rem] md:w-[10rem] rounded-full bg-gray-200 border-[0.5px] border-zinc-950/10 object-cover"
                        id="preview-image" draggable="false"
                        ondragstart="return false;" onselectstart="return false;">

                    <input type="file" name="department_logo" id="file" class="hidden" accept="image/*"
                        onchange="previewImage(this)">
                </div>
                <div><span data-slot="control"
                        class="relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white before:shadow after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent sm:after:focus-within:ring-2 sm:after:focus-within:ring-blue-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none before:has-[[data-invalid]]:shadow-red-500/10">
                    </span>
                    <x-secondary-button type="button" class="rounded-full"
                        onclick="document.getElementById('file').click()">
                        Upload
                    </x-secondary-button>
                    <x-input-error :messages="$errors->get('college_name')" class="mt-2" />
                </div>
            </section>

            <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5 ">

            <section class="grid gap-x-8 gap-y-6 sm:grid-cols-2">
                <div class="space-y-1">
                    <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6 ">Department Name
                    </h2>
                    <p data-slot="text" class="text-base/6 text-zinc-500 sm:text-sm/6 ">Please enter the department
                        name.</p>
                </div>
                <div><span data-slot="control"
                        class="relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white before:shadow after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent sm:after:focus-within:ring-2 sm:after:focus-within:ring-blue-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none before:has-[[data-invalid]]:shadow-red-500/10">
                        <input type="text" aria-label="Department Name"
                            class="relative block w-full appearance-none rounded-lg px-[calc(theme(spacing[3.5])-1px)] py-[calc(theme(spacing[2.5])-1px)] sm:px-[calc(theme(spacing[3])-1px)] sm:py-[calc(theme(spacing[1.5])-1px)] text-base/6 text-zinc-950 placeholder:text-zinc-500 sm:text-sm/6  border border-zinc-950/10 data-[hover]:border-zinc-950/20  bg-transparent focus:outline-none data-[invalid]:border-red-500 data-[invalid]:data-[hover]:border-red-500 data-[disabled]:border-zinc-950/20"
                            id="add-department-name-input" data-headlessui-state="" placeholder=""
                            value="{{ old('department_name') }}" name="department_name" required autofocus>
                    </span>
                    <x-input-error :messages="$errors->get('department_name')" class="mt-2" />
                </div>
            </section>
            <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5 ">
            <div class="flex justify-end gap-2">
                <x-my-secondary-button type="button" onclick="window.location.href='{{ route('admin.departments') }}'">
                    Cancel</x-my-secondary-button>
                <x-my-button type="submit">Save Department</x-my-button>
            </div>
        </form>
    </main>
</x-admin-layout>