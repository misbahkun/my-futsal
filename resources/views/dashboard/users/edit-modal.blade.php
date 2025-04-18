{{-- modal edit --}}
<div id="edit-modal" class="fixed inset-0 z-50 hidden items-center justify-center">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>

    <div
        class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-2xl md:max-w-3xl lg:max-w-4xl max-h-[90vh] overflow-y-auto z-[1000000]">
        <div
            class="flex justify-between px-5 py-3 sm:px-6 sm:py-4 sticky top-0 bg-white dark:bg-gray-800 z-10 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Edit User</h3>
            <button class="close-modal text-gray-400 hover:text-gray-500">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="edit-user-form" action="" method="POST">
            @csrf
            @method('PUT')
            <div class="p-5 sm:p-6">
                <div class="flex">
                    <!-- Left Column -->
                    <div class="w-full md:w-1/2 px-3 mb-4">
                        <!-- Full Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Full
                                Name <span class="text-error-500">*</span></label>
                            <input type="text" id="edit_full_name" name="full_name" required
                                placeholder="Enter Full Name"
                                class="h-10 mt-2 mb-2 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                            <span class="text-sm text-error-500 edit-full-name-error"></span>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Email
                                <span class="text-error-500">*</span></label>
                            <input type="email" id="edit_email" name="email" required placeholder="user@example.com"
                                class="h-10 mt-2 mb-2 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                            <span class="text-sm text-error-500 edit-email-error"></span>
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Update Password
                            <input type="password" name="new_password" required placeholder="Enter New Password"
                                class="h-10 mt-2 mb-2 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                            @error('new_password')
                                <span class="text-sm text-error-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Role -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Role</label>
                            <select id="edit_role" name="role"
                                class="h-10 mt-2 mb-2 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                <option value="non_member">Non Member</option>
                                <option value="member">Member</option>
                                <option value="admin">Admin</option>
                            </select>
                            <span class="text-sm text-error-500 edit-role-error"></span>

                            @if (auth()->user()->role !== 'admin')
                                <input type="hidden" name="role" id="edit_role_hidden">
                            @endif
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="w-full md:w-1/2 px-3 mb-4">
                        <!-- Username -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Username
                                <span class="text-error-500">*</span></label>
                            <input type="text" id="edit_username" name="username" required
                                placeholder="Enter Username"
                                class="h-10 mt-2 mb-2 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                            <span class="text-sm text-error-500 edit-username-error"></span>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Phone
                                <span class="text-error-500">*</span></label>
                            <div class="flex">
                                <span
                                    class="inline-flex items-center px-3 h-10 mt-2 text-sm border border-r-0 border-gray-300 bg-gray-50 text-gray-500 rounded-l-lg dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600">+62</span>
                                <input id="edit_phone_number" name="phone_number" type="tel" required
                                    placeholder="8123456789"
                                    class="h-10 mt-2 mb-2 w-full rounded-r-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                            </div>
                            <span class="text-sm text-error-500 edit-phone-number-error"></span>
                        </div>

                        <!-- Address -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Address</label>
                            <textarea id="edit_address" name="address" rows="3" placeholder="Enter Address"
                                class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></textarea>
                            <span class="text-sm text-error-500 edit-address-error"></span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end border-t border-gray-100 dark:border-gray-700 pt-4 mt-6">
                    <button type="button"
                        class="close-modal mr-3 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-lg dark:bg-gray-700 dark:text-white/80 dark:hover:bg-gray-600">Cancel</button>
                    <button type="button" id="submit-edit-form"
                        class="px-4 py-2 bg-brand-500 hover:bg-brand-600 text-white text-sm font-medium rounded-lg">Save
                        Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
