<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Schedule;
use App\Models\MyAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Show payment details and bank account for pending payment
     */
    public function showPaymentPending($transactionId)
    {
        try {
            // First, let's check and cancel any expired transactions
            $this->cancelExpiredTransactions();

            // Find the transaction
            $transaction = Transaction::with('user')
                ->findOrFail($transactionId);

            // Make sure the transaction belongs to the current user
            if ($transaction->user_id !== Auth::id()) {
                return redirect()->route('home')
                    ->with('error', 'Anda tidak memiliki akses ke transaksi ini.');
            }

            // If transaction is no longer 'unpaid' or 'pending_verification', redirect appropriately
            if ($transaction->status !== 'unpaid' && $transaction->status !== 'pending_verification') {
                if ($transaction->status === 'cancelled') {
                    return redirect()->route('home')
                        ->with('error', 'Transaksi ini telah dibatalkan karena melewati batas waktu pembayaran.');
                } elseif ($transaction->status === 'paid') {
                    return redirect()->route('booking.success', $transaction->id)
                        ->with('success', 'Pembayaran Anda telah diterima.');
                }
            }

            // Calculate remaining time for payment (15 minutes)
            // Hanya perlu menghitung deadline jika status masih unpaid
            $createdAt = Carbon::parse($transaction->created_at);
            $deadline = $createdAt->copy()->addMinutes(15);
            $now = Carbon::now();

            // If expired and status still unpaid, cancel the transaction and redirect
            if ($now->gt($deadline) && $transaction->status === 'unpaid') {
                $this->cancelTransaction($transaction);

                return redirect()->route('home')
                    ->with('info-cancelled', 'Transaksi ini telah dibatalkan karena melewati batas waktu pembayaran (15 menit).');
            }

            // Format deadline for JavaScript
            $deadline = $deadline->toIso8601String();

            // Get bank account information based on payment method
            $bank = MyAccount::where('name', $transaction->payment_method)
                ->orWhere('name', 'like', '%' . $transaction->payment_method . '%')
                ->first();

            // Get scheduled items from database
            $schedules = Schedule::where('transaction_id', $transaction->id)
                ->with('field.venue')
                ->get();

            $items = [];

            if ($schedules->count() > 0) {
                foreach ($schedules as $schedule) {
                    $items[] = [
                        'field' => $schedule->field->name,
                        'venue' => $schedule->field->venue->name,
                        'date' => Carbon::parse($schedule->date)->format('d M Y') . ' ' . $schedule->time_slots,
                        'price' => $schedule->price
                    ];
                }
            }

            return view('payment-pending', compact(
                'transaction',
                'bank',
                'deadline',
                'items'
            ));
        } catch (\Exception $e) {
            Log::error("Error in showPaymentPending: " . $e->getMessage());
            return redirect()->route('home')
                ->with('error', 'Terjadi kesalahan saat memuat halaman pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Upload payment proof
     */
    public function uploadPaymentProof(Request $request, $transactionId)
    {
        try {
            // Validate the request
            $request->validate([
                'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB max - ganti di php.ini
            ]);

            Log::info(['file' => $request->file('payment_proof')]);

            // Find the transaction
            $transaction = Transaction::findOrFail($transactionId);

            // Ensure the transaction belongs to the current user
            if ($transaction->user_id !== Auth::id()) {
                return redirect()->route('home')
                    ->with('error', 'Anda tidak memiliki akses ke transaksi ini.');
            }

            // Check if transaction is in a status that allows uploading
            // Allow both 'unpaid' and 'pending_verification' status
            if ($transaction->status !== 'unpaid' && $transaction->status !== 'pending_verification') {
                return redirect()->back()
                    ->with('error', 'Status transaksi ini tidak memungkinkan upload bukti pembayaran.');
            }

            // Handle file upload
            if ($request->hasFile('payment_proof')) {
                // Delete previous payment proof if exists
                if ($transaction->payment_proof) {
                    Storage::disk('public')->delete($transaction->payment_proof);
                }

                // Store the new file
                $path = $request->file('payment_proof')->store('payment_proofs', 'public');

                // Update transaction with proof and change status to pending_verification
                $transaction->update([
                    'payment_proof' => $path,
                    'status' => 'pending_verification' // Pastikan status selalu pending_verification setelah upload
                ]);

                return redirect()->route('payment.pending', $transaction->id)
                    ->with('success', $transaction->payment_proof ? 'Bukti pembayaran berhasil diperbarui.' : 'Bukti pembayaran berhasil diunggah.')
                    ->with('info', 'Pembayaran Anda sedang diverifikasi oleh admin.');
            }

            return redirect()->back()
                ->with('error', 'Gagal mengunggah bukti pembayaran.');
        } catch (\Exception $e) {
            Log::error("Error in uploadPaymentProof: " . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengunggah bukti pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Check and cancel all expired transactions (unpaid after 10 minutes)
     */
    public function cancelExpiredTransactions()
    {
        try {
            $expiredTransactions = Transaction::where('status', 'unpaid')
                ->where('created_at', '<', now()->subMinutes(15))
                ->get();

            $count = 0;
            foreach ($expiredTransactions as $transaction) {
                $this->cancelTransaction($transaction);
                $count++;
            }

            if ($count > 0) {
                Log::info("Cancelled {$count} expired transactions");
            }

            return $count;
        } catch (\Exception $e) {
            Log::error("Error cancelling expired transactions: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Cancel a specific transaction and delete it
     */
    private function cancelTransaction($transaction)
    {
        try {
            Schedule::where('transaction_id', $transaction->id)->delete();

            // Now delete the transaction
            $transaction->update([
                'status' => 'cancelled'
            ]);

            Log::info("Transaction #{$transaction->id} deleted");
            return true;
        } catch (\Exception $e) {
            Log::error("Error deleting transaction #{$transaction->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check payment status - can be called manually by user
     */
    public function checkPaymentStatus($transactionId)
    {
        try {
            // First, check and cancel any expired transactions
            $this->cancelExpiredTransactions();

            // Find the transaction
            $transaction = Transaction::findOrFail($transactionId);

            // Ensure the transaction belongs to the current user
            if ($transaction->user_id !== Auth::id()) {
                return redirect()->route('home')
                    ->with('error', 'Anda tidak memiliki akses ke transaksi ini.');
            }

            // Check transaction status and redirect accordingly
            if ($transaction->status === 'cancelled') {
                return redirect()->route('home')
                    ->with('error', 'Transaksi ini telah dibatalkan.');
            } elseif ($transaction->status === 'paid') {
                // If transaction is paid, redirect to invoice page
                return redirect()->route('invoice', $transaction->id)
                    ->with('success', 'Pembayaran Anda telah diterima. Berikut adalah invoice Anda.');
            } elseif ($transaction->status === 'pending_verification') {
                return redirect()->back()
                    ->with('info-wait-verification', 'Bukti pembayaran Anda sedang diverifikasi oleh admin. Silakan tunggu.');
            }

            return redirect()->back()
                ->with('info-wait-verification', 'Pembayaran belum terverifikasi. Silakan upload bukti transfer.');
        } catch (\Exception $e) {
            Log::error("Error in checkPaymentStatus: " . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memeriksa status pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Manual cancellation by user
     */
    public function cancelTransactionManually($transactionId)
    {
        try {
            // Find the transaction
            $transaction = Transaction::findOrFail($transactionId);

            // Ensure the transaction belongs to the current user
            if ($transaction->user_id !== Auth::id()) {
                return redirect()->route('home')
                    ->with('error', 'Anda tidak memiliki akses ke transaksi ini.');
            }

            // Cancel the transaction
            $this->cancelTransaction($transaction);

            return redirect()->route('home')
                ->with('success', 'Transaksi berhasil dibatalkan.');
        } catch (\Exception $e) {
            Log::error("Error in cancelTransactionManually: " . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat membatalkan transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Show form to change payment method
     */
    public function changePaymentMethod($transactionId)
    {
        try {
            // Find the transaction
            $transaction = Transaction::findOrFail($transactionId);

            // Ensure the transaction belongs to the current user
            if ($transaction->user_id !== Auth::id()) {
                return redirect()->route('home')
                    ->with('error', 'Anda tidak memiliki akses ke transaksi ini.');
            }

            // Check if payment proof already uploaded
            if ($transaction->payment_proof || $transaction->status === 'pending_verification') {
                return redirect()->route('payment.pending', $transaction->id)
                    ->with('error', 'Anda tidak dapat mengubah metode pembayaran setelah mengunggah bukti transfer.');
            }

            // Get available payment methods
            $banks = MyAccount::select('name', 'account_name', 'type', 'image')->get();

            return view('change-payment-method', compact('transaction', 'banks'));
        } catch (\Exception $e) {
            Log::error("Error in changePaymentMethod: " . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memuat halaman');
        }
    }

    /**
     * Process payment method change
     */
    public function updatePaymentMethod(Request $request, $transactionId)
    {
        try {
            // Validate input
            $request->validate([
                'payment_method' => 'required|string'
            ]);

            // Find the transaction
            $transaction = Transaction::findOrFail($transactionId);

            // Ensure the transaction belongs to the current user
            if ($transaction->user_id !== Auth::id()) {
                return redirect()->route('home')
                    ->with('error', 'Anda tidak memiliki akses ke transaksi ini.');
            }

            // Check if payment proof already uploaded
            if ($transaction->payment_proof || $transaction->status === 'pending_verification') {
                return redirect()->route('payment.pending', $transaction->id)
                    ->with('error', 'Anda tidak dapat mengubah metode pembayaran setelah mengunggah bukti transfer.');
            }

            // Update payment method
            $transaction->update([
                'payment_method' => $request->payment_method
            ]);

            return redirect()->route('payment.pending', $transaction->id)
                ->with('success', 'Metode pembayaran berhasil diubah.');
        } catch (\Exception $e) {
            Log::error("Error in updatePaymentMethod: " . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengubah metode pembayaran: ' . $e->getMessage());
        }
    }
}
