<x-admin-layout :title="'Create Faculty'">
    <main class="flex-1 max-h-full p-5 lg:mt-0 mt-20">
        <div class="flex items-center gap-0">
            <a href="{{ route('admin.faculties') }}" class="flex items-center gap-2">
                <h1 class="text-2xl/8 font-semibold text-zinc-950 sm:text-xl/8 ">Manage Faculty</h1>
            </a>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-7 w-7">
                <path d="m9 18 6-6-6-6" />
            </svg>
            <h1 class="text-2xl/8 font-semibold text-zinc-950 sm:text-xl/8 ">Create Faculty</h1>
        </div>
        <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5 ">
        <form method="POST" class="mx-auto" action="{{ route('admin.add-faculty') }}" enctype="multipart/form-data">
            @csrf
            <section class="grid gap-x-8 gap-y-8 sm:grid-cols-2">
                <div class="space-y-1">
                    <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6 ">Profile Picture (Optional)
                    </h2>
                    <img src="{{ asset('images/default_profile.png') }}" alt="Profile Picture" class="w-20 h-20 rounded-full bg-gray-200 border-[0.5px] border-zinc-950/10 object-cover" id="preview-image" draggable="false" ondragstart="return false;" onselectstart="return false;">
                </div>
                <div class="flex gap-2 h-10 justify-start items-center">
                    <input type="file" name="profile_photo" id="profile_photo" class="hidden" accept="image/*" onchange="previewImage(this)">
                    <x-my-secondary-button type="button" onclick="document.getElementById('profile_photo').click()"
                        class="rounded-full bg-gray-50 text-gray-900 hover:bg-gray-100">
                        Upload a photo
                    </x-my-secondary-button>
                </div>
            </section>

            <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5 ">

            <section class="grid gap-x-8 gap-y-6 sm:grid-cols-2">
                <div class="space-y-1">
                    <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6 ">Faculty ID Number
                    </h2>
                    <p data-slot="text" class="text-base/6 text-zinc-500 sm:text-sm/6 ">Please enter the faculty ID number.</p>
                </div>
                <div><span data-slot="control" class="relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white before:shadow after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent sm:after:focus-within:ring-2 sm:after:focus-within:ring-blue-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none before:has-[[data-invalid]]:shadow-red-500/10">
                        <input type="text" aria-label="Faculty ID Number" class="relative block w-full appearance-none rounded-lg px-[calc(theme(spacing[3.5])-1px)] py-[calc(theme(spacing[2.5])-1px)] sm:px-[calc(theme(spacing[3])-1px)] sm:py-[calc(theme(spacing[1.5])-1px)] text-base/6 text-zinc-950 placeholder:text-zinc-500 sm:text-sm/6  border border-zinc-950/10 data-[hover]:border-zinc-950/20  bg-transparent focus:outline-none data-[invalid]:border-red-500 data-[invalid]:data-[hover]:border-red-500 data-[disabled]:border-zinc-950/20" id="add-faculty-id-number-input" data-headlessui-state="" placeholder="Enter the faculty ID number" value="{{ old('id_number') }}" name="id_number" required autofocus>
                    </span>
                    <x-input-error :messages="$errors->get('id_number')" class="mt-2" />
                </div>
            </section>

            <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5 ">

            <section class="grid gap-x-8 gap-y-6 sm:grid-cols-2">
                <div class="space-y-1">
                    <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6 ">First Name
                    </h2>
                    <p data-slot="text" class="text-base/6 text-zinc-500 sm:text-sm/6 ">Please enter the first name of the faculty member.</p>
                </div>
                <div><span data-slot="control" class="relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white before:shadow after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent sm:after:focus-within:ring-2 sm:after:focus-within:ring-blue-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none before:has-[[data-invalid]]:shadow-red-500/10">
                        <input type="text" aria-label="Faculty First Name" class="relative block w-full appearance-none rounded-lg px-[calc(theme(spacing[3.5])-1px)] py-[calc(theme(spacing[2.5])-1px)] sm:px-[calc(theme(spacing[3])-1px)] sm:py-[calc(theme(spacing[1.5])-1px)] text-base/6 text-zinc-950 placeholder:text-zinc-500 sm:text-sm/6  border border-zinc-950/10 data-[hover]:border-zinc-950/20  bg-transparent focus:outline-none data-[invalid]:border-red-500 data-[invalid]:data-[hover]:border-red-500 data-[disabled]:border-zinc-950/20" id="add-faculty-first-name-input" data-headlessui-state="" placeholder="Enter the faculty first name" value="{{ old('first_name') }}" name="first_name" required autofocus>
                    </span>
                    <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                </div>
            </section>

            <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5 ">

            <section class="grid gap-x-8 gap-y-6 sm:grid-cols-2">
                <div class="space-y-1">
                    <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6 ">Middle Initial
                    </h2>
                    <p data-slot="text" class="text-base/6 text-zinc-500 sm:text-sm/6 ">Please enter the middle initial of the faculty member (Optional).</p>
                </div>
                <div><span data-slot="control" class="relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white before:shadow after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent sm:after:focus-within:ring-2 sm:after:focus-within:ring-blue-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none before:has-[[data-invalid]]:shadow-red-500/10">
                        <input type="text" aria-label="Faculty Middle Initial" class="relative block w-full appearance-none rounded-lg px-[calc(theme(spacing[3.5])-1px)] py-[calc(theme(spacing[2.5])-1px)] sm:px-[calc(theme(spacing[3])-1px)] sm:py-[calc(theme(spacing[1.5])-1px)] text-base/6 text-zinc-950 placeholder:text-zinc-500 sm:text-sm/6  border border-zinc-950/10 data-[hover]:border-zinc-950/20  bg-transparent focus:outline-none data-[invalid]:border-red-500 data-[invalid]:data-[hover]:border-red-500 data-[disabled]:border-zinc-950/20" id="add-faculty-middle-initial-input" data-headlessui-state="" placeholder="Enter the faculty middle initial" value="{{ old('middle_initial') }}" name="middle_initial" autofocus>
                    </span>
                    <x-input-error :messages="$errors->get('middle_initial')" class="mt-2" />
                </div>
            </section>

            <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5 ">

            <section class="grid gap-x-8 gap-y-6 sm:grid-cols-2">
                <div class="space-y-1">
                    <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6 ">Last Name
                    </h2>
                    <p data-slot="text" class="text-base/6 text-zinc-500 sm:text-sm/6 ">Please enter the last name of the faculty member.</p>
                </div>
                <div><span data-slot="control" class="relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white before:shadow after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent sm:after:focus-within:ring-2 sm:after:focus-within:ring-blue-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none before:has-[[data-invalid]]:shadow-red-500/10">
                        <input type="text" aria-label="Faculty Last Name" class="relative block w-full appearance-none rounded-lg px-[calc(theme(spacing[3.5])-1px)] py-[calc(theme(spacing[2.5])-1px)] sm:px-[calc(theme(spacing[3])-1px)] sm:py-[calc(theme(spacing[1.5])-1px)] text-base/6 text-zinc-950 placeholder:text-zinc-500 sm:text-sm/6  border border-zinc-950/10 data-[hover]:border-zinc-950/20  bg-transparent focus:outline-none data-[invalid]:border-red-500 data-[invalid]:data-[hover]:border-red-500 data-[disabled]:border-zinc-950/20" id="add-faculty-last-name-input" data-headlessui-state="" placeholder="Enter the faculty last name" value="{{ old('last_name') }}" name="last_name" required autofocus>
                    </span>
                    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                </div>
            </section>

            <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5 ">

            <section class="grid gap-x-8 gap-y-6 sm:grid-cols-2">
                <div class="space-y-1">
                    <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6 ">Email
                    </h2>
                    <p data-slot="text" class="text-base/6 text-zinc-500 sm:text-sm/6 ">Use a valid faculty email address.</p>
                </div>
                <div><span data-slot="control" class="relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white before:shadow after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent sm:after:focus-within:ring-2 sm:after:focus-within:ring-blue-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none before:has-[[data-invalid]]:shadow-red-500/10">
                        <input type="email" aria-label="Faculty Email" class="relative block w-full appearance-none rounded-lg px-[calc(theme(spacing[3.5])-1px)] py-[calc(theme(spacing[2.5])-1px)] sm:px-[calc(theme(spacing[3])-1px)] sm:py-[calc(theme(spacing[1.5])-1px)] text-base/6 text-zinc-950 placeholder:text-zinc-500 sm:text-sm/6  border border-zinc-950/10 data-[hover]:border-zinc-950/20  bg-transparent focus:outline-none data-[invalid]:border-red-500 data-[invalid]:data-[hover]:border-red-500 data-[disabled]:border-zinc-950/20" id="add-faculty-email-input" data-headlessui-state="" value="{{ old('email') }}" placeholder="Enter institutional email" name="email" required></span>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
            </section>

            <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5 ">

            <section class="grid gap-x-8 gap-y-6 sm:grid-cols-2">
                <div class="space-y-1">
                    <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6 ">Department
                    </h2>
                    <p data-slot="text" class="text-base/6 text-zinc-500 sm:text-sm/6 ">Select the faculty's department.</p>
                </div>
                <div><span data-slot="control" class="relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white before:shadow after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent sm:after:focus-within:ring-2 sm:after:focus-within:ring-zinc-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none before:has-[[data-invalid]]:shadow-red-500/10">
                        <span data-slot="control" class="group relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white dark:before:hidden after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent after:has-[[data-focus]]:ring-2 after:has-[[data-focus]]:ring-blue-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none">
                            <select class="relative block w-full appearance-none rounded-lg py-[calc(theme(spacing[2.5])-1px)] sm:py-[calc(theme(spacing[1.5])-1px)] pl-[calc(theme(spacing[3.5])-1px)] pr-[calc(theme(spacing.10)-1px)] sm:pl-[calc(theme(spacing.3)-1px)] sm:pr-[calc(theme(spacing.9)-1px)] [&amp;_optgroup]:font-semibold text-base/6 text-zinc-950 placeholder:text-zinc-500 sm:text-sm/6 border border-zinc-950/10 data-[hover]:border-zinc-950/20 dark:border-white/10 dark:data-[hover]:border-white/20 bg-transparent dark:bg-white/5 dark:*:bg-zinc-800 focus:outline-none data-[invalid]:border-red-500 data-[invalid]:data-[hover]:border-red-500 data-[disabled]:border-zinc-950/20 data-[disabled]:opacity-100" id="department_id" data-headlessui-state="" name="department_id" required>
                                <option value="" disabled selected>Select Department</option>
                                @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->department_name }}</option>
                                @endforeach
                            </select>
                        </span>
                    </span>
                    <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
                </div>
            </section>

            <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5 ">

            <section class="grid gap-x-8 gap-y-6 sm:grid-cols-2">
                <div class="space-y-1">
                    <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6 ">Degree Program
                    </h2>
                    <p data-slot="text" class="text-base/6 text-zinc-500 sm:text-sm/6 ">Select the faculty's degree program (Optional).</p>
                </div>
                <div><span data-slot="control"
                        class="relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white before:shadow after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent sm:after:focus-within:ring-2 sm:after:focus-within:ring-zinc-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none before:has-[[data-invalid]]:shadow-red-500/10">
                        <span data-slot="control"
                            class="group relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white dark:before:hidden after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent after:has-[[data-focus]]:ring-2 after:has-[[data-focus]]:ring-blue-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none">
                            <select
                                class="relative block w-full appearance-none rounded-lg py-[calc(theme(spacing[2.5])-1px)] sm:py-[calc(theme(spacing[1.5])-1px)] pl-[calc(theme(spacing[3.5])-1px)] pr-[calc(theme(spacing.10)-1px)] sm:pl-[calc(theme(spacing.3)-1px)] sm:pr-[calc(theme(spacing.9)-1px)] [&amp;_optgroup]:font-semibold text-base/6 text-zinc-950 placeholder:text-zinc-500 sm:text-sm/6 border border-zinc-950/10 data-[hover]:border-zinc-950/20 dark:border-white/10 dark:data-[hover]:border-white/20 bg-transparent dark:bg-white/5 dark:*:bg-zinc-800 focus:outline-none data-[invalid]:border-red-500 data-[invalid]:data-[hover]:border-red-500 data-[disabled]:border-zinc-950/20 data-[disabled]:opacity-100 disabled:opacity-50 disabled:cursor-not-allowed"
                                id="course_id" data-headlessui-state="" name="course_id" disabled>
                                <option value="" disabled selected>Select a department first</option>
                            </select>
                        </span>
                    </span>
                    <x-input-error :messages="$errors->get('course_id')" class="mt-2" />
                </div>
            </section>

            <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5 ">

            <section class="grid gap-x-8 gap-y-6 sm:grid-cols-2">
                <div class="space-y-1">
                    <h2 class="text-base/7 font-semibold text-zinc-950 sm:text-sm/6 ">Password
                    </h2>
                    <p data-slot="text" class="text-base/6 text-zinc-500 sm:text-sm/6 ">Password should be at least 8 characters.</p>
                </div>
                <div><span data-slot="control" class="relative block w-full before:absolute before:inset-px before:rounded-[calc(theme(borderRadius.lg)-1px)] before:bg-white before:shadow after:pointer-events-none after:absolute after:inset-0 after:rounded-lg after:ring-inset after:ring-transparent sm:after:focus-within:ring-2 sm:after:focus-within:ring-blue-500 has-[[data-disabled]]:opacity-50 before:has-[[data-disabled]]:bg-zinc-950/5 before:has-[[data-disabled]]:shadow-none before:has-[[data-invalid]]:shadow-red-500/10">
                        <input type="text" aria-label="Faculty Password" class="relative block w-full appearance-none rounded-lg px-[calc(theme(spacing[3.5])-1px)] py-[calc(theme(spacing[2.5])-1px)] sm:px-[calc(theme(spacing[3])-1px)] sm:py-[calc(theme(spacing[1.5])-1px)] text-base/6 text-zinc-950 placeholder:text-zinc-500 sm:text-sm/6  border border-zinc-950/10 data-[hover]:border-zinc-950/20  bg-transparent focus:outline-none data-[invalid]:border-red-500 data-[invalid]:data-[hover]:border-red-500 data-[disabled]:border-zinc-950/20" id="add-faculty-password-input" data-headlessui-state="" value="{{ old('password') }}" placeholder="Enter the faculty password" name="password" required></span>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
            </section>

            <hr role="presentation" class="my-10 w-full border-t border-zinc-950/5 ">

            <div class="flex justify-end gap-2">
                <x-my-secondary-button type="button" onclick="window.location.href='{{ route('admin.faculties') }}'">Cancel</x-my-secondary-button>
                <x-my-button type="submit">Save faculty</x-my-button>
            </div>
        </form>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const departmentSelect = document.getElementById('department_id');
            const courseSelect = document.getElementById('course_id');
            const oldCourseId = '{{ old("course_id") }}';

            departmentSelect.addEventListener('change', function () {
                const departmentId = this.value;

                if (!departmentId) {
                    courseSelect.innerHTML = '<option value="" disabled selected>Select a department first</option>';
                    courseSelect.disabled = true;
                    return;
                }

                courseSelect.innerHTML = '<option value="" disabled selected>Loading...</option>';
                courseSelect.disabled = true;

                fetch(`/api/departments/${departmentId}/courses`)
                    .then(response => response.json())
                    .then(courses => {
                        courseSelect.innerHTML = '<option value="">— None —</option>';

                        if (courses.length === 0) {
                            courseSelect.innerHTML = '<option value="" selected>No degree programs available</option>';
                            courseSelect.disabled = false;
                            return;
                        }

                        courses.forEach(course => {
                            const option = document.createElement('option');
                            option.value = course.id;
                            option.textContent = course.course_name;
                            if (oldCourseId && oldCourseId == course.id) {
                                option.selected = true;
                            }
                            courseSelect.appendChild(option);
                        });

                        courseSelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error fetching degree programs:', error);
                        courseSelect.innerHTML = '<option value="" disabled selected>Error loading programs</option>';
                        courseSelect.disabled = true;
                    });
            });

            // Trigger on page load if department is pre-selected (e.g. after validation error)
            if (departmentSelect.value) {
                departmentSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
</x-admin-layout>
