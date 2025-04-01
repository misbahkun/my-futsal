@extends('layouts.app')

@section('title', 'Menunggu Pembayaran')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Logo -->
        <div class="mb-10">
            <a href="{{ route('home') }}">
                <img src="{{ asset('storage/image-removebg.png') }}" alt="My Futsal Logo">
            </a>
        </div>

        <!-- Order Details -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6">
                <h1 class="text-xl font-bold text-gray-800">Detail Order • Booking Venue</h1>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2 border-t border-gray-100">
                    <div class="p-6 border-b border-gray-100" x-data="{
                        deadline: '{{ $deadline }}',
                        minutes: '00',
                        seconds: '00',
                        timeLeft: 0,
                        paymentProofUploaded: {{ $transaction->payment_proof ? 'true' : 'false' }},
                        updateCountdown() {
                            // Jika bukti pembayaran sudah diupload, hentikan timer
                            if (this.paymentProofUploaded) {
                                this.minutes = '--';
                                this.seconds = '--';
                                return;
                            }
                    
                            const now = new Date().getTime();
                            const deadlineTime = new Date(this.deadline).getTime();
                            this.timeLeft = deadlineTime - now;
                    
                            if (this.timeLeft <= 0) {
                                this.minutes = '00';
                                this.seconds = '00';
                                // Reload page to show expired status
                                window.location.reload();
                                return;
                            }
                    
                            // Calculate minutes and seconds
                            const minutesTotal = Math.floor((this.timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                            this.seconds = Math.floor((this.timeLeft % (1000 * 60)) / 1000);
                    
                            // Add leading zeros
                            this.minutes = minutesTotal < 10 ? '0' + minutesTotal : minutesTotal;
                            this.seconds = this.seconds < 10 ? '0' + this.seconds : this.seconds;
                        }
                    }" x-init="updateCountdown();
                    setInterval(() => updateCountdown(), 1000)">
                        <div class="flex items-start">
                            <div class="bg-orange-50 rounded-full p-3 mr-4">
                                <svg class="w-6 h-6 text-orange-500" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-800"
                                    x-text="paymentProofUploaded ? 'Menunggu Verifikasi' : 'Menunggu Pembayaran'"></h2>
                                <template x-if="!paymentProofUploaded">
                                    <p class="text-sm text-gray-600">
                                        Selesaikan pembayaran dalam
                                        <span class="font-semibold countdown" :class="{ 'text-red-600': timeLeft < 300000 }"
                                            x-text="`${minutes}:${seconds}`"></span>
                                        <span x-show="timeLeft < 300000" class="text-red-600 font-bold">
                                            (Hampir kedaluwarsa!)
                                        </span>
                                    </p>
                                </template>
                                <template x-if="paymentProofUploaded">
                                    <p class="text-sm text-my-red">
                                        Bukti pembayaran Anda sedang diverifikasi oleh admin.
                                    </p>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Bank Info -->
                    <div class="p-6 border-b border-gray-100">
                        @if (isset($bank))
                            <div class="flex items-center mb-4">
                                <img src="{{ asset('storage/' . $bank->image) }}" alt="{{ $bank->name }}"
                                    class="h-10 mr-3">
                                <h3 class="text-lg font-bold">{{ $bank->name }}</h3>
                            </div>

                            <!-- Account Number -->
                            <div class="mb-6">
                                <p class="text-sm text-gray-600 mb-2">Nomor Rekening</p>
                                <div class="flex items-center">
                                    <span class="text-xl font-mono font-bold mr-3">{{ $bank->account_number }}</span>
                                    <button class="text-red-600 font-medium"
                                        onclick="copyToClipboard('{{ $bank->account_number }}')">Salin</button>
                                </div>
                            </div>

                            <!-- Account Name -->
                            <div class="mb-6">
                                <p class="text-sm text-gray-600 mb-2">Atas Nama</p>
                                <div class="flex items-center">
                                    <span class="text-xl font-bold mr-3">{{ $bank->account_name }}</span>
                                </div>
                            </div>
                        @else
                            <!-- Fallback if bank not found -->
                            <div class="flex items-center mb-4">
                                <h3 class="text-lg font-bold">Bank Transfer</h3>
                            </div>
                            <p class="text-sm text-gray-600">Silahkan hubungi admin untuk informasi nomor rekening.</p>
                        @endif

                        <!-- Total Amount -->
                        <div>
                            <p class="text-sm text-gray-600 mb-2">Total Pembayaran</p>
                            <div class="flex items-center">
                                <span
                                    class="text-xl font-bold mr-3">Rp{{ number_format($transaction->amount, 0, ',', '.') }}</span>
                                <button class="text-red-600 font-medium"
                                    onclick="copyToClipboard('{{ $transaction->amount }}')">Salin</button>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="p-6">
                        <h3 class="text-lg font-bold mb-4">Rincian Pembayaran</h3>

                        <!-- Biaya Sewa -->
                        <div class="mb-6">
                            <p class="font-medium text-gray-800 mb-3">Biaya Sewa</p>

                            @if (isset($items) && is_array($items) && count($items) > 0)
                                <!-- If items array exists from session or transaction data -->
                                @foreach ($items as $item)
                                    <div class="flex justify-between mb-3">
                                        <div>
                                            <p class="font-medium">{{ $item['field'] }} - {{ $item['venue'] }}</p>
                                            <p class="text-sm text-gray-500">{{ $item['date'] }}</p>
                                        </div>
                                        <p>Rp{{ number_format($item['price'], 0, ',', '.') }}</p>
                                    </div>
                                @endforeach
                            @elseif(isset($schedule))
                                <!-- Fallback to schedule if available -->
                                <div class="flex justify-between mb-3">
                                    <div>
                                        <p class="font-medium">{{ $schedule->field->name }}</p>
                                        <p class="text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($schedule->date)->format('d M Y') }}
                                            {{ $schedule->time_slots }}</p>
                                    </div>
                                    <p>Rp{{ number_format($transaction->amount, 0, ',', '.') }}</p>
                                </div>
                            @else
                                <p class="text-sm text-gray-500">Tidak ada detail item tersedia</p>
                            @endif
                        </div>

                        <!-- Diskon -->
                        <div class="flex justify-between mt-3">
                            <p>Diskon</p>
                            <p>Rp0</p>
                        </div>

                        <!-- Total -->
                        <div class="flex justify-between font-semibold border-t border-gray-200 pt-4">
                            <p>Total Biaya</p>
                            <p>Rp{{ number_format($transaction->amount, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Payment Methods and Upload Form -->
                <div class="bg-gray-50 p-6">
                    <!-- Upload Payment Proof Form -->
                    <div class="mb-8 bg-white rounded-lg shadow-sm p-5 border border-gray-200" x-data="{ showReuploadForm: false }">
                        <h3 class="text-lg font-bold mb-4 flex items-center">
                            <span
                                class="bg-green-100 text-green-600 rounded-full w-6 h-6 flex items-center justify-center mr-2">✓</span>
                            Upload Bukti Transfer
                        </h3>

                        @if ($transaction->payment_proof)
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <p class="text-sm font-medium text-green-800">Bukti pembayaran telah diunggah</p>
                                </div>
                                <p class="text-xs text-green-600 mt-2">Pembayaran Anda sedang diverifikasi oleh admin</p>

                                <div class="mt-3">
                                    <img src="{{ asset('storage/' . $transaction->payment_proof) }}" alt="Bukti Pembayaran"
                                        class="w-full rounded-lg border border-gray-200">
                                </div>

                                <!-- Tombol untuk upload ulang bukti pembayaran -->
                                <div class="mt-4" x-show="!showReuploadForm">
                                    <p class="text-sm text-gray-600 mb-2">Jika bukti pembayaran tidak jelas atau ingin
                                        mengupload ulang:</p>
                                    <button type="button" @click="showReuploadForm = true"
                                        class="text-my-red hover:text-red-800 font-medium text-sm">
                                        Upload Bukti Pembayaran Baru
                                    </button>
                                </div>

                                <!-- Form upload yang awalnya tersembunyi -->
                                <div x-show="showReuploadForm" x-transition class="mt-4">
                                    <form action="{{ route('payment.upload-proof', $transaction->id) }}" method="POST"
                                        enctype="multipart/form-data" class="space-y-4">
                                        @csrf
                                        <div>
                                            <div
                                                class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 bg-gray-50 hover:bg-gray-100 transition">
                                                <input id="reupload_proof" name="payment_proof" type="file"
                                                    accept="image/*"
                                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                                    required @change="fileSelected($event)">
                                                <div class="text-center">
                                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor"
                                                        fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                        <path
                                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                    <p class="mt-1 text-sm text-gray-600" id="new-file-name">Klik untuk
                                                        memilih file
                                                        atau tarik file ke sini</p>
                                                    <p class="text-xs text-gray-500 mt-2">
                                                        PNG, JPG, JPEG hingga 5MB
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex space-x-3">
                                            <button type="submit"
                                                class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg font-bold text-center transition flex-1">
                                                Upload
                                            </button>
                                            <button type="button" @click="showReuploadForm = false"
                                                class="border border-gray-300 text-gray-700 py-2 px-4 rounded-lg font-bold text-center hover:bg-gray-50 transition flex-1">
                                                Batal
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @else
                            <form action="{{ route('payment.upload-proof', $transaction->id) }}" method="POST"
                                enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="payment_proof" class="block text-sm font-medium text-gray-700 mb-1">Unggah
                                        Bukti Transfer</label>
                                    <div
                                        class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 bg-gray-50 hover:bg-gray-100 transition">
                                        <input id="payment_proof" name="payment_proof" type="file" accept="image/*"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required>
                                        <div class="text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor"
                                                fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                <path
                                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <p class="mt-1 text-sm text-gray-600" id="file-name">Klik untuk memilih file
                                                atau tarik file ke sini</p>
                                            <p class="text-xs text-gray-500 mt-2">
                                                PNG, JPG, JPEG hingga 5MB
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit"
                                    class="bg-green-600 hover:bg-green-700 text-white w-full py-3 rounded-lg font-bold text-center transition">
                                    Kirim Bukti Pembayaran
                                </button>
                            </form>
                        @endif
                    </div>

                    <h3 class="text-lg font-bold mb-6 flex items-center">
                        <span
                            class="bg-red-100 text-red-600 rounded-full w-6 h-6 flex items-center justify-center mr-2">•</span>
                        Cara Bayar
                    </h3>

                    <!-- Payment Method Accordions -->
                    <div class="space-y-4">
                        <!-- ATM Transfer -->
                        <div x-data="{ open: false }" class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div @click="open = !open" class="flex justify-between items-center p-4 cursor-pointer">
                                <span class="font-medium">Transfer ATM</span>
                                <svg :class="{ 'rotate-180': open }" class="w-5 h-5 transform transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                            <div x-show="open" class="p-4 border-t border-gray-100">
                                <ol class="list-decimal list-inside space-y-2 text-sm">
                                    <li>Masukkan kartu ATM & PIN Anda</li>
                                    <li>Pilih menu <strong>Transaksi Lainnya > Transfer > Ke Rek Bank Lain</strong></li>
                                    <li>Masukkan kode bank dan nomor rekening tujuan</li>
                                    <li>Masukkan jumlah transfer sesuai dengan total pembayaran</li>
                                    <li>Periksa informasi di layar, pastikan nomor rekening dan jumlah sudah benar</li>
                                    <li>Konfirmasi transaksi dan simpan bukti transfer</li>
                                </ol>
                            </div>
                        </div>

                        <!-- Mobile Banking -->
                        <div x-data="{ open: false }" class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div @click="open = !open" class="flex justify-between items-center p-4 cursor-pointer">
                                <span class="font-medium">Mobile Banking</span>
                                <svg :class="{ 'rotate-180': open }" class="w-5 h-5 transform transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                            <div x-show="open" class="p-4 border-t border-gray-100">
                                <ol class="list-decimal list-inside space-y-2 text-sm">
                                    <li>Login ke aplikasi Mobile Banking</li>
                                    <li>Pilih menu <strong>Transfer</strong></li>
                                    <li>Pilih <strong>Transfer ke Bank Lain</strong></li>
                                    <li>Masukkan nomor rekening tujuan</li>
                                    <li>Masukkan jumlah sesuai total pembayaran</li>
                                    <li>Periksa kembali detail transaksi</li>
                                    <li>Masukkan PIN atau password untuk konfirmasi</li>
                                    <li>Simpan bukti transfer</li>
                                </ol>
                            </div>
                        </div>

                        <!-- Internet Banking -->
                        <div x-data="{ open: false }" class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div @click="open = !open" class="flex justify-between items-center p-4 cursor-pointer">
                                <span class="font-medium">Internet Banking</span>
                                <svg :class="{ 'rotate-180': open }" class="w-5 h-5 transform transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                            <div x-show="open" class="p-4 border-t border-gray-100">
                                <ol class="list-decimal list-inside space-y-2 text-sm">
                                    <li>Login ke website Internet Banking</li>
                                    <li>Pilih menu <strong>Transfer</strong></li>
                                    <li>Pilih <strong>Transfer ke Bank Lain</strong></li>
                                    <li>Masukkan nomor rekening tujuan</li>
                                    <li>Masukkan nominal transfer</li>
                                    <li>Masukkan rincian berita transfer (opsional)</li>
                                    <li>Konfirmasi transaksi dengan token/OTP</li>
                                    <li>Simpan atau cetak bukti transfer</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 space-y-4">
                        <a href="{{ route('check-payment-status', $transaction->id) }}"
                            class="bg-red-700 text-white w-full py-3 rounded-lg font-bold text-center block hover:bg-red-800 transition">
                            Cek Status Pembayaran
                        </a>

                        <a href="{{ route('change-payment-method', $transaction->id) }}"
                            class="border border-red-700 text-red-700 w-full py-3 rounded-lg font-bold text-center block hover:bg-red-50 transition">
                            Ubah Metode Pembayaran
                        </a>

                        <form action="{{ route('cancel-transaction', $transaction->id) }}" method="POST"
                            id="cancelTransactionForm" onclick="confirmCancelTransaction()">
                            @csrf
                            <button type="button"
                                class="text-gray-600 w-full py-3 rounded-lg font-bold text-center block hover:bg-gray-100 transition">
                                Batalkan Transaksi
                            </button>
                        </form>

                        <a href="https://wa.me/6281286558895?text=Halo%20Admin%20saya%20ingin%20minta%20bantuan%20untuk%20id%20transaksi%20{{ $transaction->id }}%20saya."
                            target="_blank" rel="noopener noreferrer"
                            class="text-gray-600 w-full py-3 rounded-lg font-bold text-center block hover:bg-gray-100 transition">
                            Bantuan
                        </a>
                    </div>

                    <div class="mt-6 text-xs text-gray-500 text-center">
                        Waktu Operasional Customer Service: Senin-Jum'at pukul 09:00-21:00 WIB
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                Swal.fire({
                    title: 'Berhasil Disalin!',
                    text: 'Teks telah disalin ke clipboard',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });
            }, (err) => {
                console.error('Could not copy text: ', err);
            });
        }

        function confirmCancelTransaction() {
            Swal.fire({
                title: 'Batalkan Transaksi?',
                text: "Apakah Anda yakin ingin membatalkan transaksi ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DC2626',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Ya, Batalkan',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('cancelTransactionForm').submit();
                }
            });
        }

        // Untuk menangani validasi file
        document.addEventListener('DOMContentLoaded', function() {
            window.fileSelected = function(event) {
                const fileNameDisplay = document.getElementById('new-file-name');
                const maxFileSize = 5 * 1024 * 1024; // 5MB dalam bytes

                if (event.target.files && event.target.files[0]) {
                    const file = event.target.files[0];

                    // Validasi ukuran file
                    if (file.size > maxFileSize) {
                        Swal.fire({
                            title: 'File Terlalu Besar',
                            text: 'Ukuran file tidak boleh lebih dari 5MB',
                            icon: 'error',
                            confirmButtonColor: '#DC2626'
                        });
                        event.target.value = ''; // Reset input file
                        if (fileNameDisplay) {
                            fileNameDisplay.textContent = 'Klik untuk memilih file atau tarik file ke sini';
                        }
                        return;
                    }

                    if (fileNameDisplay) {
                        fileNameDisplay.textContent = file.name;
                    }
                } else if (fileNameDisplay) {
                    fileNameDisplay.textContent = 'Klik untuk memilih file atau tarik file ke sini';
                }
            };

            // Validasi file upload pertama (tetap dipertahankan)
            const input = document.getElementById('payment_proof');
            const fileNameDisplay = document.getElementById('file-name');
            const maxFileSize = 5 * 1024 * 1024; // 5MB dalam bytes

            if (input && fileNameDisplay) {
                input.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const file = this.files[0];

                        // Validasi ukuran file
                        if (file.size > maxFileSize) {
                            Swal.fire({
                                title: 'File Terlalu Besar',
                                text: 'Ukuran file tidak boleh lebih dari 5MB',
                                icon: 'error',
                                confirmButtonColor: '#DC2626'
                            });
                            this.value = ''; // Reset input file
                            fileNameDisplay.textContent = 'Klik untuk memilih file atau tarik file ke sini';
                            return;
                        }

                        fileNameDisplay.textContent = file.name;
                    } else {
                        fileNameDisplay.textContent = 'Klik untuk memilih file atau tarik file ke sini';
                    }
                });
            }
        });
    </script>
@endpush
