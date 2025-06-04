@extends('layouts.admin.app')

@section('title', 'Home Dashboard - MY FUTSAL')

@section('page', 'dashboard')

@section('content')
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
        <div class="grid grid-cols-12 gap-4 md:gap-6">
            <div class="col-span-12 space-y-6">
                <!-- Metric Group One -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6">
                    <!-- Metric Item Start -->
                    <div
                        class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
                            <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M8.80443 5.60156C7.59109 5.60156 6.60749 6.58517 6.60749 7.79851C6.60749 9.01185 7.59109 9.99545 8.80443 9.99545C10.0178 9.99545 11.0014 9.01185 11.0014 7.79851C11.0014 6.58517 10.0178 5.60156 8.80443 5.60156ZM5.10749 7.79851C5.10749 5.75674 6.76267 4.10156 8.80443 4.10156C10.8462 4.10156 12.5014 5.75674 12.5014 7.79851C12.5014 9.84027 10.8462 11.4955 8.80443 11.4955C6.76267 11.4955 5.10749 9.84027 5.10749 7.79851ZM4.86252 15.3208C4.08769 16.0881 3.70377 17.0608 3.51705 17.8611C3.48384 18.0034 3.5211 18.1175 3.60712 18.2112C3.70161 18.3141 3.86659 18.3987 4.07591 18.3987H13.4249C13.6343 18.3987 13.7992 18.3141 13.8937 18.2112C13.9797 18.1175 14.017 18.0034 13.9838 17.8611C13.7971 17.0608 13.4132 16.0881 12.6383 15.3208C11.8821 14.572 10.6899 13.955 8.75042 13.955C6.81096 13.955 5.61877 14.572 4.86252 15.3208ZM3.8071 14.2549C4.87163 13.2009 6.45602 12.455 8.75042 12.455C11.0448 12.455 12.6292 13.2009 13.6937 14.2549C14.7397 15.2906 15.2207 16.5607 15.4446 17.5202C15.7658 18.8971 14.6071 19.8987 13.4249 19.8987H4.07591C2.89369 19.8987 1.73504 18.8971 2.05628 17.5202C2.28015 16.5607 2.76117 15.2906 3.8071 14.2549ZM15.3042 11.4955C14.4702 11.4955 13.7006 11.2193 13.0821 10.7533C13.3742 10.3314 13.6054 9.86419 13.7632 9.36432C14.1597 9.75463 14.7039 9.99545 15.3042 9.99545C16.5176 9.99545 17.5012 9.01185 17.5012 7.79851C17.5012 6.58517 16.5176 5.60156 15.3042 5.60156C14.7039 5.60156 14.1597 5.84239 13.7632 6.23271C13.6054 5.73284 13.3741 5.26561 13.082 4.84371C13.7006 4.37777 14.4702 4.10156 15.3042 4.10156C17.346 4.10156 19.0012 5.75674 19.0012 7.79851C19.0012 9.84027 17.346 11.4955 15.3042 11.4955ZM19.9248 19.8987H16.3901C16.7014 19.4736 16.9159 18.969 16.9827 18.3987H19.9248C20.1341 18.3987 20.2991 18.3141 20.3936 18.2112C20.4796 18.1175 20.5169 18.0034 20.4837 17.861C20.2969 17.0607 19.913 16.088 19.1382 15.3208C18.4047 14.5945 17.261 13.9921 15.4231 13.9566C15.2232 13.6945 14.9995 13.437 14.7491 13.1891C14.5144 12.9566 14.262 12.7384 13.9916 12.5362C14.3853 12.4831 14.8044 12.4549 15.2503 12.4549C17.5447 12.4549 19.1291 13.2008 20.1936 14.2549C21.2395 15.2906 21.7206 16.5607 21.9444 17.5202C22.2657 18.8971 21.107 19.8987 19.9248 19.8987Z"
                                    fill="" />
                            </svg>
                        </div>

                        <div class="mt-5 flex items-end justify-between">
                            <div>
                                <span class="text-sm text-gray-500 dark:text-gray-400">Total User</span>
                                <h4 class="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90">
                                    {{ $formattedTotalNumber }}
                                </h4>
                            </div>
                        </div>
                    </div>
                    <!-- Metric Item End -->

                    <!-- Metric Item Start -->
                    <div
                        class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
                            <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M11.665 3.75621C11.8762 3.65064 12.1247 3.65064 12.3358 3.75621L18.7807 6.97856L12.3358 10.2009C12.1247 10.3065 11.8762 10.3065 11.665 10.2009L5.22014 6.97856L11.665 3.75621ZM4.29297 8.19203V16.0946C4.29297 16.3787 4.45347 16.6384 4.70757 16.7654L11.25 20.0366V11.6513C11.1631 11.6205 11.0777 11.5843 10.9942 11.5426L4.29297 8.19203ZM12.75 20.037L19.2933 16.7654C19.5474 16.6384 19.7079 16.3787 19.7079 16.0946V8.19202L13.0066 11.5426C12.9229 11.5844 12.8372 11.6208 12.75 11.6516V20.037ZM13.0066 2.41456C12.3732 2.09786 11.6277 2.09786 10.9942 2.41456L4.03676 5.89319C3.27449 6.27432 2.79297 7.05342 2.79297 7.90566V16.0946C2.79297 16.9469 3.27448 17.726 4.03676 18.1071L10.9942 21.5857L11.3296 20.9149L10.9942 21.5857C11.6277 21.9024 12.3732 21.9024 13.0066 21.5857L19.9641 18.1071C20.7264 17.726 21.2079 16.9469 21.2079 16.0946V7.90566C21.2079 7.05342 20.7264 6.27432 19.9641 5.89319L13.0066 2.41456Z"
                                    fill="" />
                            </svg>
                        </div>

                        <div class="mt-5 flex items-end justify-between">
                            <div>
                                <span
                                    class="text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->role == 'admin' ? 'Total Transaksi' : 'Total Transaksi Saya' }}</span>
                                <h4 class="mt-2 text-title-sm font-bold text-gray-800 dark:text-white/90">
                                    @if (Auth::user()->role == 'admin')
                                        {{ $allTotalTransactions }}
                                    @else
                                        {{ $totalTransactions }}
                                    @endif
                                </h4>
                            </div>

                            @if (Auth::user()->role === 'admin')
                                <span
                                    class="flex items-center gap-1 rounded-full {{ $transactionDirection === 'up' ? 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500' : 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500' }} py-0.5 pl-2 pr-2.5 text-sm font-medium">
                                    @if ($transactionDirection === 'up')
                                        <svg class="fill-current" width="12" height="12" viewBox="0 0 12 12"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M5.56462 1.62393C5.70193 1.47072 5.90135 1.37432 6.12329 1.37432C6.1236 1.37432 6.12391 1.37432 6.12422 1.37432C6.31631 1.37415 6.50845 1.44731 6.65505 1.59381L9.65514 4.5918C9.94814 4.88459 9.94831 5.35947 9.65552 5.65246C9.36273 5.94546 8.88785 5.94562 8.59486 5.65283L6.87329 3.93247L6.87329 10.125C6.87329 10.5392 6.53751 10.875 6.12329 10.875C5.70908 10.875 5.37329 10.5392 5.37329 10.125L5.37329 3.93578L3.65516 5.65282C3.36218 5.94562 2.8873 5.94547 2.5945 5.65248C2.3017 5.35949 2.30185 4.88462 2.59484 4.59182L5.56462 1.62393Z"
                                                fill="" />
                                        </svg>
                                    @else
                                        <svg class="fill-current" width="12" height="12" viewBox="0 0 12 12"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M5.31462 10.3761C5.45194 10.5293 5.65136 10.6257 5.87329 10.6257C5.8736 10.6257 5.8739 10.6257 5.87421 10.6257C6.0663 10.6259 6.25845 10.5527 6.40505 10.4062L9.40514 7.4082C9.69814 7.11541 9.69831 6.64054 9.40552 6.34754C9.11273 6.05454 8.63785 6.05438 8.34486 6.34717L6.62329 8.06753L6.62329 1.875C6.62329 1.46079 6.28751 1.125 5.87329 1.125C5.45908 1.125 5.12329 1.46079 5.12329 1.875L5.12329 8.06422L3.40516 6.34719C3.11218 6.05439 2.6373 6.05454 2.3445 6.34752C2.0517 6.64051 2.05185 7.11538 2.34484 7.40818L5.31462 10.3761Z"
                                                fill="" />
                                        </svg>
                                    @endif

                                    {{ $transactionPercentage }}%
                                </span>
                            @endif
                        </div>
                    </div>
                    <!-- Metric Item End -->
                </div>
                <!-- Metric Group One -->
            </div>

            <div class="col-span-12">
                <!-- ====== Table One Start -->
                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-4 pb-3 pt-4 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6"
                    x-data="transactionSearch()" x-init="init()">
                    <div class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                                History Transaksi
                            </h3>
                        </div>
                        <!-- Search Input -->
                        <div class="relative">
                            <input type="text" x-model="searchQuery" @input.debounce.300ms="searchTransactions()"
                                placeholder="Cari ID, nama, email, status, atau tanggal..."
                                class="block w-full pl-3 pr-10 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:border-gray-700 dark:bg-gray-800 dark:text-white text-sm">
                            <!-- Search Icon -->
                            <div x-show="!loading"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <!-- Loading indicator -->
                            <div x-show="loading" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg class="animate-spin h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="w-full overflow-x-auto">
                        <table class="min-w-full">
                            <!-- table header start -->
                            <thead>
                                <tr class="border-gray-100 border-y dark:border-gray-800">
                                    <th class="py-3">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                ID
                                            </p>
                                        </div>
                                    </th>
                                    <th class="py-3">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                User
                                            </p>
                                        </div>
                                    </th>
                                    <th class="py-3">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                Amount
                                            </p>
                                        </div>
                                    </th>
                                    <th class="py-3">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                Date
                                            </p>
                                        </div>
                                    </th>
                                    <th class="py-3">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                Status
                                            </p>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <!-- table header end -->

                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800" x-ref="tableBody">
                                <template x-for="transaction in transactions" :key="transaction.id">
                                    <tr>
                                        <td class="py-3">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90"
                                                    x-text="'#' + transaction.id">
                                                </p>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <div class="flex items-center">
                                                <div class="flex items-center gap-3">
                                                    <div>
                                                        <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90"
                                                            x-text="transaction.user.full_name">
                                                        </p>
                                                        <span class="text-gray-500 text-theme-xs dark:text-gray-400"
                                                            x-text="transaction.user.email">
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <div class="flex items-center">
                                                <p class="text-gray-500 text-theme-sm dark:text-gray-400"
                                                    x-text="formatCurrency(transaction.amount)">
                                                </p>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <div class="flex items-center">
                                                <p class="text-gray-500 text-theme-sm dark:text-gray-400"
                                                    x-text="formatDate(transaction.created_at)">
                                                </p>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <div class="flex items-center">
                                                <p class="rounded-full px-2 py-0.5 text-theme-xs font-medium"
                                                    :class="getStatusClass(transaction.status)"
                                                    x-text="formatStatus(transaction.status)">
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                </template>

                                <!-- No results found -->
                                <tr x-show="transactions.length === 0 && !loading">
                                    <td colspan="5" class="py-6 text-center text-gray-500 dark:text-gray-400">
                                        <span x-show="searchQuery">Tidak ada transaksi yang ditemukan untuk pencarian
                                            "<span x-text="searchQuery"></span>"</span>
                                        <span x-show="!searchQuery">Tidak ada transaksi yang ditemukan</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Loading overlay -->
                        <div x-show="loading" class="flex justify-center items-center py-8">
                            <div class="flex items-center space-x-2">
                                <svg class="animate-spin h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <span class="text-gray-500 text-sm">Mencari...</span>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div x-show="pagination.last_page > 1" class="mt-4 flex items-center justify-between">
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Menampilkan <span x-text="pagination.from"></span> sampai <span x-text="pagination.to"></span>
                            dari <span x-text="pagination.total"></span> hasil
                        </div>
                        <div class="flex space-x-1">
                            <button @click="goToPage(pagination.current_page - 1)" :disabled="pagination.current_page <= 1"
                                :class="pagination.current_page <= 1 ? 'opacity-50 cursor-not-allowed' :
                                    'hover:bg-gray-100 dark:hover:bg-gray-700'"
                                class="px-3 py-1 text-sm border rounded dark:border-gray-600 dark:text-gray-300">
                                Previous
                            </button>

                            <template x-for="page in visiblePages" :key="page">
                                <button @click="goToPage(page)"
                                    :class="page === pagination.current_page ? 'bg-blue-500 text-white' :
                                        'hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-gray-300'"
                                    class="px-3 py-1 text-sm border rounded dark:border-gray-600" x-text="page">
                                </button>
                            </template>

                            <button @click="goToPage(pagination.current_page + 1)"
                                :disabled="pagination.current_page >= pagination.last_page"
                                :class="pagination.current_page >= pagination.last_page ? 'opacity-50 cursor-not-allowed' :
                                    'hover:bg-gray-100 dark:hover:bg-gray-700'"
                                class="px-3 py-1 text-sm border rounded dark:border-gray-600 dark:text-gray-300">
                                Next
                            </button>
                        </div>
                    </div>
                </div>
                <!-- ====== Table One End -->
            </div>
        </div>
    </div>

    <script>
        function transactionSearch() {
            return {
                searchQuery: '',
                transactions: [],
                loading: false,
                pagination: {
                    current_page: 1,
                    last_page: 1,
                    per_page: 10,
                    total: 0,
                    from: 0,
                    to: 0
                },

                init() {
                    // Initialize with server-side data
                    @if (Auth::user()->role === 'admin')
                        this.transactions = @json($allHistoryTransactions->items());
                        this.pagination = {
                            current_page: {{ $allHistoryTransactions->currentPage() }},
                            last_page: {{ $allHistoryTransactions->lastPage() }},
                            per_page: {{ $allHistoryTransactions->perPage() }},
                            total: {{ $allHistoryTransactions->total() }},
                            from: {{ $allHistoryTransactions->firstItem() ?? 0 }},
                            to: {{ $allHistoryTransactions->lastItem() ?? 0 }}
                        };
                    @else
                        this.transactions = @json($historyTransactions->items());
                        this.pagination = {
                            current_page: {{ $historyTransactions->currentPage() }},
                            last_page: {{ $historyTransactions->lastPage() }},
                            per_page: {{ $historyTransactions->perPage() }},
                            total: {{ $historyTransactions->total() }},
                            from: {{ $historyTransactions->firstItem() ?? 0 }},
                            to: {{ $historyTransactions->lastItem() ?? 0 }}
                        };
                    @endif
                },

                async searchTransactions(page = 1) {
                    this.loading = true;

                    try {
                        const response = await fetch(
                            `{{ route('dashboard.search-transactions') }}?search=${encodeURIComponent(this.searchQuery)}&page=${page}`, {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            });

                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }

                        const data = await response.json();
                        this.transactions = data.data;
                        this.pagination = data.pagination;

                    } catch (error) {
                        console.error('Error searching transactions:', error);
                        // Handle error - maybe show a toast notification
                    } finally {
                        this.loading = false;
                    }
                },

                goToPage(page) {
                    if (page >= 1 && page <= this.pagination.last_page) {
                        this.searchTransactions(page);
                    }
                },

                get visiblePages() {
                    const current = this.pagination.current_page;
                    const last = this.pagination.last_page;
                    const pages = [];

                    if (last <= 7) {
                        for (let i = 1; i <= last; i++) {
                            pages.push(i);
                        }
                    } else {
                        if (current <= 3) {
                            for (let i = 1; i <= 5; i++) {
                                pages.push(i);
                            }
                            pages.push('...', last);
                        } else if (current >= last - 2) {
                            pages.push(1, '...');
                            for (let i = last - 4; i <= last; i++) {
                                pages.push(i);
                            }
                        } else {
                            pages.push(1, '...');
                            for (let i = current - 1; i <= current + 1; i++) {
                                pages.push(i);
                            }
                            pages.push('...', last);
                        }
                    }

                    return pages.filter(page => page !== '...');
                },

                formatCurrency(amount) {
                    return 'Rp' + new Intl.NumberFormat('id-ID').format(amount);
                },

                formatDate(dateString) {
                    const date = new Date(dateString);
                    return date.toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    });
                },

                formatStatus(status) {
                    return status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
                },

                getStatusClass(status) {
                    const classes = {
                        'paid': 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
                        'pending_verification': 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400',
                        'unpaid': 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-500',
                        'failed': 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
                        'cancelled': 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500'
                    };

                    return classes[status] || 'bg-gray-50 text-gray-600 dark:bg-gray-500/15 dark:text-gray-500';
                }
            }
        }
    </script>
@endsection
