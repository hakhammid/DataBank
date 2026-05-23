<x-admin-layout :title="'Edit Degree Program'">
    <main class="flex-1 max-h-full p-5 lg:mt-0 mt-20">
        <div class="flex items-center gap-0">
            <a href="{{ route('admin.degree-program') }}" class="flex items-center gap-2">
                <h1 class="text-2xl/8 font-semibold text-zinc-950 sm:text-xl/8 ">Manage Degree Program</h1>
            </a>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-7 w-7">
                <path d="m9 18 6-6-6-6" />
            </svg>
            <h1 class="text-2xl/8 font-semibold text-zinc-950 sm:text-xl/8 ">Edit Degree Program</h1>
        </div>
        <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5 ">
        <form method="POST" class="mx-auto" action="{{ route('admin.degree-program.update', $course) }}">
            @method('PUT')
            @csrf

            {{-- Department Selection --}}
            <section class="grid gap-x-8 gap-y-6 sm:grid-cols-2">
                <div class="space-y-1">
                    <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6 ">Department</h2>
                    <p data-slot="text" class="text-base/6 text-zinc-500 sm:text-sm/6 ">Select the department this degree program belongs to.</p>
                </div>
                <div>
                    <span data-slot="control"
                        class="relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white before:shadow after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent sm:after:focus-within:ring-2 sm:after:focus-within:ring-zinc-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none before:has-[[data-invalid]]:shadow-red-500/10">
                        <span data-slot="control"
                            class="group relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white dark:before:hidden after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent after:has-[[data-focus]]:ring-2 after:has-[[data-focus]]:ring-blue-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none">
                            <select
                                class="relative block w-full appearance-none rounded-lg py-[calc(theme(spacing[2.5])-1px)] sm:py-[calc(theme(spacing[1.5])-1px)] pl-[calc(theme(spacing[3.5])-1px)] pr-[calc(theme(spacing.10)-1px)] sm:pl-[calc(theme(spacing.3)-1px)] sm:pr-[calc(theme(spacing.9)-1px)] [&amp;_optgroup]:font-semibold text-base/6 text-zinc-950 placeholder:text-zinc-500 sm:text-sm/6 border border-zinc-950/10 data-[hover]:border-zinc-950/20 dark:border-white/10 dark:data-[hover]:border-white/20 bg-transparent dark:bg-white/5 dark:*:bg-zinc-800 focus:outline-none data-[invalid]:border-red-500 data-[invalid]:data-[hover]:border-red-500 data-[disabled]:border-zinc-950/20 data-[disabled]:opacity-100"
                                id="department_id" data-headlessui-state="" name="department_id" required>
                                <option value="" disabled selected>Select Department</option>
                                @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ $course->department_id == $department->id ? 'selected' : '' }}>{{ $department->department_name }}</option>
                                @endforeach
                            </select>
                        </span>
                    </span>
                    <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
                </div>
            </section>

            <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5 ">

            {{-- Degree Program Name --}}
            <section class="grid gap-x-8 gap-y-6 sm:grid-cols-2">
                <div class="space-y-1">
                    <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6 ">Degree Program
                    </h2>
                    <p data-slot="text" class="text-base/6 text-zinc-500 sm:text-sm/6 ">Please enter the degree program.</p>
                </div>
                <div>
                    <span data-slot="control"
                        class="relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white before:shadow after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent sm:after:focus-within:ring-2 sm:after:focus-within:ring-blue-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none before:has-[[data-invalid]]:shadow-red-500/10">
                        <input type="text" aria-label="Degree Program Name"
                            class="relative block w-full appearance-none rounded-lg px-[calc(theme(spacing[3.5])-1px)] py-[calc(theme(spacing[2.5])-1px)] sm:px-[calc(theme(spacing[3])-1px)] sm:py-[calc(theme(spacing[1.5])-1px)] text-base/6 text-zinc-950 placeholder:text-zinc-500 sm:text-sm/6  border border-zinc-950/10 data-[hover]:border-zinc-950/20  bg-transparent focus:outline-none data-[invalid]:border-red-500 data-[invalid]:data-[hover]:border-red-500 data-[disabled]:border-zinc-950/20"
                            id="edit-degree-program-name-input" data-headlessui-state="" placeholder="" value="{{ $course->course_name }}" name="degree_program" required autofocus>
                        </span>
                        <x-input-error :messages="$errors->get('degree_program')" class="mt-2" />
                </div>
            </section>
            <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5 ">
            <div class="flex justify-end gap-2">
                <x-my-secondary-button type="button" onclick="window.location.href='{{ route('admin.degree-program') }}'">Cancel</x-my-secondary-button>
                <x-my-button type="submit">Save degree program</x-my-button>
            </div>
        </form>
    </main>
</x-admin-layout>
