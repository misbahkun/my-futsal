@extends('layouts.admin.app')

@section('title', 'Transactions')
@section('page', 'transactions')

@section('content')
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
        <!-- Breadcrumb Start -->
        <div x-data="{ pageName: 'Transactions' }">
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
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <!-- Filter Button -->
                    <button type="button" onclick="openFilterModal()"
                        class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-white font-medium py-2.5 px-4 rounded-lg transition-colors duration-200 inline-flex items-center shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M10 18h4v-2h-4v2zM3 6v2h18V6H3zm3 7h12v-2H6v2z" />
                        </svg>
                        Filter Data
                        @if (request()->hasAny(['filter_type', 'payment_status', 'amount_range']))
                            <span class="ml-2 bg-brand-500 text-white text-xs px-2 py-0.5 rounded-full">
                                {{ collect([request('filter_type'), request('payment_status'), request('amount_range')])->filter()->count() }}
                            </span>
                        @endif
                    </button>

                    <!-- Reset Filter -->
                    @if (request()->hasAny(['filter_type', 'payment_status', 'amount_range', 'start_date', 'end_date']))
                        <a href="{{ route('admin.transactions') }}"
                            class="bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-600 dark:text-white font-medium py-2.5 px-4 rounded-lg transition-colors duration-200 inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.65,6.35C16.2,4.9 14.21,4 12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20C15.73,20 18.84,17.45 19.73,14H17.65C16.83,16.33 14.61,18 12,18A6,6 0 0,1 6,12A6,6 0 0,1 12,6C13.66,6 15.14,6.69 16.22,7.78L13,11H20V4L17.65,6.35Z" />
                            </svg>
                            Reset
                        </a>
                    @endif
                </div>
            </div>

            <!-- Active Filters Display -->
            @if (request()->hasAny(['filter_type', 'payment_status', 'amount_range', 'start_date', 'end_date']))
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-600 dark:text-white mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M11,16.5L18,9.5L16.59,8.09L11,13.67L7.41,10.09L6,11.5L11,16.5Z" />
                            </svg>
                            <span class="text-sm font-medium text-blue-800 dark:text-white">Filter Aktif:</span>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @if (request('filter_type') && request('filter_type') !== 'custom')
                                <span
                                    class="bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-white px-2 py-1 rounded text-xs font-medium">
                                    {{ ucfirst(request('filter_type')) }}
                                </span>
                            @endif
                            @if (request('payment_status') && request('payment_status') !== 'all')
                                <span
                                    class="bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-white px-2 py-1 rounded text-xs font-medium">
                                    {{ ucwords(str_replace('_', ' ', request('payment_status'))) }}
                                </span>
                            @endif
                            @if (request('amount_range'))
                                <span
                                    class="bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-white px-2 py-1 rounded text-xs font-medium">
                                    Rp {{ str_replace('-', ' - ', request('amount_range')) }}
                                </span>
                            @endif
                            @if (request('start_date'))
                                <span
                                    class="bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-white px-2 py-1 rounded text-xs font-medium">
                                    Dari: {{ date('d/m/Y', strtotime(request('start_date'))) }}
                                </span>
                            @endif
                            @if (request('end_date'))
                                <span
                                    class="bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-white px-2 py-1 rounded text-xs font-medium">
                                    Sampai: {{ date('d/m/Y', strtotime(request('end_date'))) }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]"
                x-data="transactionSearch()" x-init="init()">
                <div class="px-5 py-4 sm:px-6 sm:py-5 flex justify-between items-center">
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                        All Transactions
                    </h3>
                    <!-- Search Input -->
                    <div class="relative">
                        <input type="text" x-model="searchQuery" @input.debounce.300ms="searchTransactions()"
                            placeholder="Cari ID, nama, email, jumlah, status, atau tanggal..."
                            class="block w-full pl-3 pr-10 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:border-gray-700 dark:bg-gray-800 dark:text-white text-sm">
                        <!-- Search Icon -->
                        <div x-show="!loading"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <!-- Loading Indicator -->
                        <div x-show="loading" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <svg class="animate-spin h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4m2 5.291A7.962 7.962 0 0 0 12 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="p-5 border-t border-gray-100 dark:border-gray-800 sm:p-6">
                    <!-- Success Alert with Invoice Link -->
                    @if (session('invoice_link'))
                        <div class="mb-6 bg-success/10 py-4 px-6 rounded-lg border border-success/20">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-success font-medium">{{ session('success') }}</p>
                                    <p class="text-gray-600 mt-1">Transaction ID: {{ session('transaction_id') }}</p>
                                </div>
                                <a href="{{ session('invoice_link') }}" target="_blank"
                                    class="inline-flex items-center justify-center rounded-md bg-success py-2 px-4 text-center font-medium text-white hover:bg-opacity-90">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    View Invoice
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Table -->
                    <div class="max-w-full overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-gray-100 dark:border-gray-800">
                                    <th class="px-5 py-3 sm:px-6">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">ID</p>
                                        </div>
                                    </th>
                                    <th class="px-5 py-3 sm:px-6">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">User</p>
                                        </div>
                                    </th>
                                    <th class="px-5 py-3 sm:px-6">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Amount
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-5 py-3 sm:px-6">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Status
                                            </p>
                                        </div>
                                    </th>
                                    <th class="px-5 py-3 sm:px-6">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Payment
                                                Type</p>
                                        </div>
                                    </th>
                                    <th class="px-5 py-3 sm:px-6">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Date</p>
                                        </div>
                                    </th>
                                    <th class="px-5 py-3 sm:px-6">
                                        <div class="flex items-center">
                                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Action
                                            </p>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800" x-ref="tableBody">
                                <template x-for="transaction in transactions" :key="transaction.id">
                                    <tr>
                                        <td class="px-5 py-4 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="text-gray-800 text-theme-sm font-medium dark:text-white/90"
                                                    x-text="transaction.id"></p>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 sm:px-6">
                                            <div class="flex items-center">
                                                <div class="flex items-center gap-3">
                                                    <div>
                                                        <span
                                                            class="block font-medium text-gray-800 text-theme-sm dark:text-white/90"
                                                            x-text="transaction.user.full_name"></span>
                                                        <span class="block text-gray-500 text-theme-xs dark:text-gray-400"
                                                            x-text="transaction.user.email"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="text-gray-500 text-theme-sm dark:text-gray-400"
                                                    x-text="formatCurrency(transaction.amount)"></p>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 sm:px-6">
                                            <div class="flex items-center">
                                                <span class="inline-flex rounded-full py-1 px-3 text-sm font-medium"
                                                    :class="getStatusClass(transaction.status)"
                                                    x-text="formatStatus(transaction.status)">
                                                </span>
                                                <template x-if="transaction.status === 'paid'">
                                                    <a :href="'{{ route('invoice', ':id') }}'.replace(':id', transaction.id)"
                                                        target="_blank" class="ml-2 inline-flex items-center">
                                                        <svg width="16" height="16" viewBox="0 0 24 24"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg"
                                                            class="inline">
                                                            <path
                                                                d="M13.0002 3H7.00024C5.89567 3 5.00024 3.89543 5.00024 5V19C5.00024 20.1046 5.89567 21 7.00024 21H17.0002C18.1048 21 19.0002 20.1046 19.0002 19V9M13.0002 3L19.0002 9M13.0002 3V9H19.0002"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                    </a>
                                                </template>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="text-gray-500 text-theme-sm dark:text-gray-400"
                                                    x-text="formatStatus(transaction.payment_type)"></p>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="text-gray-500 text-theme-sm dark:text-gray-400"
                                                    x-text="formatDate(transaction.created_at)"></p>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 sm:px-6">
                                            <div class="flex items-center gap-2">
                                                <a :href="'{{ route('admin.transactions.show', ':id') }}'.replace(':id',
                                                    transaction.id)"
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
                                                <a :href="'{{ route('admin.transactions.edit', ':id') }}'.replace(':id',
                                                    transaction.id)"
                                                    class="cursor-pointer inline-flex items-center justify-center w-8 h-8 rounded-full bg-warning-500 hover:bg-warning-600 text-white"
                                                    title="Edit">
                                                    <svg width="16" height="16" fill="currentColor"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                        <path fill-rule="evenodd"
                                                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                                                    </svg>
                                                </a>
                                                <form
                                                    :action="'{{ route('admin.transactions.destroy', ':id') }}'.replace(':id',
                                                        transaction.id)"
                                                    method="POST" class="inline"
                                                    @submit.prevent="deleteTransaction(transaction.id)">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="cursor-pointer inline-flex items-center justify-center w-8 h-8 rounded-full bg-error-500 hover:bg-error-600 text-white"
                                                        title="Delete">
                                                        <svg width="16" height="16" fill="currentColor"
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                                            <path fill-rule="evenodd"
                                                                d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                <!-- No Results Found -->
                                <tr x-show="transactions.length === 0 && !loading">
                                    <td colspan="7" class="px-5 py-4 sm:px-6 text-center">
                                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                            <span x-show="searchQuery">Tidak ada transaksi yang ditemukan untuk pencarian
                                                "<span x-text="searchQuery"></span>"</span>
                                            <span x-show="!searchQuery">Tidak ada transaksi yang ditemukan</span>
                                        </p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- Loading Overlay -->
                        <div x-show="loading" class="flex justify-center items-center py-8">
                            <div class="flex items-center space-x-2">
                                <svg class="animate-spin h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4m2 5.291A7.962 7.962 0 0 0 12 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
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
            </div>
        </div>
    </div>


    <!-- Filter Modal -->
    <div id="filterModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-brand-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M10 18h4v-2h-4v2zM3 6v2h18V6H3zm3 7h12v-2H6v2z" />
                        </svg>
                        Filter Transactions
                    </h3>
                    <button onclick="closeFilterModal()"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
                        </svg>
                    </button>
                </div>
            </div>
            <!-- Modal Body -->
            <form method="GET" action="{{ route('admin.transactions') }}" class="p-6 space-y-6">
                <!-- Labels in 2-column grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Periode Waktu
                    </label>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Rentang Jumlah
                    </label>
                </div>
                <!-- Radio buttons and content -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Periode Waktu Radio Buttons -->
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="filter_type" value=""
                                {{ !request('filter_type') && !request('start_date') && !request('end_date') ? 'checked' : '' }}
                                class="text-brand-500 focus:ring-brand-500 mr-3">
                            <span class="text-gray-700 dark:text-gray-300">Semua Periode</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="filter_type" value="day"
                                {{ request('filter_type') == 'day' ? 'checked' : '' }}
                                class="text-brand-500 focus:ring-brand-500 mr-3">
                            <span class="text-gray-700 dark:text-gray-300">Hari Ini</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="filter_type" value="week"
                                {{ request('filter_type') == 'week' ? 'checked' : '' }}
                                class="text-brand-500 focus:ring-brand-500 mr-3">
                            <span class="text-gray-700 dark:text-gray-300">Minggu Ini</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="filter_type" value="month"
                                {{ request('filter_type') == 'month' ? 'checked' : '' }}
                                class="text-brand-500 focus:ring-brand-500 mr-3">
                            <span class="text-gray-700 dark:text-gray-300">Bulan Ini</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="filter_type" value="year"
                                {{ request('filter_type') == 'year' ? 'checked' : '' }}
                                class="text-brand-500 focus:ring-brand-500 mr-3">
                            <span class="text-gray-700 dark:text-gray-300">Tahun Ini</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="filter_type" value="custom"
                                {{ request('start_date') || request('end_date') ? 'checked' : '' }}
                                class="text-brand-500 focus:ring-brand-500 mr-3">
                            <span class="text-gray-700 dark:text-gray-300">Rentang Tanggal</span>
                        </label>
                    </div>
                    <!-- Rentang Jumlah Radio Buttons -->
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="amount_range" value=""
                                {{ !request('amount_range') ? 'checked' : '' }}
                                class="text-brand-500 focus:ring-brand-500 mr-3">
                            <span class="text-gray-700 dark:text-gray-300">Semua Jumlah</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="amount_range" value="0-100000"
                                {{ request('amount_range') == '0-100000' ? 'checked' : '' }}
                                class="text-brand-500 focus:ring-brand-500 mr-3">
                            <span class="text-gray-700 dark:text-gray-300">Rp 0 - Rp 100.000</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="amount_range" value="100000-500000"
                                {{ request('amount_range') == '100000-500000' ? 'checked' : '' }}
                                class="text-brand-500 focus:ring-brand-500 mr-3">
                            <span class="text-gray-700 dark:text-gray-300">Rp 100.000 - Rp 500.000</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="amount_range" value="500000-1000000"
                                {{ request('amount_range') == '500000-1000000' ? 'checked' : '' }}
                                class="text-brand-500 focus:ring-brand-500 mr-3">
                            <span class="text-gray-700 dark:text-gray-300">Rp 500.000 - Rp 1.000.000</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="amount_range" value="1000000-999999999"
                                {{ request('amount_range') == '1000000-999999999' ? 'checked' : '' }}
                                class="text-brand-500 focus:ring-brand-500 mr-3">
                            <span class="text-gray-700 dark:text-gray-300">> Rp 1.000.000</span>
                        </label>
                    </div>
                </div>
                <!-- Custom Date Range -->
                <div id="dateRangeSection" class="{{ !request('start_date') && !request('end_date') ? 'hidden' : '' }}">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        Pilih Tanggal
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Dari</label>
                            <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-brand-500 focus:border-brand-500">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Sampai</label>
                            <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-brand-500 focus:border-brand-500">
                        </div>
                    </div>
                </div>
                <!-- Modal Footer -->
                <div class="flex flex-col-reverse sm:flex-row gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" onclick="closeFilterModal()"
                        class="w-full sm:w-auto px-4 py-2.5 text-gray-700 dark:text-white bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg font-medium transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="w-full sm:w-auto px-6 py-2.5 bg-brand-500 hover:bg-brand-600 text-white rounded-lg font-medium transition-colors flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M15.5 14h-.79l-.28-.27a6.5 6.5 0 0 0 1.48-5.34c-.47-2.78-2.79-5-5.59-5.34a6.505 6.505 0 0 0-7.27 7.27c.34 2.8 2.56 5.12 5.34 5.59a6.5 6.5 0 0 0 5.34-1.48l.27.28v.79l4.25 4.25c.41.41 1.08.41 1.49 0 .41-.41.41-1.08 0-1.49L15.5 14zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" />
                        </svg>
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal functions
        function openFilterModal() {
            document.getElementById('filterModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeFilterModal() {
            document.getElementById('filterModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Close modal when clicking outside
        document.getElementById('filterModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeFilterModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeFilterModal();
            }
        });

        // Toggle date range section
        document.querySelectorAll('input[name="filter_type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const dateRangeSection = document.getElementById('dateRangeSection');
                if (this.value === 'custom') {
                    dateRangeSection.classList.remove('hidden');
                } else {
                    dateRangeSection.classList.add('hidden');
                    document.getElementById('start_date').value = '';
                    document.getElementById('end_date').value = '';
                    // Clear Flatpickr altInput values
                    const startPicker = document.getElementById('start_date')._flatpickr;
                    const endPicker = document.getElementById('end_date')._flatpickr;
                    if (startPicker) startPicker.clear();
                    if (endPicker) endPicker.clear();
                }
            });
        });

        // Initialize Flatpickr with Indonesian locale
        flatpickr('#start_date', {
            altInput: true,
            altFormat: 'd/m/Y', // Display dd/mm/yyyy
            dateFormat: 'Y-m-d', // Submit yyyy-mm-dd
            maxDate: 'today',
            locale: {
                firstDayOfWeek: 1, // Monday as first day
                weekdays: {
                    shorthand: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                    longhand: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']
                },
                months: {
                    shorthand: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    longhand: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                        'September', 'Oktober', 'November', 'Desember'
                    ]
                }
            },
            onChange: function(selectedDates, dateStr, instance) {
                const endPicker = document.getElementById('end_date')._flatpickr;
                if (selectedDates[0]) {
                    endPicker.set('minDate', selectedDates[0]);
                }
            }
        });

        flatpickr('#end_date', {
            altInput: true,
            altFormat: 'd/m/Y', // Display dd/mm/yyyy
            dateFormat: 'Y-m-d', // Submit yyyy-mm-dd
            maxDate: 'today',
            locale: {
                firstDayOfWeek: 1,
                weekdays: {
                    shorthand: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                    longhand: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']
                },
                months: {
                    shorthand: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    longhand: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                        'September', 'Oktober', 'November', 'Desember'
                    ]
                }
            },
            onChange: function(selectedDates, dateStr, instance) {
                const startPicker = document.getElementById('start_date')._flatpickr;
                if (selectedDates[0]) {
                    startPicker.set('maxDate', selectedDates[0]);
                }
            }
        });

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
                    this.transactions = @json($transactions->items());
                    this.pagination = {
                        current_page: {{ $transactions->currentPage() }},
                        last_page: {{ $transactions->lastPage() }},
                        per_page: {{ $transactions->perPage() }},
                        total: {{ $transactions->total() }},
                        from: {{ $transactions->firstItem() ?? 0 }},
                        to: {{ $transactions->lastItem() ?? 0 }}
                    };
                },

                async searchTransactions(page = 1) {
                    this.loading = true;

                    try {
                        const response = await fetch(
                            `{{ route('admin.transactions.search') }}?search=${encodeURIComponent(this.searchQuery)}&page=${page}`, {
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
                        // Optionally show a toast notification
                    } finally {
                        this.loading = false;
                    }
                },

                async deleteTransaction(id) {
                    if (!confirm('Are you sure you want to delete this transaction?')) {
                        return;
                    }

                    try {
                        const response = await fetch(`{{ url('dashboard/transactions') }}/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        if (!response.ok) {
                            throw new Error('Failed to delete transaction');
                        }

                        // Refresh the transaction list
                        await this.searchTransactions(this.pagination.current_page);

                    } catch (error) {
                        console.error('Error deleting transaction:', error);
                        alert('Failed to delete transaction. Please try again.');
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
                        'paid': 'bg-success-50 text-success-700 dark:bg-success-500/15 dark:text-success-500',
                        'pending_verification': 'bg-warning-50 text-warning-700 dark:bg-warning-500/15 dark:text-warning-500',
                        'unpaid': 'bg-warning-50 text-warning-700 dark:bg-warning-500/15 dark:text-warning-500',
                        'failed': 'bg-error-50 text-error-700 dark:bg-error-500/15 dark:text-error-500',
                        'cancelled': 'bg-error-50 text-error-700 dark:bg-error-500/15 dark:text-error-500'
                    };

                    return classes[status] || 'bg-gray-50 text-gray-600 dark:bg-gray-500/15 dark:text-gray-500';
                }
            }
        }
    </script>
@endsection
