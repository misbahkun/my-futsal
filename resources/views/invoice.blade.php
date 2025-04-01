@extends('layouts.app')

@section('title', 'Invoice Booking')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Logo & Header -->
        <div class="mb-10 flex justify-between items-center">
            <a href="{{ route('home') }}">
                <img src="{{ asset('storage/image-removebg.png') }}" alt="My Futsal Logo" class="h-16">
            </a>
            <div>
                <a href="{{ route('generate.invoice.pdf', $transaction->id) }}"
                    class="bg-my-red text-white px-6 py-2 rounded-lg font-bold hover:bg-red-800 inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Download PDF
                </a>
            </div>
        </div>

        <!-- Invoice Content -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100">
            <!-- Invoice Header -->
            <div class="bg-gray-50 p-6 border-b border-gray-100">
                <div class="flex flex-col md:flex-row justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">INVOICE</h1>
                        <p class="text-gray-600 mt-1">
                            <span class="font-semibold">No. Invoice:</span>
                            INV-{{ $transaction->id }}-{{ date('Ymd', strtotime($transaction->created_at)) }}
                        </p>
                        <p class="text-gray-600">
                            <span class="font-semibold">Tanggal:</span>
                            {{ date('d F Y', strtotime($transaction->updated_at)) }}
                        </p>
                    </div>
                    <div class="mt-4 md:mt-0 md:text-right">
                        <div
                            class="inline-block px-4 py-1 rounded-full bg-green-100 text-green-800 font-semibold text-sm mb-2">
                            LUNAS
                        </div>
                        <p class="text-gray-600">
                            <span class="font-semibold">Metode Pembayaran:</span>
                            {{ ucwords($transaction->payment_method) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Customer Info & Merchant Info -->
            <div class="p-6 border-b border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Customer Info -->
                    <div>
                        <h2 class="text-lg font-bold text-gray-800 mb-3">Informasi Penyewa</h2>
                        <p class="text-gray-600">{{ $transaction->user->full_name }}</p>
                        <p class="text-gray-600">{{ $transaction->user->email }}</p>
                        <p class="text-gray-600">{{ $transaction->user->phone_number }}</p>
                    </div>

                    <!-- Merchant Info -->
                    <div class="md:text-right">
                        <h2 class="text-lg font-bold text-gray-800 mb-3">Informasi Merchant</h2>
                        <p class="text-gray-600">MyFutsal</p>
                        <p class="text-gray-600">contact@myfutsal.com</p>
                        <p class="text-gray-600">+62 812-8655-8895</p>
                    </div>
                </div>
            </div>

            <!-- Booking Details -->
            <div class="p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Detail Booking</h2>

                <!-- Items Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="py-2 px-4 text-left border-b border-gray-200">Lapangan</th>
                                <th class="py-2 px-4 text-left border-b border-gray-200">Venue</th>
                                <th class="py-2 px-4 text-left border-b border-gray-200">Tanggal & Jam</th>
                                <th class="py-2 px-4 text-right border-b border-gray-200">Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($schedules as $schedule)
                                <tr>
                                    <td class="py-4 px-4 border-b border-gray-200">{{ $schedule->field->name }}</td>
                                    <td class="py-4 px-4 border-b border-gray-200">{{ $schedule->field->venue->name }}</td>
                                    <td class="py-4 px-4 border-b border-gray-200">
                                        {{ \Carbon\Carbon::parse($schedule->date)->format('d M Y') }}
                                        <span class="text-gray-600 text-sm">{{ $schedule->time_slots }}</span>
                                    </td>
                                    <td class="py-4 px-4 text-right border-b border-gray-200">
                                        Rp{{ number_format($schedule->price, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="py-4 px-4 text-right font-semibold">Subtotal</td>
                                <td class="py-4 px-4 text-right">Rp{{ number_format($transaction->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="py-4 px-4 text-right font-semibold">Diskon</td>
                                <td class="py-4 px-4 text-right">Rp0</td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td colspan="3" class="py-4 px-4 text-right font-bold">Total</td>
                                <td class="py-4 px-4 text-right font-bold">
                                    Rp{{ number_format($transaction->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Notes & Terms -->
                <div class="mt-8 border-t border-gray-200 pt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-md font-bold text-gray-800 mb-2">Catatan</h3>
                            <p class="text-sm text-gray-600">
                                Invoice ini adalah bukti resmi pembayaran. Mohon untuk membawa bukti invoice saat akan
                                bermain. Tunjukkan invoice ini kepada petugas di venue.
                            </p>
                        </div>
                        <div>
                            <h3 class="text-md font-bold text-gray-800 mb-2">Syarat & Ketentuan</h3>
                            <ul class="text-sm text-gray-600 list-disc pl-5">
                                <li>Pembayaran yang sudah dilakukan tidak dapat dikembalikan</li>
                                <li>Jadwal yang sudah dipesan tidak dapat dibatalkan</li>
                                <li>Pastikan untuk datang 15 menit sebelum waktu bermain</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thank You Note -->
            <div class="p-6 bg-gray-50 border-t border-gray-100 text-center">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Terima Kasih Atas Pembayaran Anda!</h3>
                <p class="text-gray-600">Jika Anda memiliki pertanyaan, silakan hubungi kami melalui WhatsApp di +62
                    812-8655-8895</p>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-8 text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center text-my-red hover:text-red-800">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Kembali ke Beranda
            </a>
        </div>
    </div>
@endsection
