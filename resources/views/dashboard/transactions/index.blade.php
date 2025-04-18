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
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="px-5 py-4 sm:px-6 sm:py-5 flex justify-between items-center" x-data="{ open: false }">
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                        All Transactions
                    </h3>
                </div>

                <div class="p-5 border-t border-gray-100 dark:border-gray-800 sm:p-6">
                    <!-- Success alert with invoice link -->
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
                                                    ID
                                                </p>
                                            </div>
                                        </th>
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
                                                    Amount
                                                </p>
                                            </div>
                                        </th>
                                        <th class="px-5 py-3 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    Status
                                                </p>
                                            </div>
                                        </th>
                                        <th class="px-5 py-3 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    Payment Type
                                                </p>
                                            </div>
                                        </th>
                                        <th class="px-5 py-3 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    Date
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
                                    @if ($transactions->count() > 0)
                                        @foreach ($transactions as $transaction)
                                            <tr>
                                                <td class="px-5 py-4 sm:px-6">
                                                    <div class="flex items-center">
                                                        <p
                                                            class="text-gray-800 text-theme-sm font-medium dark:text-white/90">
                                                            {{ $transaction->id }}
                                                        </p>
                                                    </div>
                                                </td>
                                                <td class="px-5 py-4 sm:px-6">
                                                    <div class="flex items-center">
                                                        <div class="flex items-center gap-3">
                                                            <div>
                                                                <span
                                                                    class="block font-medium text-gray-800 text-theme-sm dark:text-white/90">
                                                                    {{ $transaction->user->full_name }}
                                                                </span>
                                                                <span
                                                                    class="block text-gray-500 text-theme-xs dark:text-gray-400">
                                                                    {{ $transaction->user->email }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-5 py-4 sm:px-6">
                                                    <div class="flex items-center">
                                                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                            Rp{{ number_format($transaction->amount, 0, ',', '.') }}
                                                        </p>
                                                    </div>
                                                </td>
                                                <td class="px-5 py-4 sm:px-6">
                                                    <div class="flex items-center">
                                                        <span
                                                            class="inline-flex rounded-full py-1 px-3 text-sm font-medium 
                                                            {{ $transaction->status === 'paid'
                                                                ? 'bg-success-50 text-success-700 dark:bg-success-500/15 dark:text-success-500'
                                                                : ($transaction->status === 'pending_verification'
                                                                    ? 'bg-warning-50 text-warning-700 dark:bg-warning-500/15 dark:text-warning-500'
                                                                    : ($transaction->status === 'unpaid'
                                                                        ? 'bg-error-50 text-warning-700 dark:bg-warning-500/15 dark:text-warning-500'
                                                                        : 'bg-gray-50 text-error-700 dark:bg-error-500/15 dark:text-error-500')) }}">
                                                            {{ str_replace('_', ' ', ucfirst($transaction->status)) }}
                                                            @if ($transaction->status === 'paid')
                                                                <a href="{{ route('invoice', $transaction->id) }}"
                                                                    target="_blank" class="ml-2 inline-flex items-center">
                                                                    <svg width="16" height="16" viewBox="0 0 24 24"
                                                                        fill="none" xmlns="http://www.w3.org/2000/svg"
                                                                        class="inline">
                                                                        <path
                                                                            d="M13.0002 3H7.00024C5.89567 3 5.00024 3.89543 5.00024 5V19C5.00024 20.1046 5.89567 21 7.00024 21H17.0002C18.1048 21 19.0002 20.1046 19.0002 19V9M13.0002 3L19.0002 9M13.0002 3V9H19.0002"
                                                                            stroke="currentColor" stroke-width="2"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round" />
                                                                    </svg>
                                                                </a>
                                                            @endif
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="px-5 py-4 sm:px-6">
                                                    <div class="flex items-center">
                                                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                            {{ str_replace('_', ' ', ucfirst($transaction->payment_type)) }}
                                                        </p>
                                                    </div>
                                                </td>
                                                <td class="px-5 py-4 sm:px-6">
                                                    <div class="flex items-center">
                                                        <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                            {{ date('d M Y', strtotime($transaction->created_at)) }}
                                                        </p>
                                                    </div>
                                                </td>
                                                <td class="px-5 py-4 sm:px-6">
                                                    <div class="flex items-center gap-2">
                                                        <a href="{{ route('admin.transactions.show', $transaction->id) }}"
                                                            class="cursor-pointer inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-500 hover:bg-blue-600 text-white"
                                                            title="View">
                                                            <svg fill="#ff0000" version="1.1" id="Capa_1"
                                                                xmlns="http://www.w3.org/2000/svg"
                                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="64px"
                                                                height="64px" viewBox="0 0 442.04 442.04"
                                                                xml:space="preserve" stroke="#ff0000">
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
                                                        <a href="{{ route('admin.transactions.edit', $transaction->id) }}"
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
                                                            action="{{ route('admin.transactions.destroy', $transaction->id) }}"
                                                            method="POST" class="inline"
                                                            onsubmit="return confirm('Are you sure you want to delete this transaction?')">
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
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="px-5 py-4 sm:px-6 text-center">
                                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                    No transactions found
                                                </p>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- ====== Table Six End -->

                    <!-- Pagination -->
                    <div class="my-6">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
