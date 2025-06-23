@extends('layouts.app')

@section('title', 'Pembayaran Online')

@section('header')
    @include('components.navbar')
    <img class="fixed top-16 z-10 shadow-lg w-full" src="{{ asset('storage/stick.png') }}" alt="stick" class="w-full">
@endsection

@push('styles')
    <style>
        .verification-code-input {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin: 25px 0;
        }

        .verification-code-input input {
            width: 40px;
            height: 40px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #f7fafc;
        }

        .verification-code-input input:focus {
            border-color: #DC2626;
            outline: none;
            box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.2);
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
@endpush

@push('head-scripts')
    <script>
        window.isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
    </script>
@endpush

@section('content')
    <div class="mx-5 sm:mx-auto max-w-max-w-6.5xl mt-24 md:mt-32" x-data="{
        phoneNumber: '{{ $phoneNumber ?: '' }}',
        email: '{{ $userEmail }}',
        cart: $store.cart,
        payment: $store.payment,
        snapToken: '{{ $snapToken ?? '' }}', // Store snapToken from server
    }">
        <div class="flex flex-col md:flex-row md:justify-between md:space-x-5 space-y-5 md:space-y-0 mb-12">
            <div class="h-[37rem] md:h-[35rem] overflow-y-auto md:w-7/12 ">
                <div class="bg-my-grey py-6 px-10 rounded-xl">
                    <h1 class="font-bold text-lg mb-3">Data Penyewa</h1>
                    <div class="border-t border-white">
                        @if (!Auth::check())
                            <p class="mt-3">Sudah punya akun MyFutsal?<a href="{{ route('login') }}"
                                    class="text-my-red ms-2 font-semibold">Klik Disini</a></p>
                        @endif
                        <div class="space-y-6 border-gray-100 mt-5">
                            {{-- Elements --}}
                            <div class="flex flex-col space-y-6 md:space-y-0 md:flex-row md:justify-between md:space-x-3">
                                <div class="flex-1">
                                    <label class="mb-1.5 block text-sm font-medium">
                                        Full Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="full_name" name="full_name"
                                        placeholder="Masukkan Nama Lengkap" required
                                        value="{{ Auth::check() ? Auth::user()->full_name : old('full_name') }}"
                                        class="bg-white shadow-theme-xs h-11 w-full rounded-lg border-my-red border-t-2 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden"
                                        {{ Auth::check() ? 'readonly' : '' }} form="paymentForm" />
                                    @error('full_name')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="flex-1">
                                    <label class="mb-1.5 block text-sm font-medium">
                                        Phone <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute">
                                            <select
                                                class="appearance-none rounded-l-lg border-0 border-r border-gray-200 bg-transparent bg-none py-3 pr-8 pl-3.5 leading-tight focus:ring-3 focus:outline-hidden flex items-center"
                                                {{ Auth::check() ? 'disabled' : '' }}>
                                                <option value="ID" class="flex items-center">
                                                    ID
                                                </option>
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                                <svg class="stroke-current" width="20" height="20"
                                                    viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke=""
                                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        </div>
                                        <input placeholder="Masukkan nomor telepon" id="phone_number" type="tel"
                                            name="phone_number" required
                                            value="{{ Auth::check() ? Auth::user()->phone_number : old('phone_number') }}"
                                            class="bg-white shadow-theme-xs h-11 w-full rounded-lg border-my-red border-t-2 bg-transparent py-3 pr-4 pl-[84px] text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden"
                                            {{ Auth::check() ? 'readonly' : '' }} form="paymentForm" />
                                        @error('phone_number')
                                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Elements -->
                            <div>
                                <label class="mb-1.5 block text-sm font-medium">
                                    Email <span class="text-red-500">*</span>
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
                                    <input type="email" id="email" name="email" placeholder="myfutsal@gmail.com"
                                        required value="{{ Auth::check() ? Auth::user()->email : old('email') }}"
                                        class="border-my-red border-t-2 bg-white shadow-theme-xs h-11 w-full rounded-lg bg-transparent px-4 py-2.5 pl-[62px] text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden"
                                        {{ Auth::check() ? 'readonly' : '' }} form="paymentForm" />
                                    @error('email')
                                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Info box --}}
                            @if (!Auth::check())
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
                                                Dengan melakukan booking lapangan, Kamu akan otomatis terdaftar
                                                sebagai
                                                pengguna
                                                MyFutsal.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="md:w-5/12">
                <div class="h-max py-6 px-10 shadow-xl rounded-lg border-my-red border-t-2">
                    <h1 class="font-bold text-lg mb-3">Rincian Biaya</h1>
                    <div class="border-t border-gray-300 mb-3"></div>
                    <div class="space-y-2">
                        @foreach ($items as $item)
                            <div class="flex justify-between mb-2">
                                <div>
                                    <p class="font-medium">{{ $item['field'] }} - {{ $item['venue'] }}</p>
                                    <p class="text-sm text-gray-500">{{ $item['date'] }}</p>

                                    @if (isset($item['originalPrice']))
                                        <div class="flex items-center space-x-2">
                                            <p class="text-sm text-gray-500 line-through">
                                                Rp. {{ number_format($item['originalPrice'], 0, ',', '.') }}
                                            </p>
                                            <p class="text-sm text-green-600">
                                                Diskon {{ $discountPercentage }}%
                                            </p>
                                        </div>
                                    @endif
                                </div>
                                <p>Rp. {{ number_format($item['price'], 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="border-b border-gray-300 my-3"></div>

                    @if ($originalTotalPrice > 0)
                        <div class="flex justify-between text-gray-600 mb-2 font-semibold">
                            <p>Total Harga</p>
                            <p>Rp. {{ number_format($originalTotalPrice, 0, ',', '.') }}</p>
                        </div>
                        <div class="flex justify-between text-my-red mb-2 font-semibold">
                            <p>Diskon</p>
                            <p>- Rp. {{ number_format($originalTotalPrice - $totalPrice, 0, ',', '.') }}</p>
                        </div>
                    @endif

                    <div class="flex justify-between text-lg font-bold">
                        @if ($originalTotalPrice > 0)
                            <p class="text-green-600">Total Bayar</p>
                            <p class="text-green-600">Rp. {{ number_format($totalPrice, 0, ',', '.') }}</p>
                        @else
                            <p>Total Bayar</p>
                            <p>Rp. {{ number_format($totalPrice, 0, ',', '.') }}</p>
                        @endif
                    </div>
                </div>

                <p class="text-sm my-6">Dengan mengklik tombol berikut, Anda menyetujui <a href="#"
                        class="text-my-red">Syarat dan Ketentuan</a> serta
                    <a href="#" class="text-my-red">Kebijakan privasi.</a>
                </p>
                <form id="paymentForm" action="{{ route('book') }}" method="POST" x-data="{ processing: false }">
                    @csrf
                    @foreach ($items as $item)
                        <input type="hidden" name="items[]" value="{{ json_encode($item) }}">
                    @endforeach
                    <button type="button" @click="payment.showVerificationSelector()" :disabled="processing"
                        class="w-full bg-my-red font-bold text-xl text-white py-2 rounded-lg hover:bg-red-800 cursor-pointer"
                        x-bind:class="{ 'opacity-50 cursor-not-allowed': processing }">
                        Bayar Sekarang
                    </button>
                </form>
            </div>
        </div>

        <!-- Verification Method Modal -->
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
            x-show="payment.showVerificationMethodModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-cloak
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            <div class="bg-white rounded-lg p-8 max-w-lg w-full mx-4"
                @click.away="payment.showVerificationMethodModal = false"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95">

                <!-- Close button -->
                <div class="flex justify-end">
                    <button @click="payment.showVerificationMethodModal = false"
                        class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Header with icon -->
                <div class="flex justify-center mb-4">
                    <div class="bg-red-800 rounded-full p-4">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                </div>

                <h2 class="text-xl font-bold text-center mb-2">Pilih Metode Verifikasi</h2>

                <p class="text-gray-600 text-center mb-6">
                    Silahkan pilih salah satu metode di bawah ini untuk mendapatkan kode verifikasi yang akan
                    dikirimkan ke nomor
                    <span class="font-semibold"
                        x-text="payment.currentPhoneNumber && payment.currentPhoneNumber.length > 5 ? 
                        ('*******' + payment.currentPhoneNumber.substring(payment.currentPhoneNumber.length - 5)) : 
                        'nomor yang dimasukkan'">
                    </span>
                </p>

                <!-- Verification options -->
                <div class="space-y-3">
                    <!-- WhatsApp option -->
                    <button @click="payment.selectVerificationMethod('whatsapp')"
                        class="w-full flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        <svg class="w-6 h-6 text-green-500 mr-3" fill="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                        </svg>
                        <span class="font-medium">Kirim kode melalui WhatsApp</span>
                    </button>

                    <!-- Miscall option -->
                    <button @click="payment.selectVerificationMethod('miscall')"
                        class="w-full flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        <svg class="w-6 h-6 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M18.93 20.69c-1.23.83-2.73 1.3-4.36 1.3-1.05 0-2.07-.2-3.03-.56a18.38 18.38 0 01-3.06-1.75 22.43 22.43 0 01-2.77-2.3 22.35 22.35 0 01-2.3-2.77A18.38 18.38 0 011.66 11.5a11.63 11.63 0 01-.56-3.03c0-1.63.47-3.13 1.3-4.36.35-.51.98-.83 1.64-.83.23 0 .46.05.66.14h.02c.58.22 1.1.81 1.36 1.55.08.22.18.53.29.85.11.33.23.65.34.95.09.24.18.46.25.65.1.24.15.46.15.65 0 .25-.11.5-.31.75l-1.1 1.13c-.02.04-.03.09-.03.14 0 .05.01.1.02.14.27.92.8 1.85 1.57 2.77.8.96 1.75 1.82 2.81 2.57.07.05.15.07.22.07.1 0 .17-.03.24-.1l1.03-1.03c.24-.24.52-.36.8-.36.26 0 .5.1.7.3.55.51 1.02.62 1.68.89.26.1.56.22.88.34.3.11.6.22.8.29.75.26 1.35.78 1.57 1.36v.01c.09.21.14.43.14.67 0 .65-.32 1.27-.83 1.61z" />
                        </svg>
                        <span class="font-medium">Kirim kode melalui Miscall</span>
                    </button>

                    <!-- Email option -->
                    <button @click="payment.selectVerificationMethod('email')"
                        class="w-full flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        <svg class="w-6 h-6 text-red-500 mr-3" fill="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4V8l8 5 8-5v10zm-8-7L4 6h16l-8 5z" />
                        </svg>
                        <span class="font-medium">Kirim kode melalui Email</span>
                        <span class="text-xs text-gray-500 ml-2"
                            x-html="(() => {
                                const emailVal = $store.payment.currentEmail || document.getElementById('email')?.value || '';
                                if (!emailVal || !emailVal.includes('@')) return '';
                                
                                const atIndex = emailVal.indexOf('@');
                                const prefix = emailVal.substring(0, 4);
                                const maskedPart = '*'.repeat(Math.max(1, atIndex - 4));
                                const domain = emailVal.substring(atIndex);
                                
                                return prefix + maskedPart + domain;
                            })()"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Verification Code Modal -->
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
            x-show="payment.showVerificationCodeModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-cloak
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95">

                <!-- Close button -->
                <div class="flex justify-end">
                    <button @click="payment.showVerificationCodeModal = false" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Header with icon -->
                <div class="flex justify-center mb-4">
                    <div class="bg-red-800 rounded-full p-4">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                </div>

                <h2 class="text-xl font-bold text-center mb-2">Masukkan Kode Verifikasi</h2>

                <p class="text-gray-600 text-center mb-3">
                    Silahkan masukkan <span class="font-bold">6 digit</span> angka yang
                    tertera dalam
                    <template x-if="payment.selectedMethod === 'whatsapp'">
                        <span>WhatsApp.</span>
                    </template>
                    <template x-if="payment.selectedMethod === 'miscall'">
                        <span>panggilan telepon.</span>
                    </template>
                    <template x-if="payment.selectedMethod === 'email'">
                        <span>Email.</span>
                    </template>
                </p>

                <!-- Verification code input -->
                <div class="flex justify-center gap-3 my-6 verification-code-input">
                    <template x-for="(digit, index) in [0,1,2,3,4,5]" :key="index">
                        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" x-ref="digit_input"
                            x-model="payment.verificationCodeDigits[index]" @input="payment.onDigitInput(index, $event)"
                            @keydown="payment.onDigitKeydown(index, $event)"
                            class="w-10 h-10 text-center text-lg font-bold border border-gray-300 rounded-md bg-gray-50 focus:border-red-600 focus:outline-none focus:ring-2 focus:ring-red-600/20">
                    </template>
                </div>

                <div class="text-center text-sm text-gray-600 mb-6">
                    <p>Mohon tunggu <span x-text="payment.formatCountdown()"></span> untuk kirim ulang</p>
                    <button type="button" @click="payment.resendVerificationCode"
                        :disabled="payment.verificationCountdown > 0"
                        class="mt-2 text-red-800 font-semibold hover:underline focus:outline-none transition duration-200"
                        :class="payment.verificationCountdown > 0 ? 'opacity-50 cursor-not-allowed' : ''">
                        Kirim ulang kode
                    </button>
                </div>

                <!-- Verification button -->
                <button @click="payment.submitVerificationCode"
                    class="w-full bg-red-800 text-white font-bold py-3 px-4 rounded-lg hover:bg-red-900 transition">
                    Verifikasi
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            if (cart.length < 1) {
                Swal.fire({
                    title: 'Keranjang Kosong',
                    text: 'Silakan pilih lapangan terlebih dahulu.',
                    icon: 'warning',
                    confirmButtonColor: '#DC2626',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = "{{ route('venue') }}";
                });
            }

            // Handle snapToken for logged-in users
            const snapToken = '{{ $snapToken ?? '' }}';
            if (snapToken && window.isLoggedIn) {
                window.snap.pay(snapToken, {
                    onSuccess: function(result) {
                        console.log('Payment success:', result);
                        Alpine.store('cart').clearCart();
                        window.location.href = '{{ route('home') }}';
                    },
                    onPending: function(result) {
                        console.log('Payment pending:', result);
                        window.location.href = '{{ route('home') }}';
                    },
                    onError: function(result) {
                        console.log('Payment error:', result);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat pembayaran.',
                            icon: 'error',
                            confirmButtonColor: '#DC2626',
                            confirmButtonText: 'OK'
                        });
                    },
                    onClose: function() {
                        console.log('Payment popup closed');
                    }
                });
            }
        });

        window.sendVerificationCode = function(method, phoneNumber, email, successCallback) {
            const finalPhoneNumber = (method === 'whatsapp' || method === 'miscall') ?
                window.Alpine.store('payment').currentPhoneNumber :
                phoneNumber;
            Swal.fire({
                title: 'Mengirim kode verifikasi',
                text: 'Mohon tunggu sebentar...',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrfToken) {
                console.error('CSRF token tidak ditemukan!');
                Swal.fire({
                    title: 'Error!',
                    text: 'CSRF token tidak ditemukan.',
                    icon: 'error',
                    confirmButtonColor: '#DC2626',
                    confirmButtonText: 'OK'
                });
                return;
            }

            fetch('{{ route('send-verification') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        method: method,
                        phone_number: finalPhoneNumber,
                        email: email
                    })
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        return response.text().then(text => {
                            console.error('Response bukan JSON:', text);
                            throw new Error('Format respons tidak valid');
                        });
                    }
                    if (!response.ok) {
                        return response.json().then(errData => {
                            console.error('Error response:', errData);
                            throw new Error(errData.message || 'Network error: ' + response.status);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data);
                    Swal.close();
                    if (data.success) {
                        if (typeof successCallback === 'function') {
                            successCallback();
                        }
                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            text: data.message || 'Gagal mengirim kode verifikasi. Silakan coba lagi.',
                            icon: 'error',
                            confirmButtonColor: '#DC2626',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    Swal.close();
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan',
                        icon: 'error',
                        confirmButtonColor: '#DC2626',
                        confirmButtonText: 'OK'
                    });
                });
        };
    </script>
@endpush
