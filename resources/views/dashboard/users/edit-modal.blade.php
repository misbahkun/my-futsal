    <!-- Modal -->
    <div x-show="editModal" class="fixed inset-0 flex items-center justify-center z-[999999]">
        <div @click="editModal = false" class="fixed inset-0 bg-black opacity-50 z-[999998]"></div>
        <div
            class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-2xl md:max-w-3xl lg:max-w-4xl max-h-[90vh] overflow-y-auto z-[1000000]">
            <div
                class="flex justify-between px-5 py-3 sm:px-6 sm:py-4 sticky top-0 bg-white dark:bg-gray-800 z-10 border-b border-gray-100 dark:border-gray-700">
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Edit User</h3>
                <button @click="editModal = false" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form :action="'{{ route('admin.users.update', '') }}/' + editUserId" method="POST">
                @csrf
                @method('PUT')
                <div class="p-5 sm:p-6">
                    <div class="flex flex-wrap -mx-3">
                        <!-- Left Column -->
                        <div class="w-full md:w-1/2 px-3 mb-4">
                            <!-- Full Name -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Full Name <span class="text-error-500">*</span>
                                </label>
                                <input type="text" name="full_name" id="edit_full_name" required
                                    class="h-10 mt-2 mb-2 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                                @error('full_name')
                                    <span class="text-sm text-error-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Email <span class="text-error-500">*</span>
                                </label>
                                <input type="email" name="email" id="edit_email" required
                                    class="h-10 mt-2 mb-2 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                                @error('email')
                                    <span class="text-sm text-error-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Password (Optional on edit) -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Password <span class="text-gray-500 text-xs">(Leave blank to keep current)</span>
                                </label>
                                <input type="password" name="password" placeholder="Enter new password"
                                    class="h-10 mt-2 mb-2 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                                @error('password')
                                    <span class="text-sm text-error-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Role -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Role
                                </label>
                                <select name="role" id="edit_role"
                                    class="h-10 mt-2 mb-2 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                    <option value="non_member">Non Member</option>
                                    <option value="member">Member</option>
                                    <option value="admin">Admin</option>
                                </select>
                                @error('role')
                                    <span class="text-sm text-error-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="w-full md:w-1/2 px-3">
                            <!-- Username -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Username <span class="text-error-500">*</span>
                                </label>
                                <input type="text" name="username" id="edit_username" required
                                    class="h-10 mt-2 mb-2 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                                @error('username')
                                    <span class="text-sm text-error-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Phone <span class="text-error-500">*</span>
                                </label>
                                <div class="flex">
                                    <span
                                        class="inline-flex items-center px-3 h-10 mt-2 text-sm border border-r-0 border-gray-300 bg-gray-50 text-gray-500 rounded-l-lg dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600">+62</span>
                                    <input name="phone_number" id="edit_phone_number" required
                                        class="h-10 mt-2 mb-2 w-full rounded-r-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                                </div>
                                @error('phone_number')
                                    <span class="text-sm text-error-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Address
                                </label>
                                <textarea name="address" id="edit_address" rows="3"
                                    class="w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"></textarea>
                                @error('address')
                                    <span class="text-sm text-error-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Verification Status -->
                            <div class="flex items-center mt-3">
                                <input type="checkbox" id="edit_verified" name="verified" value="1"
                                    class="h-4 w-4 text-brand-500 focus:ring-brand-500/10 border-gray-300 rounded">
                                <label for="edit_verified"
                                    class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Mark email as verified
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end border-t border-gray-100 dark:border-gray-700 pt-4 mt-6">
                        <button type="button" @click="editModal = false"
                            class="mr-3 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-lg dark:bg-gray-700 dark:text-white/80 dark:hover:bg-gray-600">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-brand-500 hover:bg-brand-600 text-white text-sm font-medium rounded-lg">
                            Update User
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
