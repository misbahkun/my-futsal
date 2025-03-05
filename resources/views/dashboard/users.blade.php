@extends('layouts.admin.app')

@section('title', 'List Users')

@section('page', 'users')

@section('content')
    <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
        <!-- Breadcrumb Start -->
        <div x-data="{ pageName: 'Users' }">
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
                        List User
                    </h3>
                    <button @click="open = !open"
                        class="shadow-theme-xs inline-flex items-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
                        Add User

                        <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M9.77692 3.24224C9.91768 3.17186 10.0834 3.17186 10.2241 3.24224L15.3713 5.81573L10.3359 8.33331C10.1248 8.43888 9.87626 8.43888 9.66512 8.33331L4.6298 5.81573L9.77692 3.24224ZM3.70264 7.0292V13.4124C3.70264 13.6018 3.80964 13.775 3.97903 13.8597L9.25016 16.4952L9.25016 9.7837C9.16327 9.75296 9.07782 9.71671 8.99432 9.67496L3.70264 7.0292ZM10.7502 16.4955V9.78396C10.8373 9.75316 10.923 9.71683 11.0067 9.67496L16.2984 7.0292V13.4124C16.2984 13.6018 16.1914 13.775 16.022 13.8597L10.7502 16.4955ZM9.41463 17.4831L9.10612 18.1002C9.66916 18.3817 10.3319 18.3817 10.8949 18.1002L16.6928 15.2013C17.3704 14.8625 17.7984 14.17 17.7984 13.4124V6.58831C17.7984 5.83076 17.3704 5.13823 16.6928 4.79945L10.8949 1.90059C10.3319 1.61908 9.66916 1.61907 9.10612 1.90059L9.44152 2.57141L9.10612 1.90059L3.30823 4.79945C2.63065 5.13823 2.20264 5.83076 2.20264 6.58831V13.4124C2.20264 14.17 2.63065 14.8625 3.30823 15.2013L9.10612 18.1002L9.41463 17.4831Z"
                                fill="" />
                        </svg>
                    </button>

                    <!-- Modal -->
                    <div x-show="open" class="fixed inset-0 flex items-center justify-center z-50">
                        <div @click="open = false" class="fixed inset-0 bg-black opacity-50"></div>
                        <div
                            class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                            <div class="flex justify-between px-5 py-4 sm:px-6 sm:py-5">
                                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                                    Add New User
                                </h3>
                                <img @click="open = false"
                                    :src="darkMode ? '{{ asset('storage/close-white.png') }}' :
                                        '{{ asset('storage/close.png') }}'"
                                    class="w-8 h-8 cursor-pointer" alt="close button">
                            </div>
                            <form action="" method="POST">
                                @csrf
                                <div class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
                                    {{-- Elements --}}
                                    <div>
                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                            Full Name
                                        </label>
                                        <input type="text" placeholder="Masukan Nama Lengkap"
                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                    </div>
                                    {{-- Elements --}}
                                    <div>
                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                            Username
                                        </label>
                                        <input type="text" placeholder="Masukan Username"
                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                    </div>
                                    <!-- Elements -->
                                    <div>
                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                            Email
                                        </label>
                                        <div class="relative">
                                            <span
                                                class="absolute top-1/2 left-0 -translate-y-1/2 border-r border-gray-200 px-3.5 py-3 text-gray-500 dark:border-gray-800 dark:text-gray-400">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M3.04175 7.06206V14.375C3.04175 14.6511 3.26561 14.875 3.54175 14.875H16.4584C16.7346 14.875 16.9584 14.6511 16.9584 14.375V7.06245L11.1443 11.1168C10.457 11.5961 9.54373 11.5961 8.85638 11.1168L3.04175 7.06206ZM16.9584 5.19262C16.9584 5.19341 16.9584 5.1942 16.9584 5.19498V5.20026C16.9572 5.22216 16.946 5.24239 16.9279 5.25501L10.2864 9.88638C10.1145 10.0062 9.8862 10.0062 9.71437 9.88638L3.07255 5.25485C3.05342 5.24151 3.04202 5.21967 3.04202 5.19636C3.042 5.15695 3.07394 5.125 3.11335 5.125H16.8871C16.9253 5.125 16.9564 5.15494 16.9584 5.19262ZM18.4584 5.21428V14.375C18.4584 15.4796 17.563 16.375 16.4584 16.375H3.54175C2.43718 16.375 1.54175 15.4796 1.54175 14.375V5.19498C1.54175 5.1852 1.54194 5.17546 1.54231 5.16577C1.55858 4.31209 2.25571 3.625 3.11335 3.625H16.8871C17.7549 3.625 18.4584 4.32843 18.4585 5.19622C18.4585 5.20225 18.4585 5.20826 18.4584 5.21428Z"
                                                        fill="#667085" />
                                                </svg>
                                            </span>
                                            <input type="text" placeholder="myfutsal@gmail.com"
                                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-[62px] text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                        </div>
                                    </div>

                                    <!-- Elements -->
                                    <div x-data="{
                                        selectedCountry: 'ID',
                                        countryCodes: {
                                            'ID': '+62',
                                        },
                                        phoneNumber: '62'
                                    }">
                                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                            Phone
                                        </label>
                                        <div class="relative">
                                            <div class="absolute">
                                                <select x-model="selectedCountry"
                                                    @change="phoneNumber = countryCodes[selectedCountry]"
                                                    class="focus:border-brand-300 focus:ring-brand-500/10 appearance-none rounded-l-lg border-0 border-r border-gray-200 bg-transparent bg-none py-3 pr-8 pl-3.5 leading-tight text-gray-700 focus:ring-3 focus:outline-hidden dark:border-gray-800 dark:text-gray-400">
                                                    <option value="ID"
                                                        class="text-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                                        ID
                                                    </option>
                                                    <!-- Add more country codes as needed -->
                                                </select>
                                                <div
                                                    class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-700 dark:text-gray-400">
                                                    <svg class="stroke-current" width="20" height="20"
                                                        viewBox="0 0 20 20" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396"
                                                            stroke="" stroke-width="1.5" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <input placeholder="62" x-model="phoneNumber" type="tel"
                                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-3 pr-4 pl-[84px] text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                                        </div>
                                    </div>
                                    <div class="mt-5 flex justify-end">
                                        <button
                                            class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center rounded-lg px-4 py-3 text-sm font-medium text-white transition">
                                            Create
                                        </button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- End Modal -->
                </div>
                <div class="p-5 border-t border-gray-100 dark:border-gray-800 sm:p-6">
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
                                                    User
                                                </p>
                                            </div>
                                        </th>
                                        <th class="px-5 py-3 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    Email
                                                </p>
                                            </div>
                                        </th>
                                        <th class="px-5 py-3 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    Phone Number
                                                </p>
                                            </div>
                                        </th>
                                        <th class="px-5 py-3 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    Email Verified At
                                                </p>
                                            </div>
                                        </th>
                                        <th class="px-5 py-3 sm:px-6">
                                            <div class="flex items-center">
                                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                                    Updated At
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
                                    @yield('tbody_content')
                                    @foreach ($users as $user)
                                        <tr>
                                            <td class="px-5 py-4 sm:px-6">
                                                <div class="flex items-center">
                                                    <div class="flex items-center gap-3">
                                                        <div>
                                                            <span
                                                                class="block font-medium text-gray-800 text-theme-sm dark:text-white/90">
                                                                {{ $user->full_name }}
                                                            </span>
                                                            <span
                                                                class="block text-gray-500 text-theme-xs dark:text-gray-400">
                                                                {{ $user->role }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 sm:px-6">
                                                <div class="flex items-center">
                                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                        {{ $user->email }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 sm:px-6">
                                                <div class="flex items-center">
                                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                                        {{ $user->phone_number }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 sm:px-6">
                                                <div class="flex items-center">
                                                    <p
                                                        class="rounded-full bg-success-50 px-2 py-0.5 text-theme-xs font-medium text-success-700 dark:bg-success-500/15 dark:text-success-500">
                                                        {{ $user->email_verified_at }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 sm:px-6">
                                                <div class="flex items-center">
                                                    <p
                                                        class="rounded-full bg-warning-50 px-2 py-0.5 text-theme-xs font-medium text-warning-700 dark:bg-warning-500/15 dark:text-warning-500">
                                                        {{ $user->updated_at }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4 sm:px-6">
                                                <div class="flex items-center gap-2">
                                                    <span
                                                        class="cursor-pointer inline-flex items-center justify-center gap-1 rounded-full bg-warning-500 px-2.5 py-0.5 text-sm font-medium text-white">
                                                        Edit
                                                    </span>
                                                    <span
                                                        class="cursor-pointer inline-flex items-center justify-center gap-1 rounded-full bg-error-500 px-2.5 py-0.5 text-sm font-medium text-white">
                                                        Delete
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- ====== Table Six End -->
                </div>
            </div>
        </div>
    </div>

@endsection
