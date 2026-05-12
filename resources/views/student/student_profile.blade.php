<x-student-layout :title="'Profile'">
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-0 sm:px-6 lg:px-8 space-y-6 mt-10">
            <div class="p-4 sm:p-8 bg-white flex justify-center">
                <div class="w-full max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white flex justify-center">
                <div class="w-full max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-student-layout>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fileInput = document.getElementById('profile_photo');
            const form = fileInput.closest('form');
            const profileImage = document.querySelector('.rounded-full');

            fileInput.addEventListener('change', function (e) {
                if (this.files && this.files[0]) {
                    // Validate file size (2MB)
                    if (this.files[0].size > 2 * 1024 * 1024) {
                        alert('File size must be less than 2MB');
                        this.value = '';
                        return;
                    }

                    // Validate file type
                    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                    if (!allowedTypes.includes(this.files[0].type)) {
                        alert('Only JPG, PNG, and GIF files are allowed');
                        this.value = '';
                        return;
                    }

                    // Show preview
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        profileImage.src = e.target.result;
                    }
                    reader.readAsDataURL(this.files[0]);

                    // Submit form
                    form.submit();
                }
            });
        });
    </script>
@endpush
