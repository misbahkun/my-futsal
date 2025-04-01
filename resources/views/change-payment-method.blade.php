@extends('layouts.app')

@section('title', 'Ubah Metode Pembayaran')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Logo -->
        <div class="mb-10">
            <a href="{{ route('home') }}">
                <img src="{{ asset('storage/image-removebg.png') }}" alt="My Futsal Logo">
            </a>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center">
                    <a href="{{ route('payment.pending', $transaction->id) }}" class="mr-3">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <h1 class="text-xl font-bold text-gray-800">Ubah Metode Pembayaran</h1>
                </div>
            </div>

            <div class="p-6">
                <div class="max-w-lg mx-auto">
                    <!-- Current Method -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold mb-3">Metode Pembayaran Saat Ini</h2>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 flex items-center">
                            @if(isset($bank))
                                <img src="{{ asset('storage/' . $bank->image) }}" alt="{{ $bank->name }}" class="h-10 mr-3">
                                <div>
                                    <p class="font-medium">{{ $bank->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $bank->account_number }}</p>
                                </div>
                            @else
                                <div class="bg-gray-100 rounded-full p-3 mr-3">
                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                                        <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium">{{ strtoupper($transaction->payment_method) }}</p>
                                    <p class="text-sm text-gray-500">Metode pembayaran saat ini</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Select New Method -->
                    <h2 class="text-lg font-semibold mb-4">Pilih Metode Pembayaran Baru</h2>
                    
                    <form action="{{ route('update-payment-method', $transaction->id) }}" method="POST" id="changeMethodForm">
                        @csrf
                        <div class="space-y-3">
                            @foreach ($banks as $bank)
                            <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition
                                {{ strtolower($bank->name) == $transaction->payment_method ? 'bg-red-50 border-my-red' : 'border-gray-200' }}">
                                <input type="radio" name="payment_method" value="{{ strtolower($bank->name) }}" 
                                    @if(strtolower($bank->name) == $transaction->payment_method) checked @endif
                                    class="h-4 w-4 text-my-red focus:ring-red-500">
                                <div class="ml-4 flex items-center">
                                    <img src="{{ asset('storage/' . $bank->image) }}" alt="{{ $bank->name }}" class="h-10 mr-3">
                                    <div>
                                        <p class="font-medium">{{ $bank->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $bank->type }}</p>
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>

                        @error('payment_method')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <div class="mt-8 flex space-x-4">
                            <button type="submit" class="bg-my-red text-white py-3 px-6 rounded-lg font-bold hover:bg-red-800 transition flex-1">
                                Simpan Perubahan
                            </button>
                            <a href="{{ route('payment.pending', $transaction->id) }}" class="border border-gray-300 text-gray-700 py-3 px-6 rounded-lg font-bold hover:bg-gray-50 transition text-center flex-1">
                                Batal
                            </a>
                        </div>
                    </form>

                    <div class="mt-6 text-sm text-gray-500">
                        <p>Catatan: Mengubah metode pembayaran tidak akan mengubah detail transaksi dan batas waktu pembayaran.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('changeMethodForm');
            const radioButtons = form.querySelectorAll('input[type="radio"]');

            radioButtons.forEach(radio => {
                radio.addEventListener('change', function() {
                    // Reset all labels
                    document.querySelectorAll('#changeMethodForm label').forEach(label => {
                        label.classList.remove('bg-red-50');
                        label.classList.add('border-gray-200');
                        label.classList.remove('border-my-red');
                    });
                    
                    // Highlight selected label
                    if (this.checked) {
                        const selectedLabel = this.closest('label');
                        selectedLabel.classList.add('bg-red-50');
                        selectedLabel.classList.remove('border-gray-200');
                        selectedLabel.classList.add('border-my-red');
                    }
                });
            });
        });
    </script>
@endpush