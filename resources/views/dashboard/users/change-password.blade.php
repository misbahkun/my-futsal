@extends('layouts.admin.app')

@section('title', 'Change Password')

@section('page', 'change-password')

@section('content')
    <div class="mx-auto max-w-(--breakpoint-2xl) p-4 md:p-6">
        <!-- Breadcrumb Start -->
        <div x-data="{ pageName: `Change Password` }">
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
                Change Password
            </h3>

            @if (session('success'))
                <div
                    class="mb-6 rounded-lg bg-success-50 p-4 text-success-500 dark:bg-success-500/10 dark:text-success-400">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 rounded-lg bg-error-50 p-4 text-error-500 dark:bg-error-500/10 dark:text-error-400">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="mb-6 rounded-2xl border border-gray-200 p-5 lg:p-6 dark:border-gray-800">
                <form method="POST" action="{{ route('password.update') }}" id="change-password-form">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-y-5 lg:max-w-xl">
                        <div>
                            <label for="current_password"
                                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Current Password
                            </label>
                            <div class="relative">
                                <input type="password" id="current_password" name="current_password"
                                    class="@error('current_password') border-error-500 @enderror shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                    required autocomplete="current-password">
                                <button type="button"
                                    class="toggle-password absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-400"
                                    data-target="current_password">
                                    <svg class="eye-icon fill-current" width="22" height="22" viewBox="0 0 22 22"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M11 4.5C6.5 4.5 2.73 7.61 1 12C2.73 16.39 6.5 19.5 11 19.5C15.5 19.5 19.27 16.39 21 12C19.27 7.61 15.5 4.5 11 4.5ZM11 17C8.24 17 6 14.76 6 12C6 9.24 8.24 7 11 7C13.76 7 16 9.24 16 12C16 14.76 13.76 17 11 17ZM11 9C9.34 9 8 10.34 8 12C8 13.66 9.34 15 11 15C12.66 15 14 13.66 14 12C14 10.34 12.66 9 11 9Z" />
                                    </svg>
                                    <svg class="eye-off-icon fill-current hidden" width="22" height="22"
                                        viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M11 4.5C6.5 4.5 2.73 7.61 1 12C2.73 16.39 6.5 19.5 11 19.5C15.5 19.5 19.27 16.39 21 12C19.27 7.61 15.5 4.5 11 4.5ZM11 17C8.24 17 6 14.76 6 12C6 9.24 8.24 7 11 7C13.76 7 16 9.24 16 12C16 14.76 13.76 17 11 17ZM11 9C9.34 9 8 10.34 8 12C8 13.66 9.34 15 11 15C12.66 15 14 13.66 14 12C14 10.34 12.66 9 11 9Z" />
                                        <path
                                            d="M2.46967 2.46967C2.76256 2.17678 3.23744 2.17678 3.53033 2.46967L19.5303 18.4697C19.8232 18.7626 19.8232 19.2374 19.5303 19.5303C19.2374 19.8232 18.7626 19.8232 18.4697 19.5303L2.46967 3.53033C2.17678 3.23744 2.17678 2.76256 2.46967 2.46967Z"
                                            fill="currentColor" stroke="currentColor" stroke-width="0.5" />
                                    </svg>
                                </button>
                            </div>
                            @error('current_password')
                                <p class="mt-1 text-sm text-error-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                New Password
                            </label>
                            <div class="relative">
                                <input type="password" id="password" name="password"
                                    class="@error('password') border-error-500 @enderror shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                    required autocomplete="new-password">
                                <button type="button"
                                    class="toggle-password absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-400"
                                    data-target="password">
                                    <svg class="eye-icon fill-current" width="22" height="22" viewBox="0 0 22 22"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M11 4.5C6.5 4.5 2.73 7.61 1 12C2.73 16.39 6.5 19.5 11 19.5C15.5 19.5 19.27 16.39 21 12C19.27 7.61 15.5 4.5 11 4.5ZM11 17C8.24 17 6 14.76 6 12C6 9.24 8.24 7 11 7C13.76 7 16 9.24 16 12C16 14.76 13.76 17 11 17ZM11 9C9.34 9 8 10.34 8 12C8 13.66 9.34 15 11 15C12.66 15 14 13.66 14 12C14 10.34 12.66 9 11 9Z" />
                                    </svg>
                                    <svg class="eye-off-icon fill-current hidden" width="22" height="22"
                                        viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M11 4.5C6.5 4.5 2.73 7.61 1 12C2.73 16.39 6.5 19.5 11 19.5C15.5 19.5 19.27 16.39 21 12C19.27 7.61 15.5 4.5 11 4.5ZM11 17C8.24 17 6 14.76 6 12C6 9.24 8.24 7 11 7C13.76 7 16 9.24 16 12C16 14.76 13.76 17 11 17ZM11 9C9.34 9 8 10.34 8 12C8 13.66 9.34 15 11 15C12.66 15 14 13.66 14 12C14 10.34 12.66 9 11 9Z" />
                                        <path
                                            d="M2.46967 2.46967C2.76256 2.17678 3.23744 2.17678 3.53033 2.46967L19.5303 18.4697C19.8232 18.7626 19.8232 19.2374 19.5303 19.5303C19.2374 19.8232 18.7626 19.8232 18.4697 19.5303L2.46967 3.53033C2.17678 3.23744 2.17678 2.76256 2.46967 2.46967Z"
                                            fill="currentColor" stroke="currentColor" stroke-width="0.5" />
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1 text-sm text-error-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation"
                                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Confirm New Password
                            </label>
                            <div class="relative">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                    required autocomplete="new-password">
                                <button type="button"
                                    class="toggle-password absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-400"
                                    data-target="password_confirmation">
                                    <svg class="eye-icon fill-current" width="22" height="22" viewBox="0 0 22 22"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M11 4.5C6.5 4.5 2.73 7.61 1 12C2.73 16.39 6.5 19.5 11 19.5C15.5 19.5 19.27 16.39 21 12C19.27 7.61 15.5 4.5 11 4.5ZM11 17C8.24 17 6 14.76 6 12C6 9.24 8.24 7 11 7C13.76 7 16 9.24 16 12C16 14.76 13.76 17 11 17ZM11 9C9.34 9 8 10.34 8 12C8 13.66 9.34 15 11 15C12.66 15 14 13.66 14 12C14 10.34 12.66 9 11 9Z" />
                                    </svg>
                                    <svg class="eye-off-icon fill-current hidden" width="22" height="22"
                                        viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M11 4.5C6.5 4.5 2.73 7.61 1 12C2.73 16.39 6.5 19.5 11 19.5C15.5 19.5 19.27 16.39 21 12C19.27 7.61 15.5 4.5 11 4.5ZM11 17C8.24 17 6 14.76 6 12C6 9.24 8.24 7 11 7C13.76 7 16 9.24 16 12C16 14.76 13.76 17 11 17ZM11 9C9.34 9 8 10.34 8 12C8 13.66 9.34 15 11 15C12.66 15 14 13.66 14 12C14 10.34 12.66 9 11 9Z" />
                                        <path
                                            d="M2.46967 2.46967C2.76256 2.17678 3.23744 2.17678 3.53033 2.46967L19.5303 18.4697C19.8232 18.7626 19.8232 19.2374 19.5303 19.5303C19.2374 19.8232 18.7626 19.8232 18.4697 19.5303L2.46967 3.53033C2.17678 3.23744 2.17678 2.76256 2.46967 2.46967Z"
                                            fill="currentColor" stroke="currentColor" stroke-width="0.5" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit"
                                class="bg-brand-500 hover:bg-brand-600 inline-flex items-center justify-center rounded-lg px-6 py-2.5 text-sm font-medium text-white">
                                Update Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password visibility toggle
            const toggleButtons = document.querySelectorAll('.toggle-password');
            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const inputField = document.getElementById(targetId);
                    const eyeIcon = this.querySelector('.eye-icon');
                    const eyeOffIcon = this.querySelector('.eye-off-icon');

                    if (inputField.type === 'password') {
                        inputField.type = 'text';
                        eyeIcon.classList.add('hidden');
                        eyeOffIcon.classList.remove('hidden');
                    } else {
                        inputField.type = 'password';
                        eyeIcon.classList.remove('hidden');
                        eyeOffIcon.classList.add('hidden');
                    }
                });
            });

            // Password validation
            const form = document.getElementById('change-password-form');
            form.addEventListener('submit', function(e) {
                const newPassword = document.getElementById('password').value;
                const confirmPassword = document.getElementById('password_confirmation').value;

                if (newPassword !== confirmPassword) {
                    e.preventDefault();

                    if (window.Swal) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Password Mismatch',
                            text: 'New password and confirmation do not match'
                        });
                    } else {
                        alert('New password and confirmation do not match');
                    }
                }

                // Basic password strength validation
                if (newPassword.length < 8) {
                    e.preventDefault();

                    if (window.Swal) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Password Too Short',
                            text: 'Password must be at least 8 characters long'
                        });
                    } else {
                        alert('Password must be at least 8 characters long');
                    }
                }
            });
        });
    </script>
@endpush
