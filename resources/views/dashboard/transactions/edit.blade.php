@extends('layouts.admin.app')

@section('title', 'Edit Transaction')
@section('page', 'transactions')

@section('content')
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
        <!-- Breadcrumb Start -->
        <div x-data="{ pageName: 'Edit Transaction' }">
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
                <div class="px-5 py-4 sm:px-6 sm:py-5">
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                        Edit Transaction #{{ $transaction->id }}
                    </h3>
                </div>

                <div class="p-5 border-t border-gray-100 dark:border-gray-800 sm:p-6">
                    <form action="{{ route('admin.transactions.update', $transaction->id) }}" method="POST"
                        enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- User Selection (Disabled) -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    User
                                </label>
                                @php
                                    $selectedUser = $users->firstWhere('id', $transaction->user_id);
                                    $userDisplayText = $selectedUser
                                        ? $selectedUser->full_name . ' (' . $selectedUser->email . ')'
                                        : 'User not found';
                                @endphp
                                <input type="text" value="{{ $userDisplayText }}" disabled
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                                <!-- Hidden field to keep the value when form is submitted -->
                                <input type="hidden" name="user_id" value="{{ $transaction->user_id }}" />
                                @error('user_id')
                                    <span class="text-sm text-error-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Amount (Disabled) -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Amount (Rp)
                                </label>
                                <input type="number" name="amount" value="{{ old('amount', $transaction->amount) }}"
                                    required disabled
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                                <!-- Hidden field to keep the value when form is submitted -->
                                <input type="hidden" name="amount" value="{{ old('amount', $transaction->amount) }}" />
                                @error('amount')
                                    <span class="text-sm text-error-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Payment Type -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Payment Type
                                </label>
                                <input type="payment_type" name="payment_type"
                                    value="{{ old('payment_type', $transaction->payment_type) }}" required disabled
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                                <!-- Hidden field to keep the value when form is submitted -->
                                <input type="hidden" name="payment_type"
                                    value="{{ old('payment_type', $transaction->payment_type) }}" />
                                @error('payment_type')
                                    <span class="text-sm text-error-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Payment Method (Changed to select) -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Payment Method
                                </label>
                                <input type="payment_method" name="payment_method"
                                    value="{{ old('payment_method', $transaction->payment_method) }}" required disabled
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                                <!-- Hidden field to keep the value when form is submitted -->
                                <input type="hidden" name="payment_method"
                                    value="{{ old('payment_method', $transaction->payment_method) }}" />
                                @error('payment_method')
                                    <span class="text-sm text-error-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Status -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Status
                                </label>
                                <input type="status" name="status"
                                    value="{{ old('status', $transaction->status) }}" required disabled
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                                <!-- Hidden field to keep the value when form is submitted -->
                                <input type="hidden" name="status"
                                    value="{{ old('status', $transaction->status) }}" />
                                @error('status')
                                    <span class="text-sm text-error-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end gap-4 mt-8">
                            <a href="{{ route('admin.transactions') }}"
                                class="shadow-theme-xs inline-flex items-center justify-center rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
                                Cancel
                            </a>

                            <button type="submit"
                                class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center rounded-lg px-4 py-3 text-sm font-medium text-white transition">
                                Update Transaction
                            </button>

                            @if ($transaction->status === 'paid')
                                <a href="{{ route('invoice', $transaction->id) }}" target="_blank"
                                    class="bg-success shadow-theme-xs hover:bg-success-600 inline-flex items-center rounded-lg px-4 py-3 text-sm font-medium text-white transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 6.25278V19.2528M12 6.25278L6.75 10.5028M12 6.25278L17.25 10.5028"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M5.75 14.7528V19.2528H18.25V14.7528" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    View Invoice
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
