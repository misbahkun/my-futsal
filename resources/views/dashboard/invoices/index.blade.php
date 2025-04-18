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
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="px-5 py-4 sm:px-6 sm:py-5">
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                        Invoice List
                    </h3>
                </div>

                <div class="p-5 border-t border-gray-100 dark:border-gray-800 sm:p-6">
                    <div class="max-w-full overflow-x-auto">
                        <table class="w-full table-auto">
                            <thead>
                                <tr class="text-left bg-gray-50 dark:bg-gray-800">
                                    <th class="px-4 py-3 font-medium text-gray-700 dark:text-gray-400">
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
                                    <th class="px-4 py-3 font-medium text-gray-700 dark:text-gray-400">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($invoices->count() > 0)
                                    @foreach ($invoices as $invoice)
                                        <tr class="border-b border-gray-100 dark:border-gray-800">
                                            <td class="px-4 py-4">
                                                <span class="font-medium text-gray-800 dark:text-white/90">
                                                    INV-{{ $invoice->id }}-{{ date('Ymd', strtotime($invoice->created_at)) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 text-gray-700 dark:text-gray-400">
                                                {{ date('d F Y', strtotime($invoice->updated_at)) }}
                                            </td>
                                            <td class="px-4 py-4 text-gray-700 dark:text-gray-400">
                                                Rp{{ number_format($invoice->amount, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-4 text-gray-700 dark:text-gray-400">
                                                {{ ucwords($invoice->payment_method) }}
                                            </td>
                                            @if (Auth::user()->role === 'admin')
                                                <td class="px-4 py-4 text-gray-700 dark:text-gray-400">
                                                    {{ $invoice->user->full_name ?? 'N/A' }}
                                                </td>
                                            @endif
                                            <td class="px-4 py-4">
                                                <div class="flex items-center space-x-3">
                                                    <a href="{{ route('invoice', $invoice->id) }}"
                                                        class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center rounded-lg px-3 py-2 text-sm font-medium text-white transition">
                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                                                            <path
                                                                d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z" />
                                                        </svg>
                                                        View
                                                    </a>
                                                    <a href="{{ route('generate.invoice.pdf', $invoice->id) }}"
                                                        class="bg-gray-500 shadow-theme-xs hover:bg-gray-600 inline-flex items-center rounded-lg px-3 py-2 text-sm font-medium text-white transition">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1"
                                                            fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z" />
                                                        </svg>
                                                        Download
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="border-b border-gray-100 dark:border-gray-800">
                                        <td colspan="{{ Auth::user()->role === 'admin' ? '6' : '5' }}"
                                            class="px-4 py-4 text-center text-gray-700 dark:text-gray-400">
                                            Belum ada invoice
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $invoices->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
