@extends('layouts.app')

@section('title', 'Pembayaran Online')

@section('header')
    @include('components.navbar')
    <img class="fixed top-16 z-10 shadow-lg w-full" src="{{ asset('storage/stick.png') }}" alt="stick" class="w-full">
@endsection

@section('content')

    <div class="mx-5 sm:mx-auto max-w-max-w-6.5xl mt-24 md:mt-32">


        <div class="flex flex-col md:flex-row md:justify-between md:space-x-5 space-y-5 md:space-y-0 mb-12">
            <div class="h-[37rem] md:h-[35rem] overflow-y-auto md:w-7/12 ">
                <div class="bg-my-grey py-6 px-10 rounded-xl">
                    <h1 class="font-bold text-lg mb-3">Data Penyewa</h1>
                    <form action="" method="POST" class="border-t border-white">
                        <p class="mt-3">Sudah punya akun MyFutsal?<a href="{{ route('login') }}"
                                class="text-my-red ms-2 font-semibold">Klik Disini</a></p>
                        @csrf
                        <div class="space-y-6 border-gray-100 mt-5">
                            {{-- Elements --}}
                            <div class="flex flex-col space-y-6 md:space-y-0 md:flex-row md:justify-between md:space-x-3">
                                <div>
                                    <label class="mb-1.5 block text-sm font-medium">
                                        Full Name
                                    </label>
                                    <input type="text" placeholder="Masukan Nama Lengkap"
                                        class="bg-white shadow-theme-xs h-11 w-full rounded-lg border-my-red border-t-2 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden" />
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
                                                class="appearance-none rounded-l-lg border-0 border-r border-gray-200 bg-transparent bg-none py-3 pr-8 pl-3.5 leading-tight focus:ring-3 focus:outline-hidden flex items-center">
                                                <option value="ID" class="flex items-center">
                                                    ID
                                                </option>
                                                <!-- Add more country codes as needed -->
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                                <svg class="stroke-current" width="20" height="20"
                                                    viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke=""
                                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        </div>
                                        <input placeholder="62" x-model="phoneNumber" type="tel"
                                            class="bg-white shadow-theme-xs h-11 w-full rounded-lg border-my-red border-t-2 bg-transparent py-3 pr-4 pl-[84px] text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden" />
                                    </div>
                                </div>
                            </div>
                            {{-- Elements --}}
                            {{-- <div>
                                <label class="mb-1.5 block text-sm font-medium">
                                    Username
                                </label>
                                <input type="text" placeholder="Masukan Username"
                                    class="bg-white shadow-theme-xs  h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden  " />
                            </div> --}}
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
                                        class="border-my-red border-t-2 bg-white shadow-theme-xs  h-11 w-full rounded-lg bg-transparent px-4 py-2.5 pl-[62px] text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden  " />
                                </div>
                            </div>
                            {{-- Elements --}}
                            <div class="rounded-lg border border-white bg-gray-100 p-4">
                                <div class="flex items-start gap-3">
                                    <div class="-mt-0.5">
                                        <svg class="fill-current" width="20" height="20" viewBox="0 0 24 24"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M3.6501 11.9996C3.6501 7.38803 7.38852 3.64961 12.0001 3.64961C16.6117 3.64961 20.3501 7.38803 20.3501 11.9996C20.3501 16.6112 16.6117 20.3496 12.0001 20.3496C7.38852 20.3496 3.6501 16.6112 3.6501 11.9996ZM12.0001 1.84961C6.39441 1.84961 1.8501 6.39392 1.8501 11.9996C1.8501 17.6053 6.39441 22.1496 12.0001 22.1496C17.6058 22.1496 22.1501 17.6053 22.1501 11.9996C22.1501 6.39392 17.6058 1.84961 12.0001 1.84961ZM10.9992 7.52468C10.9992 8.07697 11.4469 8.52468 11.9992 8.52468H12.0002C12.5525 8.52468 13.0002 8.07697 13.0002 7.52468C13.0002 6.9724 12.5525 6.52468 12.0002 6.52468H11.9992C11.4469 6.52468 10.9992 6.9724 10.9992 7.52468ZM12.0002 17.371C11.586 17.371 11.2502 17.0352 11.2502 16.621V10.9445C11.2502 10.5303 11.586 10.1945 12.0002 10.1945C12.4144 10.1945 12.7502 10.5303 12.7502 10.9445V16.621C12.7502 17.0352 12.4144 17.371 12.0002 17.371Z"
                                                fill="" />
                                        </svg>
                                    </div>

                                    <div>
                                        <p class="text-xs text-gray-800">
                                            Dengan melakukan booking lapangan, Kamu akan otomatis terdaftar sebagai pengguna
                                            MyFutsal.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Elements -->

                            {{-- <div>
                                <label class="mb-1.5 block text-sm font-medium">
                                    User Photo file
                                </label>
                                <input type="file"
                                    class="focus:border-ring-brand-300 bg-white shadow-theme-xs focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden" />
                            </div> --}}

                            {{-- 
                            <div class="mt-5 flex justify-end">
                                <button
                                    class="hidden bg-brand-500 shadow-theme-xs hover:bg-brand-600 items-center rounded-lg px-4 py-3 text-sm font-medium text-white transition bg-my-red">
                                    Create
                                </button>
                            </div> --}}

                        </div>
                    </form>
                </div>
                <div class="bg-my-grey rounded-xl py-6 px-10 mt-5" x-data="{ open: false, selectedBank: 'bca' }">
                    <p class="font-bold text-lg mb-3">Metode Pembayaran</p>

                    <div class="border-t border-white">
                        <!-- Parent clickable element -->
                        <div @click="open = !open"
                            class="select-none flex justify-between items-center shadow-lg rounded-t-lg bg-white border-my-red border-t-2 pt-4 pb-2 px-3 mt-5 cursor-pointer">
                            <div class="flex gap-x-3 ms-3">
                                <img src="{{ asset('storage/bank_account.png') }}"
                                    class="w-14 h-fit border rounded-lg px-1 py-0" alt="transfer bank account">
                                <div class="flex flex-col">
                                    <p class="text-sm font-semibold">Transfer Bank Account</p>
                                    <div class="flex space-x-3">
                                        <img src="{{ asset('storage/bca-bank-central-asia.svg') }}" class="w-10 h-10"
                                            alt="bank bca">
                                        <img src="{{ asset('storage/bank-mandiri.svg') }}" class="w-10 h-10"
                                            alt="bank mandiri">
                                    </div>
                                </div>
                            </div>
                            <img :src="open ? '{{ asset('storage/up-arrows.png') }}' : '{{ asset('storage/down-arrows.png') }}'"
                                class="w-5 h-5 me-5" alt="chevron">
                        </div>

                        <div x-show="open" class="rounded-b-lg overflow-hidden shadow-lg select-none" x-transition>
                            <div class="bg-white">
                                <!-- BCA Option -->
                                @foreach ($banks as $bank)
                                    <label
                                        class="flex items-center gap-x-3 ps-6 pt-4 pb-3 px-3 cursor-pointer hover:bg-gray-50 border-b border-gray-100">
                                        <img src="{{ asset('storage/' . $bank->image) }}"
                                            class="w-14 h-fit rounded-lg px-1 py-2" alt="bca">

                                        <div class="flex flex-col">
                                            <p class="text-sm font-semibold">{{ $bank->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $bank->type }}</p>
                                        </div>
                                        <input type="radio" name="bank" value="bca" x-model="selectedBank"
                                            class="h-4 w-4 ms-auto me-5 text-my-red">
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="md:w-5/12">
                <div class="h-max py-6 px-10 shadow-xl rounded-lg border-my-red border-t-2">
                    <h1 class="font-bold text-lg mb-3">Rincian Biaya</h1>
                    <div class="border-t border-gray-300 mb-3"></div>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <p>Biaya Sewa</p>
                            <p>Rp. 200.000</p>
                        </div>
                        <div class="flex justify-between">
                            <p>Biaya Transaksi</p>
                            <p>Rp. 0</p>
                        </div>
                        <div class="flex justify-between">
                            <p>Diskon</p>
                            <p>Rp. 0</p>
                        </div>
                    </div>
                    <div class="border-b border-gray-300 my-3"></div>
                    <div class="flex justify-between font-semibold">
                        <p>Total Bayar</p>
                        <p>Rp. 0</p>
                    </div>
                </div>

                <p class="text-sm my-6">Dengan mengklik tombol berikut, Anda menyetujui <a href="#"
                        class="text-my-red">Syarat dan Ketentuan</a> serta
                    <a href="#" class="text-my-red">Kebijakan privasi.</a>
                </p>
                <button type="submit"
                    class="w-full bg-my-red font-bold text-xl text-white py-2 rounded-lg hover:bg-red-800 cursor-pointer">
                    Bayar Sekarang
                </button>
            </div>
        </div>

        <div class=" grid-cols-1 sm:grid-cols-2 hidden sm:gap-y-5 gap-10 2xl:gap-y-9 2xl:gap-x-10 mt-32">
            <div class="bg-my-grey rounded-xl py-6 px-10 overflow-y-auto h-100">
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
                                    class="bg-white shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden" />
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
                                            class="appearance-none rounded-l-lg border-0 border-r border-gray-200 bg-transparent bg-none py-3 pr-8 pl-3.5 leading-tight focus:ring-3 focus:outline-hidden flex items-center">
                                            <option value="ID" class="flex items-center">
                                                ID
                                            </option>
                                            <!-- Add more country codes as needed -->
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                            <svg class="stroke-current" width="20" height="20"
                                                viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke=""
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                    </div>
                                    <input placeholder="62" x-model="phoneNumber" type="tel"
                                        class="bg-white shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent py-3 pr-4 pl-[84px] text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden" />
                                </div>
                            </div>
                        </div>
                        {{-- Elements --}}
                        <div>
                            <label class="mb-1.5 block text-sm font-medium">
                                Username
                            </label>
                            <input type="text" placeholder="Masukan Username"
                                class="bg-white shadow-theme-xs focus:border-brand-300  h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden  " />
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
                                    class="bg-white shadow-theme-xs focus:border-brand-300  h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pl-[62px] text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden  " />
                            </div>
                        </div>

                        <!-- Elements -->

                        {{-- <div>
                            <label class="mb-1.5 block text-sm font-medium">
                                User Photo file
                            </label>
                            <input type="file"
                                class="focus:border-ring-brand-300 bg-white shadow-theme-xs focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden" />
                        </div> --}}

                        {{-- 
                        <div class="mt-5 flex justify-end">
                            <button
                                class="hidden bg-brand-500 shadow-theme-xs hover:bg-brand-600 items-center rounded-lg px-4 py-3 text-sm font-medium text-white transition bg-my-red">
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
