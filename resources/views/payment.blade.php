@extends('layouts.app')

@section('title', 'Pembayaran Online')

@section('header')
    @include('components.navbar')
    <img class="fixed top-16 z-10 shadow-lg w-full" src="{{ asset('storage/stick.png') }}" alt="stick" class="w-full">
@endsection

@section('content')

    <div class="mx-5 sm:mx-auto max-w-max-w-6.5xl">
        <div class="grid grid-cols-1 sm:grid-cols-2 sm:gap-y-5 gap-10 2xl:gap-y-9 2xl:gap-x-10 mt-32">
            <div class="bg-my-grey rounded-xl py-6 px-10">
                <p class="font-bold text-lg mb-3">Customer Detail</p>
                <form action="" method="POST">
                    @csrf
                    <div class="space-y-6 border-gray-100 mt-5">
                        {{-- Elements --}}
                        <div class="flex flex-col space-y-6 md:space-y-0 md:flex-row md:justify-between md:space-x-3">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium">
                                    Full Name
                                </label>
                                <input type="text" placeholder="Masukan Nama Lengkap"
                                    class="bg-white shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden" />
                            </div>
                            <div x-data="{
                                selectedCountry: 'ID',
                                countryCodes: {
                                    'ID': '+62',
                                },
                                phoneNumber: '62'
                            }" class="md:w-2/4">
                                <label class="mb-1.5 block text-sm font-medium">
                                    Phone
                                </label>
                                <div class="relative">
                                    <div class="absolute">
                                        <select x-model="selectedCountry"
                                            @change="phoneNumber = countryCodes[selectedCountry]"
                                            class="focus:border-brand-300 focus:ring-brand-500/10 appearance-none rounded-l-lg border-0 border-r border-gray-200 bg-transparent bg-none py-3 pr-8 pl-3.5 leading-tight focus:ring-3 focus:outline-hidden flex items-center">
                                            <option value="ID" class="flex items-center">
                                                ID
                                            </option>
                                            <!-- Add more country codes as needed -->
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                            <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke=""
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                    </div>
                                    <input placeholder="62" x-model="phoneNumber" type="tel"
                                        class="bg-white shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-3 pr-4 pl-[84px] text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden" />
                                </div>
                            </div>
                        </div>
                        {{-- Elements --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-medium">
                                Username
                            </label>
                            <input type="text" placeholder="Masukan Username"
                                class="bg-white shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10  h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden  " />
                        </div>
                        <!-- Elements -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium">
                                Email
                            </label>
                            <div class="relative">
                                <span
                                    class="absolute top-1/2 left-0 -translate-y-1/2 border-r border-gray-200 px-3.5 py-3 text-gray-500">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3.04175 7.06206V14.375C3.04175 14.6511 3.26561 14.875 3.54175 14.875H16.4584C16.7346 14.875 16.9584 14.6511 16.9584 14.375V7.06245L11.1443 11.1168C10.457 11.5961 9.54373 11.5961 8.85638 11.1168L3.04175 7.06206ZM16.9584 5.19262C16.9584 5.19341 16.9584 5.1942 16.9584 5.19498V5.20026C16.9572 5.22216 16.946 5.24239 16.9279 5.25501L10.2864 9.88638C10.1145 10.0062 9.8862 10.0062 9.71437 9.88638L3.07255 5.25485C3.05342 5.24151 3.04202 5.21967 3.04202 5.19636C3.042 5.15695 3.07394 5.125 3.11335 5.125H16.8871C16.9253 5.125 16.9564 5.15494 16.9584 5.19262ZM18.4584 5.21428V14.375C18.4584 15.4796 17.563 16.375 16.4584 16.375H3.54175C2.43718 16.375 1.54175 15.4796 1.54175 14.375V5.19498C1.54175 5.1852 1.54194 5.17546 1.54231 5.16577C1.55858 4.31209 2.25571 3.625 3.11335 3.625H16.8871C17.7549 3.625 18.4584 4.32843 18.4585 5.19622C18.4585 5.20225 18.4585 5.20826 18.4584 5.21428Z"
                                            fill="#667085" />
                                    </svg>
                                </span>
                                <input type="text" placeholder="myfutsal@gmail.com"
                                    class="bg-white shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10  h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-[62px] text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden  " />
                            </div>
                        </div>

                        <!-- Elements -->

                        <div>
                            <label class="mb-1.5 block text-sm font-medium">
                                User Photo file
                            </label>
                            <input type="file"
                                class="focus:border-ring-brand-300 bg-white shadow-theme-xs focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden" />
                        </div>

                        {{-- 
                        <div class="mt-5 flex justify-end">
                            <button
                                class="hidden bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center rounded-lg px-4 py-3 text-sm font-medium text-white transition bg-my-red">
                                Create
                            </button>
                        </div> --}}

                    </div>
                </form>
            </div>
            <div class="bg-my-grey rounded-xl py-6 px-10">
                <p class="font-bold text-lg mb-4 text-center">Panduan Pembayaran</p>
                <div class="flex mb-5">
                    <div class="flex flex-col flex-1 space-y-3">
                        <p>1. periksa kembali apakah data pemesanan sudah benar.</p>
                        <p>2. Jika data sudah benar, klik tombol "Pay Now" untuk melanjutkan proses pembayaran.</p>
                        <p>3. Setelah klik "Pay Now", halaman akan menampilkan data dan detail pemesanan lapangan. Cetak
                            bukti pemesanan dalam bentuk soft file (PDF).</p>
                        <p>4. Cetak bukti pemesanan tersebut dalam bentuk fisik dan serahkan ke kasir saat tiba di lokasi.
                        </p>
                        <p>5. Kasir akan memverifikasi bukti pemesanan. Bayar sejumlah biaya yang tertera pada bukti
                            pemesanan lapangan. Setelah pembayaran selesai, proses pemesanan lapangan futsal dianggap
                            selesai.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('footer')
    @include('components.footer')
@endsection
