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
                        <div
                            class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-2xl md:max-w-3xl lg:max-w-4xl max-h-[90vh] overflow-y-auto z-[1000000]">
                            <div
                                class="flex justify-between px-5 py-3 sm:px-6 sm:py-4 sticky top-0 bg-white dark:bg-gray-800 z-10 border-b border-gray-100 dark:border-gray-700">
                                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Add New User</h3>
                                <button @click="open = false" class="text-gray-400 hover:text-gray-500">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <form action="{{ route('admin.users.store') }}" method="POST">
                                @csrf
                                <div class="p-5 sm:p-6">
                                    <div class="flex">
                                        <!-- Left Column -->
                                        <div class="w-full md:w1/2 px-3 mb-4">
                                            <!-- Full Name -->
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-400">Full
                                                    Name <span class="text-error-500">*</span></label>
                                                <input type="text" name="full_name" value="{{ old('full_name') }}"
                                                    required placeholder="Enter Full Name"
                                                    class="h-10 mt-2 mb-2 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                                                @error('full_name')
                                                    <span class="text-sm text-error-500">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <!-- Email -->
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-400">Email
                                                    <span class="text-error-500">*</span></label>
                                                <input type="email" name="email" value="{{ old('email') }}" required
                                                    placeholder="user@example.com"
                                                    class="h-10 mt-2 mb-2 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                                                @error('email')
                                                    <span class="text-sm text-error-500">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <!-- Password -->
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-400">Password
                                                    <span class="text-error-500">*</span></label>
                                                <input type="password" name="password" required placeholder="Enter Password"
                                                    class="h-10 mt-2 mb-2 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                                                @error('password')
                                                    <span class="text-sm text-error-500">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <!-- Role -->
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-400">Role</label>
                                                <select name="role"
                                                    class="h-10 mt-2 mb-2 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                                    <option value="non_member" selected>Non Member</option>
                                                    <option value="member">Member</option>
                                                    <option value="admin">Admin</option>
                                                </select>
                                                @error('role')
                                                    <span class="text-sm text-error-500">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Right Column -->
                                        <div class="w-full md:w1/2 px-3 mb-4">
                                            <!-- Username -->
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-400">Username
                                                    <span class="text-error-500">*</span></label>
                                                <input type="text" name="username" value="{{ old('username') }}" required
                                                    placeholder="Enter Username"
                                                    class="h-10 mt-2 mb-2 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                                                @error('username')
                                                    <span class="text-sm text-error-500">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <!-- Phone -->
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-400">Phone
                                                    <span class="text-error-500">*</span></label>
                                                <div class="flex">
                                                    <span
                                                        class="inline-flex items-center px-3 h-10 mt-2 text-sm border border-r-0 border-gray-300 bg-gray-50 text-gray-500 rounded-l-lg dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600">+62</span>
                                                    <input name="phone_number" value="{{ old('phone_number', '') }}"
                                                        type="tel" required placeholder="8123456789"
                                                        class="h-10 mt-2 mb-2 w-full rounded-r-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                                                </div>
                                                @error('phone_number')
                                                    <span class="text-sm text-error-500">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <!-- Address -->
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-400">Address</label>
                                                <textarea name="address" rows="3" placeholder="Enter Address"
                                                    class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">{{ old('address') }}</textarea>
                                                @error('address')
                                                    <span class="text-sm text-error-500">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <!-- Verification Status -->
                                            <div class="flex items-center mt-3">
                                                <input type="checkbox" id="verified" name="verified" value="1"
                                                    class="h-4 w-4 text-brand-500 focus:ring-brand-500/10 border-gray-300 rounded">
                                                <label for="verified"
                                                    class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-400">Mark
                                                    email as verified</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex justify-end border-t border-gray-100 dark:border-gray-700 pt-4 mt-6">
                                        <button type="button" @click="open = false"
                                            class="mr-3 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-lg dark:bg-gray-700 dark:text-white/80 dark:hover:bg-gray-600">Cancel</button>
                                        <button type="submit"
                                            class="px-4 py-2 bg-brand-500 hover:bg-brand-600 text-white text-sm font-medium rounded-lg">Create
                                            User</button>
                                    </div>
                                </div>
                            </form>
                        </div>
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
                                            <!-- Update the action buttons in the table -->
                                            <td class="px-5 py-4 sm:px-6">
                                                <div class="flex items-center gap-2">
                                                    <!-- View Button -->
                                                    <a href="javascript:void(0)"
                                                        onclick="Alpine.store('userManagement').viewUser('{{ $user->id }}')"
                                                        class="cursor-pointer inline-flex items-center justify-center rounded-full bg-info-500 p-2 text-white hover:bg-info-600 active:bg-info-700 focus:ring-2 focus:ring-info-300 transition-colors"
                                                        title="View Details">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="h-4 w-4 fill-current" viewBox="0 0 24 24"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </a>

                                                    <!-- Edit Button -->
                                                    <a href="javascript:void(0)"
                                                        onclick="Alpine.store('userManagement').editUser('{{ $user->id }}')"
                                                        class="cursor-pointer inline-flex items-center justify-center rounded-full bg-warning-500 p-2 text-white hover:bg-warning-600 transition-colors"
                                                        title="Edit User">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="h-4 w-4 fill-current" viewBox="0 0 24 24"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>

                                                    <!-- Delete Button with SweetAlert confirmation -->
                                                    <button
                                                        onclick="Alpine.store('userManagement').confirmDelete('{{ $user->id }}', '{{ $user->full_name }}')"
                                                        class="cursor-pointer inline-flex items-center justify-center rounded-full bg-error-500 p-2 text-white hover:bg-error-600 transition-colors"
                                                        title="Delete User">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>

                                                    <!-- Hidden delete form that will be submitted via JavaScript -->
                                                    <form id="delete-form-{{ $user->id }}"
                                                        action="{{ route('admin.users.destroy', $user->id) }}"
                                                        method="POST" style="display: none;">
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
        <div x-data="{ viewModal: false, viewUser: {} }" x-cloak>
            @include('dashboard.users.view-modal')
        </div>
        <div x-data="{ editModal: false, editUserId: null }" x-cloak>
            @include('dashboard.users.edit-modal')
        </div>
    </div>

@endsection
