@extends('layouts.admin.app')

@section('title', 'My Profile')

@section('page', 'profile')

@section('content')
    <div class="mx-auto max-w-(--breakpoint-2xl) p-4 md:p-6">
        <!-- Breadcrumb Start -->
        <div x-data="{ pageName: `Profile` }">
            <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90" x-text="pageName"></h2>

                <nav>
                    <ol class="flex items-center gap-1.5">
                        <li>
                            <a class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400"
                                href="{{ route('homeDashboard') }}">
                                Home
                                <svg class="stroke-current" width="17" height="16" viewBox="0 0 17 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6.0765 12.667L10.2432 8.50033L6.0765 4.33366" stroke=""
                                        stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                        </li>
                        <li class="text-sm text-gray-800 dark:text-white/90" x-text="pageName"></li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Breadcrumb End -->

        <div class="rounded-2xl border border-gray-200 bg-white p-5 lg:p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h3 class="mb-5 text-lg font-semibold text-gray-800 lg:mb-7 dark:text-white/90">
                Profile
            </h3>

            <div class="mb-6 rounded-2xl border border-gray-200 p-5 lg:p-6 dark:border-gray-800">
                <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
                    <div class="flex w-full flex-col items-center gap-6 xl:flex-row">
                        <div class="h-20 w-20 overflow-hidden rounded-full border border-gray-200 dark:border-gray-800">
                            <img src="{{ asset('storage/' . $user->image) }}" class="w-full object-cover" alt="user" />
                        </div>
                        <div class="order-3 xl:order-2">
                            <h4
                                class="mb-2 text-center text-lg font-semibold text-gray-800 xl:text-left dark:text-white/90 profile-full-name">
                                {{ $user->full_name }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-6 rounded-2xl border border-gray-200 p-5 lg:p-6 dark:border-gray-800">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800 lg:mb-6 dark:text-white/90">
                            Personal Information
                        </h4>

                        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-7 2xl:gap-x-32">
                            <div>
                                <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                    Full Name
                                </p>
                                <p class="text-sm font-medium text-gray-800 dark:text-white/90 profile-full-name">
                                    {{ $user->full_name }}
                                </p>
                            </div>

                            <div>
                                <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                    Email address
                                </p>
                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                    {{ $user->email }}
                                </p>
                            </div>

                            <div>
                                <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                    Phone
                                </p>
                                <p class="text-sm font-medium text-gray-800 dark:text-white/90 profile-phone-number">
                                    {{ $user->phone_number }}
                                </p>
                            </div>

                            <div>
                                <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                    Role
                                </p>
                                <p class="text-sm font-medium text-gray-800 dark:text-white/90 profile-role">
                                    {{ $user->role }}
                                </p>
                            </div>

                            <div>
                                <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">
                                    Email verified at
                                </p>
                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                    {{ $user->email_verified_at }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <button id="edit-profile-btn" data-user-id="{{ $user->id }}"
                        class="shadow-theme-xs flex w-full items-center justify-center gap-2 rounded-full border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-800 lg:inline-flex lg:w-auto dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                        <svg class="fill-current" width="18" height="18" viewBox="0 0 18 18" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M15.0911 2.78206C14.2125 1.90338 12.7878 1.90338 11.9092 2.78206L4.57524 10.116C4.26682 10.4244 4.0547 10.8158 3.96468 11.2426L3.31231 14.3352C3.25997 14.5833 3.33653 14.841 3.51583 15.0203C3.69512 15.1996 3.95286 15.2761 4.20096 15.2238L7.29355 14.5714C7.72031 14.4814 8.11172 14.2693 8.42013 13.9609L15.7541 6.62695C16.6327 5.74827 16.6327 4.32365 15.7541 3.44497L15.0911 2.78206ZM12.9698 3.84272C13.2627 3.54982 13.7376 3.54982 14.0305 3.84272L14.6934 4.50563C14.9863 4.79852 14.9863 5.2734 14.6934 5.56629L14.044 6.21573L12.3204 4.49215L12.9698 3.84272ZM11.2597 5.55281L5.6359 11.1766C5.53309 11.2794 5.46238 11.4099 5.43238 11.5522L5.01758 13.5185L6.98394 13.1037C7.1262 13.0737 7.25666 13.003 7.35947 12.9002L12.9833 7.27639L11.2597 5.55281Z"
                                fill="" />
                        </svg>
                        Edit
                    </button>
                </div>
            </div>
            <div class="rounded-2xl border border-gray-200 p-5 lg:p-6 dark:border-gray-800">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800 lg:mb-6 dark:text-white/90">
                            Address
                        </h4>

                        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-7 2xl:gap-x-32">
                            <div>
                                <p class="text-sm font-medium text-gray-800 dark:text-white/90 profile-address">
                                    {{ $user->address }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <button id="edit-address-btn" data-user-id="{{ $user->id }}"
                        class="shadow-theme-xs flex w-full items-center justify-center gap-2 rounded-full border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-800 lg:inline-flex lg:w-auto dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                        <svg class="fill-current" width="18" height="18" viewBox="0 0 18 18" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M15.0911 2.78206C14.2125 1.90338 12.7878 1.90338 11.9092 2.78206L4.57524 10.116C4.26682 10.4244 4.0547 10.8158 3.96468 11.2426L3.31231 14.3352C3.25997 14.5833 3.33653 14.841 3.51583 15.0203C3.69512 15.1996 3.95286 15.2761 4.20096 15.2238L7.29355 14.5714C7.72031 14.4814 8.11172 14.2693 8.42013 13.9609L15.7541 6.62695C16.6327 5.74827 16.6327 4.32365 15.7541 3.44497L15.0911 2.78206ZM12.9698 3.84272C13.2627 3.54982 13.7376 3.54982 14.0305 3.84272L14.6934 4.50563C14.9863 4.79852 14.9863 5.2734 14.6934 5.56629L14.044 6.21573L12.3204 4.49215L12.9698 3.84272ZM11.2597 5.55281L5.6359 11.1766C5.53309 11.2794 5.46238 11.4099 5.43238 11.5522L5.01758 13.5185L6.98394 13.1037C7.1262 13.0737 7.25666 13.003 7.35947 12.9002L12.9833 7.27639L11.2597 5.55281Z"
                                fill="" />
                        </svg>
                        Edit
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('modal')
    <!-- BEGIN MODAL -->
    <div id="profile-info-modal" class="fixed inset-0 z-99999 hidden items-center justify-center overflow-y-auto p-5"
        aria-hidden="true">
        <div class="modal-close-btn fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[32px]"></div>
        <div
            class="no-scrollbar relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-4 lg:p-11 dark:bg-gray-900">
            <!-- close btn -->
            <button
                class="close-info-modal transition-color absolute top-5 right-5 z-999 flex h-11 w-11 items-center justify-center rounded-full bg-gray-100 text-gray-400 hover:bg-gray-200 hover:text-gray-600 dark:bg-gray-700 dark:bg-white/[0.05] dark:text-gray-400 dark:hover:bg-white/[0.07] dark:hover:text-gray-300">
                <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M6.04289 16.5418C5.65237 16.9323 5.65237 17.5655 6.04289 17.956C6.43342 18.3465 7.06658 18.3465 7.45711 17.956L11.9987 13.4144L16.5408 17.9565C16.9313 18.347 17.5645 18.347 17.955 17.9565C18.3455 17.566 18.3455 16.9328 17.955 16.5423L13.4129 12.0002L17.955 7.45808C18.3455 7.06756 18.3455 6.43439 17.955 6.04387C17.5645 5.65335 16.9313 5.65335 16.5408 6.04387L11.9987 10.586L7.45711 6.04439C7.06658 5.65386 6.43342 5.65386 6.04289 6.04439C5.65237 6.43491 5.65237 7.06808 6.04289 7.4586L10.5845 12.0002L6.04289 16.5418Z"
                        fill="" />
                </svg>
            </button>
            <div class="px-2 pr-14">
                <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                    Edit Personal Information
                </h4>
                <p class="mb-6 text-sm text-gray-500 lg:mb-7 dark:text-gray-400">
                    Update your details to keep your profile up-to-date.
                </p>
            </div>
            <form id="personal-info-form" class="flex flex-col">
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <div class="custom-scrollbar h-[450px] overflow-y-auto px-2">
                    <div class="mt-7">
                        <h5 class="mb-5 text-lg font-medium text-gray-800 lg:mb-6 dark:text-white/90">
                            Personal Information
                        </h5>

                        <div class="grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2">
                            <div class="col-span-2 lg:col-span-1">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Full Name
                                </label>
                                <input type="text" id="edit_full_name" name="full_name"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            </div>

                            <div class="col-span-2 lg:col-span-1">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Email Address
                                </label>
                                <input type="text" value="{{ $user->email }}" disabled
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-gray-50 bg-none px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-800 dark:text-white/90 dark:placeholder:text-white/30 cursor-not-allowed" />
                            </div>

                            <div class="col-span-2 lg:col-span-1">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Phone
                                </label>
                                <input type="text" id="edit_phone_number" name="phone_number"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            </div>

                            <div class="col-span-2 lg:col-span-1">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Role
                                </label>
                                <select id="edit_role" name="role"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                    <option value="non_member">Non Member</option>
                                    <option value="member">Member</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex items-center gap-3 px-2 lg:justify-end">
                    <button type="button"
                        class="close-info-modal flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 sm:w-auto dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                        Close
                    </button>
                    <button type="submit" id="save-changes-btn"
                        class="bg-brand-500 hover:bg-brand-600 flex w-full justify-center rounded-lg px-4 py-2.5 text-sm font-medium text-white sm:w-auto">
                        Save Changes
                    </button>
                </div>
            </form>
            <!-- Hidden form element to store action URL -->
            <form id="edit-profile-form" class="hidden"></form>
        </div>
    </div>

    <div id="profile-address-modal" class="fixed inset-0 z-99999 hidden items-center justify-center overflow-y-auto p-5"
        aria-hidden="true">
        <div class="modal-close-btn fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[32px]"></div>
        <div
            class="no-scrollbar relative flex w-full max-w-[700px] flex-col overflow-y-auto rounded-3xl bg-white p-6 lg:p-11 dark:bg-gray-900">
            <!-- close btn -->
            <button
                class="close-address-modal transition-color absolute top-5 right-5 z-999 flex h-11 w-11 items-center justify-center rounded-full bg-gray-100 text-gray-400 hover:bg-gray-200 hover:text-gray-600 dark:bg-gray-700 dark:bg-white/[0.05] dark:text-gray-400 dark:hover:bg-white/[0.07] dark:hover:text-gray-300">
                <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M6.04289 16.5418C5.65237 16.9323 5.65237 17.5655 6.04289 17.956C6.43342 18.3465 7.06658 18.3465 7.45711 17.956L11.9987 13.4144L16.5408 17.9565C16.9313 18.347 17.5645 18.347 17.955 17.9565C18.3455 17.566 18.3455 16.9328 17.955 16.5423L13.4129 12.0002L17.955 7.45808C18.3455 7.06756 18.3455 6.43439 17.955 6.04387C17.5645 5.65335 16.9313 5.65335 16.5408 6.04387L11.9987 10.586L7.45711 6.04439C7.06658 5.65386 6.43342 5.65386 6.04289 6.04439C5.65237 6.43491 5.65237 7.06808 6.04289 7.4586L10.5845 12.0002L6.04289 16.5418Z"
                        fill="" />
                </svg>
            </button>

            <div class="px-2 pr-14">
                <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">
                    Edit Address
                </h4>
                <p class="mb-6 text-sm text-gray-500 lg:mb-7 dark:text-gray-400">
                    Update your details to keep your profile up-to-date.
                </p>
            </div>
            <form id="address-form" class="flex flex-col">
                <div class="custom-scrollbar overflow-y-auto px-2">
                    <div class="grid grid-cols-1">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Address
                            </label>
                            <textarea id="edit_address" name="address" placeholder="Masukan alamat lengkap"
                                class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></textarea>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex items-center gap-3 lg:justify-end">
                    <button type="button"
                        class="close-address-modal flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 sm:w-auto dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                        Close
                    </button>
                    <button type="submit" id="save-changes-btn"
                        class="bg-brand-500 hover:bg-brand-600 flex w-full justify-center rounded-lg px-4 py-2.5 text-sm font-medium text-white sm:w-auto">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- END MODAL -->
@endsection

@push('script')
    <script>
        function editProfile(userId, type = 'profile') {
            // Set ID user yang akan diedit
            document.querySelector('#edit-profile-form').setAttribute('action', `/dashboard/users/${userId}`);

            // Fetch data user dengan AJAX dan penanganan error yang lebih baik
            fetch(`/dashboard/users/${userId}/edit`)
                .then(response => {
                    console.log('Response status:', response.status);

                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }

                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error(`Expected JSON response but got ${contentType || 'unknown content type'}`);
                    }

                    // Clone response untuk debugging jika diperlukan
                    const clonedResponse = response.clone();

                    // Parse response sebagai JSON dengan penanganan error
                    return response.json().catch(err => {
                        console.error('JSON parsing error:', err);
                        return clonedResponse.text().then(text => {
                            console.log('Raw response text:', text);
                            throw new Error('Invalid JSON response from server');
                        });
                    });
                })
                .then(data => {
                    console.log('User data received:', data);

                    // Tutup loading dialog jika menggunakan SweetAlert
                    if (window.Swal) {
                        Swal.close();
                    }

                    // Validasi data yang diterima
                    if (!data || typeof data !== 'object') {
                        throw new Error('Received invalid data structure from server');
                    }

                    // Based on type parameter, fill in the appropriate form and show the corresponding modal
                    if (type === 'profile') {
                        // Isi formulir dengan data user dengan validasi ekstra
                        document.getElementById('edit_full_name').value = data.full_name || '';
                        document.getElementById('edit_phone_number').value = data.phone_number || '';

                        // Pastikan elemen ada sebelum mencoba mengakses
                        const roleSelect = document.getElementById('edit_role');
                        if (roleSelect) {
                            roleSelect.value = data.role || 'non_member';
                        } else {
                            console.error('Role select element not found');
                        }

                        // Tampilkan modal profil
                        showProfileInfoModal();
                    } else if (type === 'address') {
                        // Isi formulir alamat dengan data user
                        document.getElementById('edit_address').value = data.address || '';

                        // Tampilkan modal alamat
                        showProfileAddressModal();
                    }
                })
                .catch(error => {
                    console.error(`Error fetching ${type} data:`, error);

                    // Tutup loading dialog jika menggunakan SweetAlert
                    if (window.Swal) {
                        Swal.close();

                        // Tampilkan pesan error yang lebih spesifik
                        Swal.fire({
                            title: 'Error!',
                            text: `Failed to load ${type} data`,
                            icon: 'error',
                            confirmButtonColor: '#DC2626',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        alert(`Error: Failed to load ${type} data. ${error.message}`);
                    }
                });
        }

        // Fungsi untuk menampilkan modal info
        function showProfileInfoModal() {
            const modal = document.getElementById('profile-info-modal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.classList.add('overflow-hidden'); // Mencegah scrolling pada background
                modal.setAttribute('aria-hidden', 'false');
            }
        }

        // Fungsi untuk menampilkan modal alamat
        function showProfileAddressModal() {
            const modal = document.getElementById('profile-address-modal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.classList.add('overflow-hidden'); // Mencegah scrolling pada background
                modal.setAttribute('aria-hidden', 'false');
            }
        }

        // Fungsi untuk menyembunyikan modal
        function hideProfileInfoModal() {
            const modal = document.getElementById('profile-info-modal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.classList.remove('overflow-hidden'); // Mengaktifkan kembali scrolling
                modal.setAttribute('aria-hidden', 'true');
            }
        }

        // Fungsi untuk menyembunyikan modal alamat
        function hideProfileAddressModal() {
            const modal = document.getElementById('profile-address-modal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.classList.remove('overflow-hidden'); // Mengaktifkan kembali scrolling
                modal.setAttribute('aria-hidden', 'true');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const saveChangesBtn = document.querySelectorAll('#save-changes-btn');
            saveChangesBtn.forEach(btn => {
                btn.addEventListener('click', function() {
                    const isInfoModalOpen = document.getElementById('profile-info-modal').classList
                        .contains('flex');
                    const isAddressModalOpen = document.getElementById('profile-address-modal')
                        .classList.contains('flex');

                    if (isInfoModalOpen) {
                        hideProfileInfoModal();
                    } else if (isAddressModalOpen) {
                        hideProfileAddressModal();
                    }
                });
            });

            // Tambahkan event listener untuk tombol edit profil
            const editProfileBtn = document.getElementById('edit-profile-btn');
            if (editProfileBtn) {
                editProfileBtn.addEventListener('click', function() {
                    const userId = this.getAttribute('data-user-id');
                    editProfile(userId, 'profile');
                });
            }

            // Tambahkan event listener untuk tombol edit alamat
            const editAddressBtn = document.getElementById('edit-address-btn');
            if (editAddressBtn) {
                editAddressBtn.addEventListener('click', function() {
                    const userId = this.getAttribute('data-user-id');
                    editProfile(userId, 'address');
                });
            }

            // Tambahkan event listener untuk tombol tutup modal
            const closeInfoButtons = document.querySelectorAll('.close-info-modal');
            closeInfoButtons.forEach(button => {
                button.addEventListener('click', hideProfileInfoModal);
            });

            // Tambahkan event listener untuk tombol tutup modal alamat
            const closeAddressButtons = document.querySelectorAll('.close-address-modal');
            closeAddressButtons.forEach(button => {
                button.addEventListener('click', hideProfileAddressModal);
            });

            // Personal Info Form Validation and Submission
            const personalInfoForm = document.getElementById('personal-info-form');
            if (personalInfoForm) {
                personalInfoForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const userId = this.querySelector('input[name="user_id"]').value;

                    // Get form inputs
                    const fullName = document.getElementById('edit_full_name');
                    const phoneNumber = document.getElementById('edit_phone_number');
                    const role = document.getElementById('edit_role');

                    // Create an object to track changes
                    const changedFields = {};

                    // Get original data from the page
                    const originalData = {
                        full_name: document.querySelector('.profile-full-name').textContent.trim(),
                        phone_number: document.querySelector('.profile-phone-number').textContent
                            .trim(),
                        role: document.querySelector('.profile-role').textContent.trim(),
                        address: document.querySelector('.profile-address').textContent.trim()
                    };

                    // Check if fields have changed from original values
                    if (fullName && fullName.value.trim() !== originalData.full_name && fullName.value
                        .trim() !== '') {
                        changedFields.full_name = fullName.value.trim();
                    }

                    if (phoneNumber && phoneNumber.value.trim() !== originalData.phone_number && phoneNumber
                        .value.trim() !== '') {
                        // Additional phone number validation
                        const phoneRegex = /^[0-9]{9,15}$/;
                        if (!phoneRegex.test(phoneNumber.value.trim())) {
                            if (window.Swal) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Invalid Phone Number',
                                    text: 'Please enter a valid phone number (9-15 digits)'
                                });
                            } else {
                                alert('Please enter a valid phone number (9-15 digits)');
                            }
                            return;
                        }
                        changedFields.phone_number = phoneNumber.value.trim();
                    }

                    if (role && role.value !== originalData.role) {
                        changedFields.role = role.value;
                    }

                    // If no changes, show message
                    if (Object.keys(changedFields).length === 0) {
                        if (window.Swal) {
                            Swal.fire({
                                icon: 'info',
                                title: 'No Changes',
                                text: 'No changes were made to the personal information'
                            });
                        } else {
                            alert('No changes were made');
                        }
                        return;
                    }

                    // If changes exist, create a new form to submit only changed fields
                    const formData = new FormData();
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]')
                        .getAttribute('content'));
                    formData.append('_method', 'PUT');

                    for (const [key, value] of Object.entries(changedFields)) {
                        formData.append(key, value);
                    }

                    // Show loading if SweetAlert available
                    if (window.Swal) {
                        Swal.fire({
                            title: 'Processing...',
                            text: 'Updating your profile',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    }

                    // Submit via fetch
                    fetch(`/dashboard/users/${userId}`, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! Status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                if (window.Swal) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Updated Successfully',
                                        text: data.message
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    alert('Updated Successfully');
                                    location.reload();
                                }
                            } else {
                                if (window.Swal) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Update Failed',
                                        text: data.message || 'An error occurred while updating'
                                    });
                                } else {
                                    alert('Update Failed: ' + (data.message || 'An error occurred'));
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            if (window.Swal) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: `An error occurred while updating: ${error.message}`
                                });
                            } else {
                                alert(`An error occurred while updating: ${error.message}`);
                            }
                        });
                });
            }

            // Address Form Validation and Submission
            const addressForm = document.getElementById('address-form');
            if (addressForm) {
                addressForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Get address input
                    const addressInput = document.getElementById('edit_address');
                    const userId = document.querySelector('#edit-profile-form').getAttribute('action')
                        .split('/').pop();

                    // Get original address data
                    const originalAddress = document.querySelector('.profile-address').textContent.trim();

                    // Check if address has changed and is not empty
                    if (addressInput && addressInput.value.trim() !== originalAddress && addressInput.value
                        .trim() !== '') {
                        // Create form data
                        const formData = new FormData();
                        formData.append('_token', document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content'));
                        formData.append('_method', 'PUT');
                        formData.append('address', addressInput.value.trim());

                        // Show loading if SweetAlert available
                        if (window.Swal) {
                            Swal.fire({
                                title: 'Processing...',
                                text: 'Updating your address',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        }

                        // Submit via fetch
                        fetch(`/dashboard/users/${userId}`, {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: formData
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(`HTTP error! Status: ${response.status}`);
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    if (window.Swal) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Updated Successfully',
                                            text: data.message
                                        }).then(() => {
                                            location.reload();
                                        });
                                    } else {
                                        alert('Updated Successfully');
                                        location.reload();
                                    }
                                } else {
                                    if (window.Swal) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Update Failed',
                                            text: data.message ||
                                                'An error occurred while updating'
                                        });
                                    } else {
                                        alert('Update Failed: ' + (data.message ||
                                            'An error occurred'));
                                    }
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                if (window.Swal) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: `An error occurred while updating: ${error.message}`
                                    });
                                } else {
                                    alert(`An error occurred while updating: ${error.message}`);
                                }
                            });
                    } else {
                        // No changes or empty input
                        if (window.Swal) {
                            Swal.fire({
                                icon: 'info',
                                title: 'No Changes',
                                text: 'No changes were made to the address'
                            });
                        } else {
                            alert('No changes were made');
                        }
                    }
                });
            }
        });
    </script>
@endpush
