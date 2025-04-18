@extends('layouts.admin.app')

@section('title', 'User Details')
@section('page', 'users')

@section('content')
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
        <!-- Breadcrumb Start -->
        <div x-data="{ pageName: 'User Details' }">
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
                        <li>
                            <a class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400"
                                href="{{ route('admin.users') }}">
                                Users
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
                <div class="px-5 py-4 sm:px-6 sm:py-5 flex justify-between items-center">
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                        User Information
                    </h3>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.users') }}"
                            class="inline-flex items-center gap-2 rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                            Back to Users
                        </a>
                        <button onclick="editUser('{{ $user->id }}')"
                            class="cursor-pointer inline-flex items-center justify-center w-8 h-8 rounded-full bg-warning-500 hover:bg-warning-600 text-white"
                            title="Edit">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path
                                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                <path fill-rule="evenodd"
                                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                            </svg>
                        </button>

                        <!-- Tombol Delete User -->
                        <button onclick="confirmDelete('{{ $user->id }}', '{{ $user->full_name }}')"
                            class="cursor-pointer inline-flex items-center justify-center w-8 h-8 rounded-full bg-error-500 hover:bg-error-600 text-white ml-1"
                            title="Delete">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path
                                    d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                <path fill-rule="evenodd"
                                    d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                            </svg>
                        </button>

                        <!-- Form Delete User (hidden) -->
                        <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}"
                            method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>

                        <!-- Hidden delete form -->
                        <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}"
                            method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>

                <div class="p-5 border-t border-gray-100 dark:border-gray-800 sm:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- User basic info card -->
                        <div class="bg-gray-50 dark:bg-gray-900 p-5 rounded-lg">
                            <h4 class="text-lg font-medium text-gray-800 dark:text-white/90 mb-4">Personal Information</h4>

                            <div class="space-y-4">
                                <!-- Full Name -->
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Full Name</span>
                                    <span class="text-gray-800 dark:text-white/90 mt-1">{{ $user->full_name }}</span>
                                </div>

                                <!-- Username -->
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Username</span>
                                    <span class="text-gray-800 dark:text-white/90 mt-1">{{ $user->username }}</span>
                                </div>

                                <!-- Email -->
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</span>
                                    <span class="text-gray-800 dark:text-white/90 mt-1">{{ $user->email }}</span>
                                </div>

                                <!-- Phone Number -->
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone Number</span>
                                    <span class="text-gray-800 dark:text-white/90 mt-1">{{ $user->phone_number }}</span>
                                </div>

                                <!-- Address -->
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Address</span>
                                    <span
                                        class="text-gray-800 dark:text-white/90 mt-1">{{ $user->address ?: 'Not provided' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- User account info card -->
                        <div class="bg-gray-50 dark:bg-gray-900 p-5 rounded-lg">
                            <h4 class="text-lg font-medium text-gray-800 dark:text-white/90 mb-4">Account Information</h4>

                            <div class="space-y-4">
                                <!-- Role -->
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Role</span>
                                    <span
                                        class="inline-flex rounded-full py-1 px-3 text-sm font-medium mt-1 w-fit
                                        {{ $user->role === 'admin'
                                            ? 'bg-success-50 text-success-700 dark:bg-success-500/15 dark:text-success-500'
                                            : ($user->role === 'member'
                                                ? 'bg-brand-50 text-brand-700 dark:bg-brand-500/15 dark:text-brand-400'
                                                : 'bg-gray-50 text-gray-700 dark:bg-gray-500/15 dark:text-white-400') }}">
                                        {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                    </span>
                                </div>

                                <!-- Email Verification Status -->
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Email Verification
                                        Status</span>
                                    <span
                                        class="inline-flex rounded-full py-1 px-3 text-sm font-medium mt-1 w-fit
                                        {{ $user->email_verified_at
                                            ? 'bg-success-50 text-success-700 dark:bg-success-500/15 dark:text-success-500'
                                            : 'bg-error-50 text-error-700 dark:bg-error-500/15 dark:text-error-500' }}">
                                        {{ $user->email_verified_at ? 'Verified' : 'Not Verified' }}
                                    </span>
                                </div>

                                <!-- Date Email Verified -->
                                @if ($user->email_verified_at)
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Email Verified
                                            At</span>
                                        <span class="text-gray-800 dark:text-white/90 mt-1">
                                            {{ $user->email_verified_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }}
                                        </span>
                                    </div>
                                @endif

                                <!-- Registration Date -->
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Registration
                                        Date</span>
                                    <span class="text-gray-800 dark:text-white/90 mt-1">
                                        {{ $user->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }}
                                    </span>
                                </div>

                                <!-- Last Updated -->
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</span>
                                    <span class="text-gray-800 dark:text-white/90 mt-1">
                                        {{ $user->updated_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.users.edit-modal')
@endsection

@push('script')
    <script>
        // Fungsi untuk edit user dengan penanganan error yang lebih baik
        function editUser(userId) {
            // Show loading dengan SweetAlert2 (jika tersedia)
            if (window.Swal) {
                Swal.fire({
                    title: 'Loading...',
                    text: 'Fetching user data for editing',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });
            } else {
                // Fallback jika SweetAlert tidak tersedia
            }

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
                        // Pastikan nilai yang valid dipilih
                        if (data.role && (data.role === 'admin' || data.role === 'user')) {
                            roleSelect.value = data.role;
                        }
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
                            text: `Failed to load user data: ${error.message}. Please check the developer console for more details.`,
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
                modal.classList.add('block');
                document.body.classList.add('overflow-hidden'); // Mencegah scrolling pada background
                modal.setAttribute('aria-hidden', 'false');
            }
        }

        // Fungsi untuk menyembunyikan modal
        function hideEditModal() {
            const modal = document.getElementById('edit-modal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('block');
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

            // Event listener untuk klik di luar modal (optional)
            const modal = document.getElementById('edit-modal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        hideEditModal();
                    }
                });
            }
        });
    </script>
@endpush
