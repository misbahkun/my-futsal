@extends('layouts.admin.app')

@section('title', 'Invoices')
@section('page', 'invoices')

@section('content')
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
        <!-- Breadcrumb Start -->
        <div x-data="{ pageName: 'Invoices' }">
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
            <!-- Action Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <!-- Filter Button -->
                    <button type="button" onclick="openFilterModal()"
                        class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-white font-medium py-2.5 px-4 rounded-lg transition-colors duration-200 inline-flex items-center shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M10 18h4v-2h-4v2zM3 6v2h18V6H3zm3 7h12v-2H6v2z" />
                        </svg>
                        Filter Data
                        @if (request()->hasAny(['filter_type', 'payment_method', 'amount_range', 'start_date', 'end_date']))
                            <span class="ml-2 bg-brand-500 text-white text-xs px-2 py-0.5 rounded-full">
                                {{ collect([request('filter_type'), request('payment_method'), request('amount_range'), request('start_date'), request('end_date')])->filter()->count() }}
                            </span>
                        @endif
                    </button>

                    <!-- Reset Filter -->
                    @if (request()->hasAny(['filter_type', 'payment_method', 'amount_range', 'start_date', 'end_date']))
                        <a href="{{ route('user.invoices') }}"
                            class="bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-600 dark:text-white font-medium py-2.5 px-4 rounded-lg transition-colors duration-200 inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.65,6.35C16.2,4.9 14.21,4 12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20C15.73,20 18.84,17.45 19.73,14H17.65C16.83,16.33 14.61,18 12,18A6,6 0 0,1 6,12A6,6 0 0,1 12,6C13.66,6 15.14,6.69 16.22,7.78L13,11H20V4L17.65,6.35Z" />
                            </svg>
                            Reset
                        </a>
                    @endif
                </div>

                <!-- Export Button -->
                <button type="button" onclick="exportToExcel()"
                    class="relative overflow-hidden bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-black dark:text-white font-semibold py-2.5 px-6 rounded-xl shadow-lg shadow-green-500/25 hover:shadow-green-500/40 transform hover:scale-105 transition-all duration-200 inline-flex items-center group">
                    <div
                        class="absolute inset-0 bg-white/10 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-0">
                    </div>
                    <svg class="w-5 h-5 mr-2 relative z-10" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" />
                    </svg>
                    <span class="relative z-10">Export Excel</span>
                </button>
            </div>

            <!-- Active Filters Display -->
            @if (request()->hasAny(['filter_type', 'payment_method', 'amount_range', 'start_date', 'end_date']))
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
                            @if (request('payment_method') && request('payment_method') !== 'all')
                                <span
                                    class="bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-white px-2 py-1 rounded text-xs font-medium">
                                    {{ ucwords(str_replace('_', ' ', request('payment_method'))) }}
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

            <!-- Summary Statistics -->
            @if (isset($totalCount))
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-12 h-12 bg-brand-100 dark:bg-brand-900/30 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-brand-600 dark:text-brand-400" fill="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Invoice</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalCount) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Invoice List -->
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="px-5 py-4 sm:px-6 sm:py-5 border-b border-gray-100 dark:border-gray-800">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                            Daftar Invoice
                        </h3>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $invoices->total() }} total invoice
                        </span>
                    </div>
                </div>
                <div class="p-5 sm:p-6">
                    <div class="max-w-full overflow-x-auto">
                        <table class="w-full table-auto">
                            <thead>
                                <tr class="text-left bg-gray-50 dark:bg-gray-800">
                                    <th class="px-4 py-3 font-medium text-gray-700 dark:text-gray-400 rounded-l-lg">
                                        No. Invoice
                                    </th>
                                    <th class="px-4 py-3 font-medium text-gray-700 dark:text-gray-400">
                                        Tanggal
                                    </th>
                                    <th class="px-4 py-3 font-medium text-gray-700 dark:text-gray-400">
                                        Total
                                    </th>
                                    <th class="px-4 py-3 font-medium text-gray-700 dark:text-gray-400">
                                        Metode Pembayaran
                                    </th>
                                    @if (Auth::user()->role === 'admin')
                                        <th class="px-4 py-3 font-medium text-gray-700 dark:text-gray-400">
                                            User
                                        </th>
                                    @endif
                                    <th class="px-4 py-3 font-medium text-gray-700 dark:text-gray-400 rounded-r-lg">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($invoices->count() > 0)
                                    @foreach ($invoices as $invoice)
                                        <tr
                                            class="border-b border-gray-100 dark:border-gray-800 dark:hover:bg-gray-800/50 transition-colors">
                                            <td class="px-4 py-4">
                                                <span class="font-semibold text-gray-800 dark:text-white/90">
                                                    INV-{{ $invoice->id }}-{{ date('Ymd', strtotime($invoice->created_at)) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 text-gray-700 dark:text-gray-400">
                                                {{ date('d M Y', strtotime($invoice->updated_at)) }}
                                            </td>
                                            <td class="px-4 py-4 font-semibold text-gray-900 dark:text-white">
                                                Rp{{ number_format($invoice->amount, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-4">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                                    {{ $invoice->payment_method == 'qris'
                                                        ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400'
                                                        : ($invoice->payment_method == 'bank_transfer'
                                                            ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
                                                            : 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400') }}">
                                                    {{ ucwords(str_replace('_', ' ', $invoice->payment_method)) }}
                                                </span>
                                            </td>
                                            @if (Auth::user()->role === 'admin')
                                                <td class="px-4 py-4 text-gray-700 dark:text-gray-400">
                                                    <div class="flex items-center">
                                                        <div
                                                            class="w-8 h-8 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center mr-2">
                                                            <span
                                                                class="text-xs font-medium text-gray-600 dark:text-gray-300">
                                                                {{ substr($invoice->user->full_name ?? 'N', 0, 2) }}
                                                            </span>
                                                        </div>
                                                        {{ $invoice->user->full_name ?? 'N/A' }}
                                                    </div>
                                                </td>
                                            @endif
                                            <td class="px-4 py-4">
                                                <div class="flex items-center space-x-2">
                                                    <a href="{{ route('invoice', $invoice->id) }}"
                                                        class="bg-brand-500 shadow-sm hover:bg-brand-600 inline-flex items-center rounded-lg px-3 py-2 text-sm font-medium text-white transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                                                            <path
                                                                d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z" />
                                                        </svg>
                                                        View
                                                    </a>
                                                    <a href="{{ route('generate.invoice.pdf', $invoice->id) }}"
                                                        class="bg-gray-500 shadow-sm hover:bg-gray-600 inline-flex items-center rounded-lg px-3 py-2 text-sm font-medium text-white transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1"
                                                            fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z" />
                                                        </svg>
                                                        PDF
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="{{ Auth::user()->role === 'admin' ? '6' : '5' }}"
                                            class="px-4 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div
                                                    class="w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                                    <svg class="w-10 h-10 text-gray-400 dark:text-gray-600"
                                                        fill="currentColor" viewBox="0 0 24 24">
                                                        <path
                                                            d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z" />
                                                    </svg>
                                                </div>
                                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Belum
                                                    ada invoice</h3>
                                                <p class="text-gray-500 dark:text-gray-400">Invoice akan muncul di sini
                                                    setelah transaksi selesai</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    @if ($invoices->hasPages())
                        <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                            {{ $invoices->appends(request()->query())->links() }}
                        </div>
                    @endif
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
                        Filter Invoice
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
            <form method="GET" action="{{ route('user.invoices') }}" class="p-6 space-y-6">
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

    @push('script')
        <script>
            function openFilterModal() {
                document.getElementById('filterModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeFilterModal() {
                document.getElementById('filterModal').classList.add('hidden');
                document.body.style.overflow = '';
            }

            document.getElementById('filterModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeFilterModal();
                }
            });

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

            // Form validation
            document.querySelector('form').addEventListener('submit', function(e) {
                const filterType = document.querySelector('input[name="filter_type"]:checked').value;
                const startDate = document.getElementById('start_date')._flatpickr?.altInput.value || '';
                const endDate = document.getElementById('end_date')._flatpickr?.altInput.value || '';

                if (filterType === 'custom') {
                    if (!startDate || !endDate) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Error!',
                            text: 'Silakan pilih tanggal dari dan sampai.',
                            icon: 'error',
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }
                    // Convert dd/mm/yyyy to yyyy-mm-dd for comparison
                    const startDateObj = startDate.split('/').reverse().join('-');
                    const endDateObj = endDate.split('/').reverse().join('-');
                    if (new Date(startDateObj) > new Date(endDateObj)) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Error!',
                            text: 'Tanggal sampai tidak boleh sebelum tanggal dari.',
                            icon: 'error',
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });

            // Export to Excel
            function exportToExcel() {
                const urlParams = new URLSearchParams(window.location.search);
                const exportUrl = '{{ route('invoices.export') }}?' + urlParams.toString();
                const exportBtn = event.target.closest('button');
                const originalContent = exportBtn.innerHTML;
                exportBtn.innerHTML = `
                <svg class="w-5 h-5 mr-2 animate-spin" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0,0,1 12,4Z"/>
                </svg>
                <span class="relative z-10">Mengunduh...</span>
            `;
                exportBtn.disabled = true;
                window.location.href = exportUrl;
                setTimeout(() => {
                    exportBtn.innerHTML = originalContent;
                    exportBtn.disabled = false;
                }, 3000);
            }
        </script>
    @endpush
@endsection
