@extends('layouts.admin.app')

@section('title', 'List Users')

@section('page', 'users')

@section('content')
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
        <!-- Breadcrumb Start -->
        <div x-data="{ pageName: 'Users' }">
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

        <div class="space-y-5 sm:space-y-6">
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="px-5 py-4 sm:px-6 sm:py-5 flex justify-between items-center" x-data="{ open: false }">
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                        List User
                    </h3>
                    <button @click="open = !open"
                        class="shadow-theme-xs inline-flex items-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
                        Add User

                        <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M9.77692 3.24224C9.91768 3.17186 10.0834 3.17186 10.2241 3.24224L15.3713 5.81573L10.3359 8.33331C10.1248 8.43888 9.87626 8.43888 9.66512 8.33331L4.6298 5.81573L9.77692 3.24224ZM3.70264 7.0292V13.4124C3.70264 13.6018 3.80964 13.775 3.97903 13.8597L9.25016 16.4952L9.25016 9.7837C9.16327 9.75296 9.07782 9.71671 8.99432 9.67496L3.70264 7.0292ZM10.7502 16.4955V9.78396C10.8373 9.75316 10.923 9.71683 11.0067 9.67496L16.2984 7.0292V13.4124C16.2984 13.6018 16.1914 13.775 16.022 13.8597L10.7502 16.4955ZM9.41463 17.4831L9.10612 18.1002C9.66916 18.3817 10.3319 18.3817 10.8949 18.1002L16.6928 15.2013C17.3704 14.8625 17.7984 14.17 17.7984 13.4124V6.58831C17.7984 5.83076 17.3704 5.13823 16.6928 4.79945L10.8949 1.90059C10.3319 1.61908 9.66916 1.61907 9.10612 1.90059L9.44152 2.57141L9.10612 1.90059L3.30823 4.79945C2.63065 5.13823 2.20264 5.83076 2.20264 6.58831V13.4124C2.20264 14.17 2.63065 14.8625 3.30823 15.2013L9.10612 18.1002L9.41463 17.4831Z"
                                fill="" />
                        </svg>
                    </button>

                    <!-- Create User Modal -->
                    <div x-show="open" class="fixed inset-0 flex items-center justify-center z-[999999]">
                        <div @click="open = false" class="fixed inset-0 bg-black opacity-50 z-[999998]"></div>
                        <!-- Make background slightly lower z-index -->
                        @include('dashboard.users.create-modal')
                    </div>
                    <!-- End Create User Modal -->

                </div>
                <div class="p-5 border-t border-gray-100 dark:border-gray-800 sm:p-6">
                    <!-- ====== Table Six Start -->
                    <div
                        class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                        <div class="max-w-full overflow-x-auto">
                            <table class="min-w-full">
                                <!-- table header start -->
                                <thead>
                                    <tr class="border-b border-gray-100 dark:border-gray-800">
                                        <th class="px-5 py-3 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    User
                                                </p>
                                            </div>
                                        </th>
                                        <th class="px-5 py-3 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    Email
                                                </p>
                                            </div>
                                        </th>
                                        <th class="px-5 py-3 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    Phone Number
                                                </p>
                                            </div>
                                        </th>
                                        <th class="px-5 py-3 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    Email Verified At
                                                </p>
                                            </div>
                                        </th>
                                        <th class="px-5 py-3 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    Action
                                                </p>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <!-- table header end -->
                                <!-- table body start -->
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                    @yield('tbody_content')
                                    @foreach ($users as $user)
                                        <tr>
                                            <td class="px-5 py-4 sm:px-6">
                                                <div class="flex items-center">
                                                    <div class="flex items-center gap-3">
                                                        <div>
                                                            <span
                                                                class="block font-medium text-gray-800 text-theme-sm dark:text-white/90">
                                                                {{ $user->full_name }}
                                                            </span>
                                                            <span
                                                                class="block text-gray-500 text-theme-xs dark:text-gray-400">
                                                                {{ $user->role }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 sm:px-6">
                                                <div class="flex items-center">
                                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                        {{ $user->email }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 sm:px-6">
                                                <div class="flex items-center">
                                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                        {{ $user->phone_number }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 sm:px-6">
                                                <div class="flex items-center">
                                                    <p
                                                        class="rounded-full bg-success-50 px-2 py-0.5 text-theme-xs font-medium text-success-700 dark:bg-success-500/15 dark:text-success-500">
                                                        {{ $user->email_verified_at }}
                                                    </p>
                                                </div>
                                            </td>
                                            <!-- Update the action buttons in the table -->
                                            <td class="px-5 py-4 sm:px-6">
                                                <div class="flex items-center gap-2">
                                                    <a href="{{ route('admin.users.show', $user->id) }}"
                                                        class="cursor-pointer inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-500 hover:bg-blue-600 text-white"
                                                        title="View">
                                                        <svg fill="#ff0000" version="1.1" id="Capa_1"
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="64px"
                                                            height="64px" viewBox="0 0 442.04 442.04" xml:space="preserve"
                                                            stroke="#ff0000">
                                                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                                stroke-linejoin="round"></g>
                                                            <g id="SVGRepo_iconCarrier">
                                                                <g>
                                                                    <g>
                                                                        <path
                                                                            d="M221.02,341.304c-49.708,0-103.206-19.44-154.71-56.22C27.808,257.59,4.044,230.351,3.051,229.203 c-4.068-4.697-4.068-11.669,0-16.367c0.993-1.146,24.756-28.387,63.259-55.881c51.505-36.777,105.003-56.219,154.71-56.219 c49.708,0,103.207,19.441,154.71,56.219c38.502,27.494,62.266,54.734,63.259,55.881c4.068,4.697,4.068,11.669,0,16.367 c-0.993,1.146-24.756,28.387-63.259,55.881C324.227,321.863,270.729,341.304,221.02,341.304z M29.638,221.021 c9.61,9.799,27.747,27.03,51.694,44.071c32.83,23.361,83.714,51.212,139.688,51.212s106.859-27.851,139.688-51.212 c23.944-17.038,42.082-34.271,51.694-44.071c-9.609-9.799-27.747-27.03-51.694-44.071 c-32.829-23.362-83.714-51.212-139.688-51.212s-106.858,27.85-139.688,51.212C57.388,193.988,39.25,211.219,29.638,221.021z">
                                                                        </path>
                                                                    </g>
                                                                    <g>
                                                                        <path
                                                                            d="M221.02,298.521c-42.734,0-77.5-34.767-77.5-77.5c0-42.733,34.766-77.5,77.5-77.5c18.794,0,36.924,6.814,51.048,19.188 c5.193,4.549,5.715,12.446,1.166,17.639c-4.549,5.193-12.447,5.714-17.639,1.166c-9.564-8.379-21.844-12.993-34.576-12.993 c-28.949,0-52.5,23.552-52.5,52.5s23.551,52.5,52.5,52.5c28.95,0,52.5-23.552,52.5-52.5c0-6.903,5.597-12.5,12.5-12.5 s12.5,5.597,12.5,12.5C298.521,263.754,263.754,298.521,221.02,298.521z">
                                                                        </path>
                                                                    </g>
                                                                    <g>
                                                                        <path
                                                                            d="M221.02,246.021c-13.785,0-25-11.215-25-25s11.215-25,25-25c13.786,0,25,11.215,25,25S234.806,246.021,221.02,246.021z">
                                                                        </path>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </svg>
                                                    </a>
                                                    <button onclick="editUser('{{ $user->id }}')"
                                                        class="cursor-pointer inline-flex items-center justify-center w-8 h-8 rounded-full bg-warning-500 hover:bg-warning-600 text-white"
                                                        title="Edit">
                                                        <svg width="16" height="16" fill="currentColor"
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                            <path fill-rule="evenodd"
                                                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                                        </svg>
                                                    </button>

                                                    <!-- Tombol Delete User -->
                                                    <button
                                                        onclick="confirmDelete('{{ $user->id }}', '{{ $user->full_name }}')"
                                                        class="cursor-pointer inline-flex items-center justify-center w-8 h-8 rounded-full bg-error-500 hover:bg-error-600 text-white ml-1"
                                                        title="Delete">
                                                        <svg width="16" height="16" fill="currentColor"
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                                            <path fill-rule="evenodd"
                                                                d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                                                        </svg>
                                                    </button>

                                                    <!-- Form Delete User (hidden) -->
                                                    <form id="delete-form-{{ $user->id }}"
                                                        action="{{ route('admin.users.destroy', $user->id) }}"
                                                        method="POST" class="hidden">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- ====== Table Six End -->
                </div>
            </div>
        </div>

        
        @include('dashboard.users.edit-modal')

    </div>

@endsection

@push('script')
{{-- users.blade.php --}}
    <script>
        // Fungsi untuk edit user dengan penanganan error yang lebih baik
        function editUser(userId) {

            // Set ID user yang akan diedit
            document.querySelector('#edit-user-form').setAttribute('action', `/dashboard/users/${userId}`);

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

                    // Isi formulir dengan data user dengan validasi ekstra
                    document.getElementById('edit_full_name').value = data.full_name || '';
                    document.getElementById('edit_username').value = data.username || '';
                    document.getElementById('edit_email').value = data.email || '';

                    // Format nomor telepon
                    let phoneNumber = data.phone_number || '';
                    if (phoneNumber && phoneNumber.startsWith('+62')) {
                        phoneNumber = phoneNumber.substring(3);
                    }
                    document.getElementById('edit_phone_number').value = phoneNumber;

                    document.getElementById('edit_address').value = data.address || '';

                    // Pastikan elemen ada sebelum mencoba mengakses
                    const roleSelect = document.getElementById('edit_role');
                    if (roleSelect) {
                        roleSelect.value = data.role || 'non_member';
                    } else {
                        console.error('Role select element not found');
                    }

                    const verifiedCheckbox = document.getElementById('edit_verified');
                    if (verifiedCheckbox) {
                        verifiedCheckbox.checked = data.email_verified_at !== null;
                    }

                    // Tampilkan modal
                    showEditModal();
                })
                .catch(error => {
                    console.error('Error fetching user data:', error);

                    // Tutup loading dialog jika menggunakan SweetAlert
                    if (window.Swal) {
                        Swal.close();

                        // Tampilkan pesan error yang lebih spesifik
                        Swal.fire({
                            title: 'Error!',
                            text: `Failed`,
                            icon: 'error',
                            confirmButtonColor: '#DC2626',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        alert(`Error: Failed to load user data. ${error.message}`);
                    }

                    // Cek route dengan menggunakan AJAX sederhana untuk debugging
                    console.log('Checking route availability...');
                    fetch('/dashboard/users')
                        .then(response => {
                            console.log('Users index route status:', response.status);
                        })
                        .catch(err => {
                            console.error('Could not reach users index:', err);
                        });
                });
        }

        // Fungsi untuk menampilkan modal
        function showEditModal() {
            const modal = document.getElementById('edit-modal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.classList.add('overflow-hidden'); // Mencegah scrolling pada background
                modal.setAttribute('aria-hidden', 'false');
            }
        }

        // Fungsi untuk menyembunyikan modal
        function hideEditModal() {
            const modal = document.getElementById('edit-modal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.classList.remove('overflow-hidden'); // Mengaktifkan kembali scrolling
                modal.setAttribute('aria-hidden', 'true');
            }
        }

        // Fungsi untuk konfirmasi delete
        function confirmDelete(userId, userName) {
            if (window.Swal) {
                Swal.fire({
                    title: 'Confirm Deletion',
                    text: `Are you sure you want to delete the user "${userName}"? This action cannot be undone.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#DC2626',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`delete-form-${userId}`).submit();
                    }
                });
            } else {
                // Fallback jika SweetAlert tidak tersedia
                if (confirm(`Are you sure you want to delete the user "${userName}"? This action cannot be undone.`)) {
                    document.getElementById(`delete-form-${userId}`).submit();
                }
            }
        }

        // Inisialisasi event listener
        document.addEventListener('DOMContentLoaded', function() {
            // Tambahkan event listener untuk tombol tutup modal
            const closeButtons = document.querySelectorAll('.close-modal');
            closeButtons.forEach(button => {
                button.addEventListener('click', hideEditModal);
            });

            // Event listener untuk submit form
            const submitButton = document.querySelector('#submit-edit-form');
            if (submitButton) {
                submitButton.addEventListener('click', function() {
                    document.getElementById('edit-user-form').submit();
                });
            }
        });
    </script>
@endpush
