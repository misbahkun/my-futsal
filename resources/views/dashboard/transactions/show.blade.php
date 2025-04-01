@extends('layouts.admin.app')

@section('title', 'Transaction Details')
@section('page', 'transactions')

@section('content')
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
        <!-- Breadcrumb Start -->
        <div x-data="{ pageName: 'Transaction Details' }">
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
                                href="{{ route('admin.transactions') }}">
                                Transactions
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
                        Transaction #{{ $transaction->id }}
                    </h3>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.transactions.edit', $transaction->id) }}"
                            class="cursor-pointer inline-flex items-center justify-center gap-1 rounded-full bg-warning-500 px-2.5 py-0.5 text-sm font-medium text-white">
                            Edit
                        </a>
                        <form action="{{ route('admin.transactions.destroy', $transaction->id) }}" method="POST"
                            class="inline" onsubmit="return confirm('Are you sure you want to delete this transaction?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="cursor-pointer inline-flex items-center justify-center gap-1 rounded-full bg-error-500 px-2.5 py-0.5 text-sm font-medium text-white">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>

                <div class="p-5 border-t border-gray-100 dark:border-gray-800 sm:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Transaction Details -->
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-5">
                            <h4 class="text-lg font-medium text-gray-800 dark:text-white/90 mb-4">Transaction Information
                            </h4>

                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Transaction ID</p>
                                    <p class="text-base font-medium text-gray-800 dark:text-white/90">
                                        #{{ $transaction->id }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Date</p>
                                    <p class="text-base font-medium text-gray-800 dark:text-white/90">
                                        {{ date('d F Y, H:i', strtotime($transaction->created_at)) }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Amount</p>
                                    <p class="text-base font-medium text-gray-800 dark:text-white/90">
                                        Rp{{ number_format($transaction->amount, 0, ',', '.') }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Payment Type</p>
                                    <p class="text-base font-medium text-gray-800 dark:text-white/90">
                                        {{ str_replace('_', ' ', ucfirst($transaction->payment_type)) }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Payment Method</p>
                                    <p class="text-base font-medium text-gray-800 dark:text-white/90">
                                        {{ ucwords($transaction->payment_method) }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                                    <div class="flex items-center mt-1">
                                        <span
                                            class="inline-flex rounded-full py-1 px-3 text-sm font-medium 
                                        {{ $transaction->status === 'paid'
                                            ? 'bg-success-50 text-success-700 dark:bg-success-500/15 dark:text-success-500'
                                            : ($transaction->status === 'pending_verification'
                                                ? 'bg-warning-50 text-warning-700 dark:bg-warning-500/15 dark:text-warning-500'
                                                : ($transaction->status === 'unpaid'
                                                    ? 'bg-error-50 text-error-700 dark:bg-error-500/15 dark:text-error-500'
                                                    : 'bg-gray-50 text-gray-700 dark:bg-gray-500/15 dark:text-gray-500')) }}">
                                            {{ str_replace('_', ' ', ucfirst($transaction->status)) }}
                                        </span>
                                    </div>

                                    @if ($transaction->status === 'paid')
                                        <div
                                            class="mt-2 inline-flex items-center px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            Invoice Available
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- User Details -->
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-5">
                            <h4 class="text-lg font-medium text-gray-800 dark:text-white/90 mb-4">User Information</h4>

                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Name</p>
                                    <p class="text-base font-medium text-gray-800 dark:text-white/90">
                                        {{ $transaction->user->full_name }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Email</p>
                                    <p class="text-base font-medium text-gray-800 dark:text-white/90">
                                        {{ $transaction->user->email }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Phone</p>
                                    <p class="text-base font-medium text-gray-800 dark:text-white/90">
                                        {{ $transaction->user->phone_number ?? 'N/A' }}</p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">User Since</p>
                                    <p class="text-base font-medium text-gray-800 dark:text-white/90">
                                        {{ date('d F Y', strtotime($transaction->user->created_at)) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Schedule Information (if available) -->
                    @php
                        $schedules = \App\Models\Schedule::where('transaction_id', $transaction->id)
                            ->with(['field.venue'])
                            ->get();
                    @endphp

                    @if ($schedules->count() > 0)
                        <div class="mt-6 bg-gray-50 dark:bg-gray-800/50 rounded-lg p-5">
                            <h4 class="text-lg font-medium text-gray-800 dark:text-white/90 mb-4">Booking Information</h4>

                            <div class="overflow-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead>
                                        <tr>
                                            <th
                                                class="px-4 py-3 bg-gray-100 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Field</th>
                                            <th
                                                class="px-4 py-3 bg-gray-100 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Venue</th>
                                            <th
                                                class="px-4 py-3 bg-gray-100 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Date</th>
                                            <th
                                                class="px-4 py-3 bg-gray-100 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Time Slot</th>
                                            <th
                                                class="px-4 py-3 bg-gray-100 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Status</th>
                                            <th
                                                class="px-4 py-3 bg-gray-100 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Price</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($schedules as $schedule)
                                            <tr>
                                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                                    {{ $schedule->field->name }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                                    {{ $schedule->field->venue->name }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                                    {{ date('d F Y', strtotime($schedule->date)) }}</td>
                                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                                    {{ $schedule->time_slots }}</td>
                                                <td class="px-4 py-3 text-sm">
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        {{ $schedule->status === 'booked' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                        {{ ucfirst($schedule->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                                    Rp{{ number_format($schedule->price, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5"
                                                class="px-4 py-3 text-sm font-medium text-right text-gray-700 dark:text-gray-300">
                                                Total</td>
                                            <td class="px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-300">
                                                Rp{{ number_format($schedules->sum('price'), 0, ',', '.') }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- Payment Proof (if available) -->
                    @if ($transaction->payment_proof)
                        <div class="mt-6 bg-gray-50 dark:bg-gray-800/50 rounded-lg p-5">
                            <h4 class="text-lg font-medium text-gray-800 dark:text-white/90 mb-4">Payment Proof</h4>

                            <div class="flex items-start gap-4">
                                <a href="{{ Storage::url($transaction->payment_proof) }}" target="_blank"
                                    class="block max-w-xs">
                                    <img src="{{ Storage::url($transaction->payment_proof) }}" alt="Payment Proof"
                                        class="rounded-md border border-gray-200 dark:border-gray-700">
                                </a>

                                <div class="space-y-2">
                                    <a href="{{ Storage::url($transaction->payment_proof) }}" target="_blank" download
                                        class="inline-flex items-center rounded-md bg-primary px-3 py-2 text-sm font-medium text-white hover:bg-opacity-90">
                                        <svg class="mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Download
                                    </a>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Uploaded: {{ date('d F Y, H:i', strtotime($transaction->updated_at)) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex justify-between items-center mt-6">
                        <a href="{{ route('admin.transactions') }}"
                            class="shadow-theme-xs inline-flex items-center justify-center rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
                            <svg class="mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Back to Transactions
                        </a>

                        @if ($transaction->status === 'paid')
                            <a href="{{ route('invoice', $transaction->id) }}" target="_blank"
                                class="bg-success shadow-theme-xs hover:bg-success-600 inline-flex items-center rounded-lg px-4 py-3 text-sm font-medium text-white transition">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                View Invoice
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
