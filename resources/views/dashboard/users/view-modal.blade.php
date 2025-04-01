    <!-- Modal -->
    <div x-show="viewModal" class="fixed inset-0 flex items-center justify-center z-[999999]">
        <div @click="viewModal = false" class="fixed inset-0 bg-black opacity-50 z-[999998]"></div>
        <div
            class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg md:max-w-xl w-full max-h-[90vh] overflow-y-auto z-[1000000]">
            <div
                class="flex justify-between px-5 py-3 sm:px-6 sm:py-4 sticky top-0 bg-white dark:bg-gray-800 z-10 border-b border-gray-100 dark:border-gray-700">
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">User Details</h3>
                <button @click="viewModal = false" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-5 sm:p-6">
                <div class="space-y-4">
                    <!-- User Avatar and Name -->
                    <div class="flex items-center gap-4 mb-6">
                        <div class="h-16 w-16 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-2xl text-gray-600 dark:text-white/80"
                            x-text="viewUser.full_name?.charAt(0).toUpperCase() || 'U'"></div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90"
                                x-text="viewUser.full_name || 'User Name'"></h3>
                            <span class="px-2 py-1 rounded-full text-xs font-medium capitalize"
                                :class="{
                                    'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400': viewUser
                                        .role === 'admin',
                                    'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400': viewUser
                                        .role === 'member',
                                    'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400': viewUser
                                        .role === 'non_member'
                                }"
                                x-text="viewUser.role || 'non_member'"></span>
                        </div>
                    </div>

                    <!-- User Info -->
                    <div class="grid grid-cols-1 gap-4">
                        <div class="border-b border-gray-100 dark:border-gray-800 pb-3">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Username</h4>
                            <p class="text-gray-800 dark:text-white/90" x-text="viewUser.username || '-'"></p>
                        </div>

                        <div class="border-b border-gray-100 dark:border-gray-800 pb-3">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Email</h4>
                            <p class="text-gray-800 dark:text-white/90" x-text="viewUser.email || '-'"></p>
                        </div>

                        <div class="border-b border-gray-100 dark:border-gray-800 pb-3">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Phone Number</h4>
                            <p class="text-gray-800 dark:text-white/90" x-text="viewUser.phone_number || '-'"></p>
                        </div>

                        <div class="border-b border-gray-100 dark:border-gray-800 pb-3">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Address</h4>
                            <p class="text-gray-800 dark:text-white/90" x-text="viewUser.address || '-'"></p>
                        </div>

                        <div class="border-b border-gray-100 dark:border-gray-800 pb-3">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Email Verification
                            </h4>
                            <p class="inline-flex items-center">
                                <span class="inline-block h-2.5 w-2.5 rounded-full mr-2"
                                    :class="viewUser.email_verified_at ? 'bg-success-500' : 'bg-error-500'"></span>
                                <span class="text-gray-800 dark:text-white/90"
                                    x-text="viewUser.email_verified_at ? 'Verified' : 'Not Verified'"></span>
                            </p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Joined</h4>
                            <p class="text-gray-800 dark:text-white/90" x-text="viewUser.created_at || '-'"></p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end mt-6">
                    <button @click="viewModal = false"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-lg dark:bg-gray-700 dark:text-white/80 dark:hover:bg-gray-600">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
