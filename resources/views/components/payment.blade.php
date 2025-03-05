<!-- Payment Modal -->
<div x-show="open" class="fixed inset-0 flex items-center justify-center z-50">
    <div @click="open = false" class="fixed inset-0 bg-black opacity-50"></div>
    <div
        class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
        <div class="flex justify-between px-5 py-4 sm:px-6 sm:py-5">
            <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                Pembayaran Manual
            </h3>
            <img @click="open = false"
                :src="darkMode ? '{{ asset('storage/close-white.png') }}' : '{{ asset('storage/close.png') }}'"
                class="w-8 h-8 cursor-pointer" alt="close button">
        </div>
        <div class="space-y-6 border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
            <p>Silakan transfer ke rekening berikut:</p>
            <p><strong>Bank ABC</strong></p>
            <p><strong>No. Rekening: 1234567890</strong></p>
            <p><strong>Atas Nama: My Futsal</strong></p>
            <form action="{{ route('confirmPayment', ['transactionId' => $transaction->id]) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Upload Bukti Pembayaran
                    </label>
                    <input type="file" name="payment_proof"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                </div>
                <button type="submit"
                    class="w-full bg-my-red font-bold text-xl text-white py-2 rounded-lg hover:bg-red-800 cursor-pointer mt-3">
                    Konfirmasi Pembayaran
                </button>
            </form>
        </div>
    </div>
</div>
